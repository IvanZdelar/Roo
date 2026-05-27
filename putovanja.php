<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ── Aktivna putovanja: avanture otvorene za pridruživanje (status = active, travel_buddy_open = 1)
$stmtActive = $pdo->prepare("
    SELECT a.id, a.naziv, a.destination, a.trip_type, a.budget_per_day,
           a.adventure_image, a.travel_with,
           (SELECT t.tag_value FROM adventure_tags t
            WHERE t.adventure_id = a.id AND t.tag_type = 'start_date' LIMIT 1) AS start_date,
           (SELECT t.tag_value FROM adventure_tags t
            WHERE t.adventure_id = a.id AND t.tag_type = 'end_date' LIMIT 1) AS end_date,
           (SELECT t.tag_value FROM adventure_tags t
            WHERE t.adventure_id = a.id AND t.tag_type = 'buddy_slots' LIMIT 1) AS buddy_slots,
           u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
    FROM adventures a
    INNER JOIN adventure_tags tb
        ON a.id = tb.adventure_id
        AND tb.tag_type = 'travel_buddy_open'
        AND tb.tag_value = '1'
    LEFT JOIN users u ON a.user_id = u.id
    WHERE a.status = 'active'
    ORDER BY a.created_at DESC
");
$stmtActive->execute();
$activeAdventures = $stmtActive->fetchAll(PDO::FETCH_ASSOC);

// ── Inspiracija: sve završene avanture
$stmtCompleted = $pdo->prepare("
    SELECT a.id, a.naziv, a.destination, a.trip_type, a.budget_per_day,
           a.adventure_image, a.travel_with,
           (SELECT t.tag_value FROM adventure_tags t
            WHERE t.adventure_id = a.id AND t.tag_type = 'start_date' LIMIT 1) AS start_date,
           (SELECT t.tag_value FROM adventure_tags t
            WHERE t.adventure_id = a.id AND t.tag_type = 'end_date' LIMIT 1) AS end_date,
           u.korisnicko_ime, u.ime, u.prezime
    FROM adventures a
    LEFT JOIN users u ON a.user_id = u.id
    WHERE a.status = 'completed'
    ORDER BY a.created_at DESC
");
$stmtCompleted->execute();
$completedAdventures = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);

// helper – display ime autora
function creatorName(array $row): string {
    $name = trim($row['korisnicko_ime'] ?? '');
    if ($name === '') {
        $name = trim(($row['ime'] ?? '') . ' ' . ($row['prezime'] ?? ''));
    }
    return $name !== '' ? $name : 'Korisnik';
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Putovanja</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/hamburger.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="putovanja-body">
<div class="putovanja-bg"></div>
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">
        Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
    </div>
</div>

<?php include 'nav.php'; ?>

<!-- ═══════════════════════════════════════════════
     AKTIVNA PUTOVANJA – otvorena za pridruživanje
════════════════════════════════════════════════ -->
<section class="putovanja-main">
    <h2 class="section-title-blue reveal-up">Aktivna putovanja</h2>

    <?php if (!empty($activeAdventures)): ?>
        <div class="travel-cards-grid-putovanja">
            <?php foreach ($activeAdventures as $trip):
                $imgSrc = 'media/slike/more.jpg';
                if (!empty($trip['adventure_image'])) {
                    $cleanPath = ltrim($trip['adventure_image'], '/');
                    if (file_exists(__DIR__ . '/' . $cleanPath)) {
                        $imgSrc = htmlspecialchars($cleanPath);
                    }
                }

                $dateRange = '';
                if (!empty($trip['start_date']) && !empty($trip['end_date'])) {
                    $dateRange = htmlspecialchars($trip['start_date']) . ' – ' . htmlspecialchars($trip['end_date']);
                } elseif (!empty($trip['start_date'])) {
                    $dateRange = 'Od ' . htmlspecialchars($trip['start_date']);
                }

                $slots = !empty($trip['buddy_slots']) ? (int)$trip['buddy_slots'] : 0;
            ?>
                <a href="adventure-details.php?id=<?= (int)$trip['id'] ?>" class="travel-card reveal-up">
                    <img src="<?= $imgSrc ?>" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3><?= htmlspecialchars($trip['naziv']) ?></h3>
                        <p><?= htmlspecialchars($trip['destination']) ?></p>
                        <p>
                            <?= htmlspecialchars(creatorName($trip)) ?> traži društvo za putovanje.
                            <?php if ($slots > 0): ?>
                                <br><strong><?= $slots ?> slobodna mjesta</strong>
                            <?php endif; ?>
                        </p>
                        <div class="travel-card-tags">
                            <?= htmlspecialchars($trip['trip_type'] ?? '') ?>
                        </div>
                        <div class="travel-card-footer">
                            <?php if ($dateRange): ?>
                                <?= $dateRange ?>
                            <?php endif; ?>
                            <?php if (!empty($trip['budget_per_day'])): ?>
                                · <?= (int)$trip['budget_per_day'] ?>€ / dan
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="travel-empty-state">
            <div class="travel-empty-icon">🧭</div>
            <h3>Nema aktivnih putovanja</h3>
            <p>Budi prvi koji će otvoriti avanturu i pozvati ekipu!</p>
            <a href="create-adventure.php" class="travel-empty-btn transition-link">
                ➕ Kreiraj avanturu
            </a>
        </div>
    <?php endif; ?>

    <a href="create-adventure.php" class="discover-more-link transition-link">OSMISLI SVOJE >>></a>
</section>

<!-- ═══════════════════════════════════════════════
     PRONAĐI INSPIRACIJU – završene avanture
════════════════════════════════════════════════ -->
<section class="putovanja-second">
    <h2 class="section-title-white reveal-up">Pronađi inspiraciju</h2>

    <?php if (!empty($completedAdventures)): ?>
        <div class="travel-cards-grid-putovanja">
            <?php foreach ($completedAdventures as $trip):
                $imgSrc = 'media/slike/more.jpg';
                if (!empty($trip['adventure_image'])) {
                    $cleanPath = ltrim($trip['adventure_image'], '/');
                    if (file_exists(__DIR__ . '/' . $cleanPath)) {
                        $imgSrc = htmlspecialchars($cleanPath);
                    }
                }

                $dateRange = '';
                if (!empty($trip['start_date']) && !empty($trip['end_date'])) {
                    $dateRange = htmlspecialchars($trip['start_date']) . ' – ' . htmlspecialchars($trip['end_date']);
                }
            ?>
                <a href="adventure-details.php?id=<?= (int)$trip['id'] ?>" class="travel-card reveal-up">
                    <img src="<?= $imgSrc ?>" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3><?= htmlspecialchars($trip['naziv'] ?: $trip['naziv']) ?></h3>
                        <p><?= htmlspecialchars($trip['destination']) ?></p>
                        <div class="travel-card-tags">
                            <?= htmlspecialchars($trip['trip_type'] ?? '') ?>
                        </div>
                        <div class="travel-card-footer">
                            <?php if ($dateRange): ?>
                                <?= $dateRange ?>
                            <?php endif; ?>
                            <?php if (!empty($trip['budget_per_day'])): ?>
                                · <?= (int)$trip['budget_per_day'] ?>€ / dan
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="travel-empty-state">
            <div class="travel-empty-icon">✨</div>
            <h3>Još nema završenih avantura</h3>
            <p>Završena putovanja pojavit će se ovdje kao inspiracija za sve korisnike.</p>
        </div>
    <?php endif; ?>

    <a href="#" class="discover-more-link discover-more-link-putovanja discover-more-link-nar">OTKRIJ VIŠE >>></a>
</section>

<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>