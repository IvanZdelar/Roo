<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';
require_once 'mascot_helper.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_mascot'])) {
    $hat       = $_POST['hat']       ?? null;
    $shirt     = $_POST['shirt']     ?? null;
    $hand_item = $_POST['hand_item'] ?? null;

    save_user_mascot($pdo, $user_id, $hat ?: null, $shirt ?: null, $hand_item ?: null);
    header('Location: profil.php');
    exit;
}

$mascot  = get_user_mascot($pdo, $user_id);
$catalog = get_items_catalog();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Uredi maskotu</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/hamburger.css">
    <link rel="stylesheet" href="css/mascot.css">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body>
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span></div>
</div>

<?php include 'nav.php'; ?>

<main class="mascot-page">
    <div class="mascot-page-header">
        <a href="profil.php" class="notif-back-btn transition-link">
            <img src="media/svg/prevBtn.svg" alt="Nazad">
        </a>
        <h1>UREDI ROO-A</h1>
    </div>

    <div class="mascot-editor">

        <!-- Preview -->
        <div class="mascot-preview-box">
            <div class="mascot-preview" id="mascotPreview">
                <!-- Base maskota -->
                <img src="media/svg/maskot.svg" class="mascot-layer" id="layerBase">
                <!-- Shirt layer -->
                <img src="" class="mascot-layer" id="layerShirt" style="display:none">
                <!-- Hat layer -->
                <img src="" class="mascot-layer" id="layerHat" style="display:none">
                <!-- Hand item layer -->
                <img src="" class="mascot-layer" id="layerHand" style="display:none">
            </div>
            <p class="mascot-preview-label">Tvoj Roo</p>
        </div>

        <!-- Items selector -->
        <form method="POST" class="mascot-form" id="mascotForm">

            <!-- Šeširi -->
            <div class="mascot-category">
                <h3>🎩 Šeširi</h3>
                <div class="mascot-items-grid">
                    <div class="mascot-item <?= $mascot['hat'] === null ? 'selected' : '' ?>"
                         data-slot="hat" data-value="" data-layer="layerHat" data-file="">
                        <div class="mascot-item-empty">✕</div>
                        <span>Ništa</span>
                    </div>
                    <?php foreach ($catalog['hats'] as $item): ?>
                    <div class="mascot-item <?= $mascot['hat'] === $item['id'] ? 'selected' : '' ?>"
                         data-slot="hat"
                         data-value="<?= $item['id'] ?>"
                         data-layer="layerHat"
                         data-file="media/svg/mascot/<?= $item['file'] ?>">
                        <img src="media/svg/mascot/<?= $item['file'] ?>" alt="">
                        <span><?= htmlspecialchars($item['name']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="hat" id="inputHat" value="<?= htmlspecialchars($mascot['hat'] ?? '') ?>">
            </div>

            <!-- Majice -->
            <div class="mascot-category">
                <h3>👕 Majice</h3>
                <div class="mascot-items-grid">
                    <div class="mascot-item <?= $mascot['shirt'] === null ? 'selected' : '' ?>"
                         data-slot="shirt" data-value="" data-layer="layerShirt" data-file="">
                        <div class="mascot-item-empty">✕</div>
                        <span>Ništa</span>
                    </div>
                    <?php foreach ($catalog['shirts'] as $item): ?>
                    <div class="mascot-item <?= $mascot['shirt'] === $item['id'] ? 'selected' : '' ?>"
                         data-slot="shirt"
                         data-value="<?= $item['id'] ?>"
                         data-layer="layerShirt"
                         data-file="media/svg/mascot/<?= $item['file'] ?>">
                        <img src="media/svg/mascot/<?= $item['file'] ?>" alt="">
                        <span><?= htmlspecialchars($item['name']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="shirt" id="inputShirt" value="<?= htmlspecialchars($mascot['shirt'] ?? '') ?>">
            </div>

            <!-- U ruci -->
            <div class="mascot-category">
                <h3>✋ U ruci</h3>
                <div class="mascot-items-grid">
                    <div class="mascot-item <?= $mascot['hand_item'] === null ? 'selected' : '' ?>"
                         data-slot="hand_item" data-value="" data-layer="layerHand" data-file="">
                        <div class="mascot-item-empty">✕</div>
                        <span>Ništa</span>
                    </div>
                    <?php foreach ($catalog['hand_items'] as $item): ?>
                    <div class="mascot-item <?= $mascot['hand_item'] === $item['id'] ? 'selected' : '' ?>"
                         data-slot="hand_item"
                         data-value="<?= $item['id'] ?>"
                         data-layer="layerHand"
                         data-file="media/svg/mascot/<?= $item['file'] ?>">
                        <img src="media/svg/mascot/<?= $item['file'] ?>" alt="">
                        <span><?= htmlspecialchars($item['name']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="hand_item" id="inputHandItem" value="<?= htmlspecialchars($mascot['hand_item'] ?? '') ?>">
            </div>

            <button type="submit" name="save_mascot" class="mascot-save-btn">
                💾 Spremi Roo-a
            </button>
        </form>
    </div>
</main>

<script src="js/mascot.js"></script>
<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>