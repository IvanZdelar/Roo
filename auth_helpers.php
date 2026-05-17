<?php
require_once 'db.php';

function base_url(): string {
    return 'http://localhost/roo';
}

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function normalize_email(string $email): string {
    return mb_strtolower(trim($email));
}

function generate_token(int $bytes = 32): string {
    return bin2hex(random_bytes($bytes));
}

function hash_token(string $token): string {
    return hash('sha256', $token);
}

function redirect(string $path): void {
    header("Location: $path");
    exit;
}

function find_user_by_email(PDO $pdo, string $email): ?array {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([normalize_email($email)]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function find_user_by_username(PDO $pdo, string $username): ?array {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE korisnicko_ime = ?");
    $stmt->execute([trim($username)]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function create_verification_token(PDO $pdo, int $userId): string {
    $rawToken = generate_token(32);
    $hash = hash_token($rawToken);
    $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60 * 24);

    $stmt = $pdo->prepare("
        UPDATE users
        SET verification_token_hash = ?, verification_expires_at = ?
        WHERE id = ?
    ");
    $stmt->execute([$hash, $expiresAt, $userId]);

    return $rawToken;
}

function verify_email_token(PDO $pdo, string $token): ?array {
    $hash = hash_token($token);

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE verification_token_hash = ?
        LIMIT 1
    ");
    $stmt->execute([$hash]);
    $user = $stmt->fetch();

    if (!$user) {
        return null;
    }

    if (!$user['verification_expires_at'] || strtotime($user['verification_expires_at']) < time()) {
        return null;
    }

    $stmt = $pdo->prepare("
        UPDATE users
        SET is_verified = 1,
            verified_at = NOW(),
            verification_token_hash = NULL,
            verification_expires_at = NULL
        WHERE id = ?
    ");
    $stmt->execute([$user['id']]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $updatedUser = $stmt->fetch();

    return $updatedUser ?: null;
}

function create_reset_token(PDO $pdo, int $userId): string {
    $rawToken = generate_token(32);
    $hash = hash_token($rawToken);
    $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60);

    $stmt = $pdo->prepare("
        UPDATE users
        SET reset_token_hash = ?, reset_expires_at = ?
        WHERE id = ?
    ");
    $stmt->execute([$hash, $expiresAt, $userId]);

    return $rawToken;
}

function validate_reset_token(PDO $pdo, string $token): ?array {
    $hash = hash_token($token);

    $stmt = $pdo->prepare("
        SELECT id, email, reset_expires_at
        FROM users
        WHERE reset_token_hash = ?
        LIMIT 1
    ");
    $stmt->execute([$hash]);
    $user = $stmt->fetch();

    if (!$user) {
        return null;
    }

    if (!$user['reset_expires_at'] || strtotime($user['reset_expires_at']) < time()) {
        return null;
    }

    return $user;
}

function complete_password_reset(PDO $pdo, int $userId, string $newPassword): void {
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_expires_at = NULL
        WHERE id = ?
    ");
    $stmt->execute([$passwordHash, $userId]);

    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
    $stmt->execute([$userId]);
}

function record_login_attempt(PDO $pdo, string $email): void {
    $stmt = $pdo->prepare("
        INSERT INTO login_attempts (email, ip_address, attempted_at)
        VALUES (?, ?, NOW())
    ");
    $stmt->execute([normalize_email($email), client_ip()]);
}

function clear_old_login_attempts(PDO $pdo): void {
    $stmt = $pdo->prepare("
        DELETE FROM login_attempts
        WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->execute();
}

function is_rate_limited(PDO $pdo, string $email): bool {
    clear_old_login_attempts($pdo);

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM login_attempts
        WHERE email = ?
          AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    ");
    $stmt->execute([normalize_email($email)]);
    $count = (int)$stmt->fetchColumn();

    return $count >= 5;
}

function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['ime'];
}

function create_remember_me(PDO $pdo, int $userId): void {
    $selector = bin2hex(random_bytes(6));
    $rawToken = generate_token(32);
    $tokenHash = hash_token($rawToken);
    $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30);

    $stmt = $pdo->prepare("
        INSERT INTO remember_tokens (user_id, selector, token_hash, expires_at)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $selector, $tokenHash, $expiresAt]);

    $cookieValue = $selector . ':' . $rawToken;

    setcookie(
        'roo_remember',
        $cookieValue,
        [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => false
        ]
    );
}

function clear_remember_me(PDO $pdo): void {
    if (!empty($_COOKIE['roo_remember'])) {
        $parts = explode(':', $_COOKIE['roo_remember'], 2);
        if (count($parts) === 2) {
            $selector = $parts[0];
            $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE selector = ?");
            $stmt->execute([$selector]);
        }
    }

    setcookie(
        'roo_remember',
        '',
        [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => false
        ]
    );
}

function try_remember_login(PDO $pdo): bool
{
    if (empty($_COOKIE['remember_me'])) {
        return false;
    }

    $parts = explode(':', $_COOKIE['remember_me']);

    if (count($parts) !== 2) {
        return false;
    }

    [$selector, $validator] = $parts;

    $stmt = $pdo->prepare("
        SELECT *
        FROM remember_tokens
        WHERE selector = ?
        LIMIT 1
    ");

    $stmt->execute([$selector]);

    $token = $stmt->fetch();

    if (!$token) {
        return false;
    }

    if (strtotime($token['expires_at']) < time()) {
        clear_remember_cookie();
        return false;
    }

    $hashedValidator = hash('sha256', $validator);

    if (!hash_equals($token['hashed_validator'], $hashedValidator)) {
        clear_remember_cookie();
        return false;
    }

    $userStmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $userStmt->execute([$token['user_id']]);

    $user = $userStmt->fetch();

    // USER DELETED
    if (!$user) {

        $deleteStmt = $pdo->prepare("
            DELETE FROM remember_tokens
            WHERE id = ?
        ");

        $deleteStmt->execute([$token['id']]);

        clear_remember_cookie();

        session_unset();
        session_destroy();

        return false;
    }

    $_SESSION['user_id'] = $user['id'];

    return true;
}

function clear_remember_cookie(): void
{
    setcookie(
        'remember_me',
        '',
        time() - 3600,
        '/',
        '',
        false,
        true
    );
}