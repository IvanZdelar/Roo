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

function get_svg_inner(string $path): string
{
    $content = file_get_contents($path);
    // Makni sve do prvog > nakon <svg
    $content = preg_replace('/<svg[^>]*>/', '', $content);
    // Makni </svg>
    $content = str_replace('</svg>', '', $content);
    return $content;
}
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
<div class="customer-bg" id="parallaxBg"></div>
<div class="profile-bg" id="parallaxBg"></div>
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span></div>
</div>

<?php include 'nav.php'; ?>

<main class="mascot-page">
    <div class="mascot-editor">

        <!-- Lijevo: preview -->
        <div class="mascot-preview-box">
            <div class="mascot-preview-frame">
                <!-- Pozadina -->
                <div class="mascot-bg-layer" id="mascotBg"></div>
                <!-- SVG maskota s layerima -->
                <svg id="mascotSVG" viewBox="0 0 127 161" 
                     xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="baseRoo">
                        <?= get_svg_inner(__DIR__ . '/media/svg/roo.svg') ?>
                    </g>
                    <image id="layerShirt" href="" style="display:none"/>
                    <image id="layerHat"   href="" style="display:none"/>
                    <image id="layerHand"  href="" style="display:none"/>
                </svg>
            </div>
        </div>

        <!-- Desno: selector -->
        <div class="mascot-selector">

            <!-- Tab navigacija -->
            <div class="mascot-tabs">
                <button class="mascot-tab" data-category="emotion">EMOCIJE</button>
                <button class="mascot-tab active" data-category="hats">KAPE</button>
                <button class="mascot-tab" data-category="shirts">MAJICE</button>
                <button class="mascot-tab" data-category="hand_items">REKVIZITI</button>
            </div>

            <!-- Grids po kategoriji -->
            <form method="POST" id="mascotForm">
                <?php foreach (['hats', 'shirts', 'hand_items'] as $category): ?>
                <div class="mascot-grid" id="grid-<?= $category ?>" 
                     style="<?= $category !== 'hats' ? 'display:none' : '' ?>">
                    <?php
                    $slot_map = ['hats' => 'hat', 'shirts' => 'shirt', 'hand_items' => 'hand_item'];
                    $slot = $slot_map[$category];
                    ?>
                    <!-- Ništa opcija -->
                    <div class="mascot-item-card <?= empty($mascot[$slot]) ? 'selected' : '' ?>"
                         data-slot="<?= $slot ?>"
                         data-value=""
                         data-layer="layer<?= ucfirst($slot === 'hand_item' ? 'Hand' : ucfirst(rtrim($slot, 's'))) ?>"
                         data-file=""
                         data-x="" data-y="" data-w="" data-h="">
                        <div class="mascot-empty-icon">✕</div>
                    </div>

                    <?php foreach ($catalog[$category] as $item): 
                        $layerId = match($slot) {
                            'hat'       => 'layerHat',
                            'shirt'     => 'layerShirt',
                            'hand_item' => 'layerHand',
                        };
                    ?>
                    <div class="mascot-item-card <?= ($mascot[$slot] ?? '') === $item['id'] ? 'selected' : '' ?>"
                    data-slot="<?= $slot ?>"
                    data-value="<?= $item['id'] ?>"
                    data-layer="<?= $layerId ?>"
                    data-file="media/svg/mascot/<?= $item['file'] ?>">
                    <img src="media/svg/mascot/<?= $item['file'] ?>" alt="">
                        <?php if (!empty($item['locked'])): ?>
                            <div class="mascot-lock">🔒</div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>

                <input type="hidden" name="hat"       id="inputHat"      value="<?= htmlspecialchars($mascot['hat'] ?? '') ?>">
                <input type="hidden" name="shirt"     id="inputShirt"    value="<?= htmlspecialchars($mascot['shirt'] ?? '') ?>">
                <input type="hidden" name="hand_item" id="inputHandItem" value="<?= htmlspecialchars($mascot['hand_item'] ?? '') ?>">

                <button type="submit" name="save_mascot" class="mascot-save-btn">
                    💾 Spremi
                </button>
            </form>
        </div>
    </div>
</main>

<script src="js/mascot.js"></script>
<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>