<?php
session_start();
require_once 'db.php';
require_once 'auth_helpers.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    header('Location: index.php');
    exit;
}

$user = verify_email_token($pdo, $token);

if (!$user) {
    ?>
    <!DOCTYPE html>
    <html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Roo - Verifikacija</title>
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="auth-utility-page">
        <div class="auth-utility-card">
            <div class="auth-utility-header">
                <h1>Ups...</h1>
                <p>Verifikacija nije uspjela.</p>
            </div>

            <div class="alert alert-danger">
                Verifikacijski link nije valjan ili je istekao.
            </div>

            <div class="auth-utility-actions">
                <p class="switch-text">
                    <a href="resend-verification.php">Pošalji novi verifikacijski link</a><br>
                    <a href="index.php">Povratak na prijavu</a>
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

login_user($user);

if (!empty($_SESSION['pending_signup_remember']) && !empty($_SESSION['pending_verify_user_id'])) {
    if ((int)$_SESSION['pending_verify_user_id'] === (int)$user['id']) {
        create_remember_me($pdo, (int)$user['id']);
    }
}

unset($_SESSION['pending_verification_email']);
unset($_SESSION['pending_signup_remember']);
unset($_SESSION['pending_verify_user_id']);

$stmt = $pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?");
$stmt->execute([$user['id']]);

header('Location: upoznavanje.php');
exit;