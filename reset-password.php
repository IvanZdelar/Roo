<?php
session_start();
$pdo = require 'db.php';
require_once 'auth_helpers.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? ($_POST['token'] ?? '');

$user = $token ? validate_reset_token($pdo, $token) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lozinka = $_POST['lozinka'] ?? '';
    $potvrda = $_POST['potvrda'] ?? '';

    if (!$user) {
        $error = 'Reset link nije valjan ili je istekao.';
    } elseif ($lozinka === '' || $potvrda === '') {
        $error = 'Unesi novu lozinku i potvrdu.';
    } elseif (strlen($lozinka) < 8) {
        $error = 'Lozinka mora imati barem 8 znakova.';
    } elseif ($lozinka !== $potvrda) {
        $error = 'Lozinke se ne podudaraju.';
    } else {
        complete_password_reset($pdo, (int)$user['id'], $lozinka);
        $success = 'Lozinka je promijenjena. Sada se možeš prijaviti.';
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Reset lozinke</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="auth-utility-page">
    <div class="auth-utility-card">
        <div class="auth-utility-header">
            <h1>Postavi novu lozinku</h1>
            <p>Odaberi novu lozinku i vrati se planiranju svoje sljedeće avanture.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" class="auth-utility-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="field-group">
                    <input type="password" id="lozinka" name="lozinka" placeholder=" " autocomplete="new-password">
                    <label for="lozinka">Nova lozinka</label>
                </div>

                <div class="field-group">
                    <input type="password" id="potvrda" name="potvrda" placeholder=" " autocomplete="new-password">
                    <label for="potvrda">Potvrda lozinke</label>
                </div>

                <button type="submit" class="submit-btn">Spremi novu lozinku</button>
            </form>
        <?php endif; ?>

        <div class="auth-utility-actions">
            <p class="switch-text"><a href="index.php">Povratak na prijavu</a></p>
        </div>
    </div>
</body>
</html>