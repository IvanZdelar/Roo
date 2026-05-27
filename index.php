<?php
session_start();
$pdo = require 'db.php';
require_once 'auth_helpers.php';
require_once 'mail_helpers.php';

if (isset($_SESSION['user_id']) || try_remember_login($pdo)) {
    redirect('dashboard.php');
}

$registerError = '';
$loginError = '';

$registerData = [
    'ime' => '',
    'prezime' => '',
    'mail' => ''
];

$loginData = [
    'mail' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'register') {
        $ime = trim($_POST['ime'] ?? '');
        $prezime = trim($_POST['prezime'] ?? '');
        $email = normalize_email($_POST['mail'] ?? '');
        $lozinka = $_POST['lozinka'] ?? '';
        $potvrda = $_POST['potvrda'] ?? '';
        $rememberSignup = isset($_POST['remember_me_signup']);

        $registerData['ime'] = $ime;
        $registerData['prezime'] = $prezime;
        $registerData['mail'] = $email;

        if ($ime === '' || $prezime === '' || $email === '' || $lozinka === '' || $potvrda === '') {
            $registerError = 'Molimo ispunite sva polja.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registerError = 'E-pošta nije ispravna.';
        } elseif (strlen($lozinka) < 8) {
            $registerError = 'Lozinka mora imati barem 8 znakova.';
        } elseif ($lozinka !== $potvrda) {
            $registerError = 'Lozinke se ne podudaraju.';
        } elseif (find_user_by_email($pdo, $email)) {
            $registerError = 'Korisnik s tom e-poštom već postoji.';
        } else {
            $passwordHash = password_hash($lozinka, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (ime, prezime, email, password_hash, is_verified)
                VALUES (?, ?, ?, ?, 0)
            ");
            $stmt->execute([$ime, $prezime, $email, $passwordHash]);

            $userId = (int)$pdo->lastInsertId();
            $token = create_verification_token($pdo, $userId);
            send_verification_email($email, $ime, $token);

            $_SESSION['pending_verification_email'] = $email;
            $_SESSION['pending_signup_remember'] = $rememberSignup;
            $_SESSION['pending_verify_user_id'] = $userId;

            redirect('verify-notice.php');
        }
    }

    if ($formType === 'login') {
        $email = normalize_email($_POST['mail'] ?? '');
        $lozinka = $_POST['lozinka'] ?? '';
        $remember = isset($_POST['remember_me']);

        $loginData['mail'] = $email;

        if ($email === '' || $lozinka === '') {
            $loginError = 'Unesite e-poštu i lozinku.';
        } elseif (is_rate_limited($pdo, $email)) {
            $loginError = 'Previše pokušaja prijave. Pokušaj ponovno za 15 minuta.';
        } else {
            $user = find_user_by_email($pdo, $email);

            if (!$user || !password_verify($lozinka, $user['password_hash'])) {
                record_login_attempt($pdo, $email);
                $loginError = 'Neispravna e-pošta ili lozinka.';
            } elseif (!(int)$user['is_verified']) {
                $loginError = 'Račun nije potvrđen. Provjeri e-poštu ili pošalji novu potvrdu.';
            } else {
                login_user($user);

                if ($remember) {
                    create_remember_me($pdo, (int)$user['id']);
                }

                $stmt = $pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);

                redirect('dashboard.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Registracija</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body>
    <div id="authContainer" class="container-fluid signin-content <?= $loginError ? 'show-login' : '' ?>">
        <div class="row h-100">

            <div class="col-md-6 signin-bg-container warm-bg" id="warmBg">
                <svg width="80%" height="80%" xmlns="http://www.w3.org/2000/svg">
                    <image width="100%" height="100%" href="media/svg/roo-bg.svg" />
                    <image x="15%" y="20%" width="70%" height="70%" href="media/svg/roo-happy.svg"/>
                </svg>
            </div>

            <div class="col-md-6 signin-bg-container cool-bg" id="coolBg">
                <svg width="70%" height="70%" xmlns="http://www.w3.org/2000/svg">
                    <image width="100%" height="100%" href="media/svg/roo-bg2.svg" />
                    <image x="10%" y="15%" width="82.5%" height="82.5%" href="media/svg/roo-happy.svg"/>
                </svg>
            </div>

            <div id="signinForm" class="col-md-6 signin-form-container">
                <div class="form-content">
                    <h1>Kreni s Roo na put!</h1>
                    <h3>Kreni planirati svoje sljedeće putovanje.</h3>

                    <?php if ($registerError): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
                    <?php endif; ?>

                    <form id="registerForm" action="index.php" method="POST" novalidate>
                        <input type="hidden" name="form_type" value="register">

                        <div class="name-row">
                            <div class="field-group">
                                <input type="text" id="ime" name="ime" placeholder=" " autocomplete="given-name" value="<?= htmlspecialchars($registerData['ime']) ?>">
                                <label for="ime">Ime</label>
                            </div>
                            <div class="field-group">
                                <input type="text" id="prezime" name="prezime" placeholder=" " autocomplete="family-name" value="<?= htmlspecialchars($registerData['prezime']) ?>">
                                <label for="prezime">Prezime</label>
                            </div>
                        </div>

                        <div class="field-group">
                            <input type="email" id="mail" name="mail" placeholder=" " autocomplete="email" value="<?= htmlspecialchars($registerData['mail']) ?>">
                            <label for="mail">E-pošta</label>
                        </div>

                        <div class="field-group">
                            <input type="password" id="lozinka" name="lozinka" placeholder=" " autocomplete="new-password">
                            <label for="lozinka">Lozinka</label>
                            <div class="strength-bar-wrap">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                        </div>

                        <div class="field-group">
                            <input type="password" id="potvrda" name="potvrda" placeholder=" " autocomplete="new-password">
                            <label for="potvrda">Potvrda lozinke</label>
                            <span class="field-error-msg" id="potvrdaError">Lozinke se ne podudaraju.</span>
                        </div>

                        <div class="remember-row">
                            <label class="remember-label">
                                <input type="checkbox" name="remember_me_signup" id="remember_me_signup">
                                <span>Zapamti me</span>
                            </label>
                        </div>

                        <button type="submit" class="submit-btn">Započni avanturu</button>
                    </form>

                    <p class="switch-text">Već imaš račun? <a href="#" id="showLogin">Prijavi se</a></p>
                </div>
            </div>

            <div id="loginForm" class="col-md-6 login-form-container">
                <div class="form-content">
                    <h1>Roo te poželio nazad!</h1>
                    <h3>Nastavi tamo gdje si stao</h3>

                    <?php if ($loginError): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
                        <p class="switch-text">
                            <a href="resend-verification.php">Pošalji ponovno verifikaciju</a><br>
                            <a href="forgot-password.php">Zaboravljena lozinka?</a>
                        </p>
                    <?php else: ?>
                        <p class="switch-text"><a href="forgot-password.php">Zaboravljena lozinka?</a></p>
                    <?php endif; ?>

                    <form id="loginFormEl" action="index.php" method="POST" novalidate>
                        <input type="hidden" name="form_type" value="login">

                        <div class="field-group">
                            <input type="email" id="login-mail" name="mail" placeholder=" " autocomplete="email" value="<?= htmlspecialchars($loginData['mail']) ?>">
                            <label for="login-mail">E-pošta</label>
                        </div>

                        <div class="field-group">
                            <input type="password" id="login-lozinka" name="lozinka" placeholder=" " autocomplete="current-password">
                            <label for="login-lozinka">Lozinka</label>
                        </div>

                        <div class="remember-row">
                            <label class="remember-label">
                                <input type="checkbox" name="remember_me" id="remember_me">
                                <span>Zapamti me</span>
                            </label>
                        </div>

                        <button type="submit" class="submit-btn">Nastavi avanturu</button>
                    </form>

                    <p class="switch-text">Nemaš račun? <a href="#" id="showSignin">Registriraj se</a></p>
                </div>
            </div>

        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>