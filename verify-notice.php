<?php
session_start();

if (empty($_SESSION['pending_verification_email'])) {
    header('Location: index.php');
    exit;
}

$email = $_SESSION['pending_verification_email'];
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Potvrdi e-poštu</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="auth-utility-page">
    <div class="auth-utility-card">
        <div class="auth-utility-header">
            <h1>Još samo jedan korak ✉️</h1>
            <p>Potvrdi svoju e-poštu i kreni dalje s Roo.</p>
        </div>

        <div class="auth-utility-note">
            <p>Poslali smo verifikacijski link na:</p>
            <p><strong><?= htmlspecialchars($email) ?></strong></p>
            <p>Provjeri svoju e-poštu i klikni na link za potvrdu računa.</p>
            <p>Nakon potvrde ćemo te automatski prijaviti i preusmjeriti na upoznavanje.</p>
        </div>

        <svg class="auth-utility-illustration" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
            <image x="0%" y="0%" width="100%" height="100%" href="media/svg/roo-happy.svg"/>
        </svg>

        <div class="auth-utility-actions">
            <p class="switch-text">
                <a href="resend-verification.php">Pošalji ponovno verifikaciju</a><br>
                <a href="index.php">Povratak na prijavu</a>
            </p>
        </div>
    </div>
</body>
</html>