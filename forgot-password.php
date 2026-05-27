<?php
session_start();
$pdo = require 'db.php';
require_once 'auth_helpers.php';
require_once 'mail_helpers.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = normalize_email($_POST['mail'] ?? '');

    if ($email === '') {
        $error = 'Unesi e-poštu.';
    } else {
        $user = find_user_by_email($pdo, $email);

        if ($user) {
            $token = create_reset_token($pdo, (int)$user['id']);
            send_reset_email($user['email'], $user['ime'], $token);
        }

        $message = 'Ako račun postoji, poslali smo link za reset lozinke.';
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Zaboravljena lozinka</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="auth-utility-page">
    <div class="auth-utility-card">
        <div class="auth-utility-header">
            <h1>Zaboravljena lozinka?</h1>
            <p>Unesi svoju e-poštu i poslat ćemo ti link za postavljanje nove lozinke.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-utility-form">
            <div class="field-group">
                <input type="email" id="mail" name="mail" placeholder=" " autocomplete="email">
                <label for="mail">E-pošta</label>
            </div>

            <button type="submit" class="submit-btn">Pošalji link</button>
        </form>

        <div class="auth-utility-actions">
            <p class="switch-text"><a href="index.php">Povratak na prijavu</a></p>
        </div>
    </div>
</body>
</html>