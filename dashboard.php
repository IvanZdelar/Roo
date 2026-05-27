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

// dohvati korisnika
$stmt = $pdo->prepare("SELECT korisnicko_ime, ime, prezime, profilna_slika, title FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$stmt = $pdo->prepare("
    SELECT a.*
    FROM adventures a
    INNER JOIN adventure_tags t 
        ON a.id = t.adventure_id
    WHERE t.tag_type = 'travel_buddy_open'
    AND t.tag_value = '1'
    AND a.status = 'active'
    ORDER BY a.id DESC
    LIMIT 4
");
$stmt->execute();
$buddyTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);
$hasBuddyTrips = count($buddyTrips) > 0;

// fallback vrijednosti
$korisnicko_ime = trim($user['korisnicko_ime'] ?? '');
$ime = $user['ime'] ?? '';
$prezime = $user['prezime'] ?? '';

// fallback logika
if (!empty($korisnicko_ime)) {
    $display_name = $korisnicko_ime;
} else {
    $display_name = trim($ime . ' ' . $prezime);
}

// ako ni to ne postoji
if (empty($display_name)) {
    $display_name = 'Korisnik';
}

$profilna_slika = $user['profilna_slika'] ?? null;
$status_nadimak = $user['title'] ?? 'Dnevni sanjar';

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(distance_km), 0)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$user_id]);

$totalKilometers = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    DELETE FROM adventure_tags
    WHERE adventure_id = ?
    AND tag_type = 'travel_buddy_open'
");

$stmt = $pdo->prepare("
    SELECT 
        u.id,
        u.korisnicko_ime,
        u.ime,
        u.prezime,
        u.profilna_slika,
        u.title,

        COALESCE(SUM(a.distance_km), 0) AS total_km

    FROM users u

    LEFT JOIN adventures a
        ON u.id = a.user_id
        AND a.status = 'completed'

    GROUP BY u.id

    ORDER BY total_km DESC

    LIMIT 5
");

$stmt->execute();

$topTravelers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Početna</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/hamburger.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="homepage-body">
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">
        Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
    </div>
</div>
   <?php
    include 'nav.php';
    ?>
    <!-- Hero -->
    <section class="hero-home reveal-up">
        <div class="hero-content-home">
            <h1 class="hero-hello">Pozdrav, <?= htmlspecialchars($_SESSION['user_name'] ?? 'korisniče') ?>!</h1>
            <p class="hero-subtitle-home">JESTE LI SPREMNI ZA</p>
            <h1 class="hero-title-home">NOVU AVANTURU</h1>
            <a href="create-adventure.php" class="hero-btn-home transition-link" id="heroStartBtn">KRENI</a>
        </div>

        <div class="hero-mascot-placeholder">
            <img src="media/svg/roo-happy.svg" alt="">
        </div>

        <div class="hero-side-text"><a>OSMISLI</a><br>PUTOVANJE</div>
    </section>

    <!-- Middle content -->
    <section class="home-main-grid">
        <div class="home-left">
            <h2 class="section-title-gold reveal-up">Travel Buddy finder</h2>

           <div class="travel-cards-grid">
                <?php if ($hasBuddyTrips): ?>

                    <?php foreach ($buddyTrips as $trip): ?>
                        <a href="adventure-details.php?id=<?= (int)$trip['id'] ?>" class="travel-card">
                            <?php if (!empty($trip['adventure_image'])): ?>
                                <img class="travel-card-img" src="<?= htmlspecialchars($trip['adventure_image']) ?>" alt="">
                            <?php else: ?>
                                <div class="travel-card-img placeholder-img">IMG</div>
                            <?php endif; ?>
                            <div class="travel-card-body">
                                <h3><?= htmlspecialchars($trip['naziv']) ?></h3>
                                <p>
                                    <?= htmlspecialchars($trip['destination']) ?><br>
                                    Traži društvo za putovanje.
                                </p>
                                <div class="travel-card-tags">
                                    <?= htmlspecialchars($trip['trip_type'] ?? '') ?>
                                </div>
                                <div class="travel-card-footer">
                                    <?= htmlspecialchars($trip['budget_per_day'] ?? '') ?>€ / dan
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="travel-empty-state">
                        <div class="travel-empty-icon">🧭</div>
                        <h3>Nitko još ne traži društvo</h3>
                        <p>Budi prvi koji će otvoriti avanturu i pronaći ekipu!</p>
                        <a href="create-adventure.php" class="travel-empty-btn transition-link">
                            ➕ Kreiraj avanturu
                        </a>
                    </div>

                <?php endif; ?>
            </div>

            <a href="putovanja.php" class="discover-more-link transition-link">OTKRIJ VIŠE>>></a>
        </div>

        <aside class="home-right">
            <div class="top-travelers-box reveal-right">
                <h2 class="section-title-gold small">Top travelers</h2>

                <?php foreach ($topTravelers as $index => $traveler): ?>
                <?php
                    $travelerName = trim(
                        $traveler['korisnicko_ime']
                        ?: ($traveler['ime'] . ' ' . $traveler['prezime'])
                    );
                    if ($travelerName === '') {
                        $travelerName = 'Korisnik';
                    }
                    $travelerImage = 'media/svg/roo-happy.svg';
                    if (!empty($traveler['profilna_slika'])) {
                        $cleanPath = ltrim($traveler['profilna_slika'], '/');
                        if (file_exists(__DIR__ . '/' . $cleanPath)) {
                            $travelerImage = $cleanPath;
                        }
                    }
                ?>
                <div class="traveler-row">
                    <div class="traveler-avatar">
                        <img
                            src="<?= htmlspecialchars($travelerImage) ?>"
                            alt=""
                            class="traveler-avatar-img">
                    </div>
                    <div class="traveler-info">
                        <strong>
                            <?= $index + 1 ?>.
                            <?= htmlspecialchars($travelerName) ?>
                        </strong>
                        <span>
                            <?= htmlspecialchars($traveler['title'] ?? 'Putnik') ?>
                        </span>
                        <p>
                            PRIJEĐENO
                            <strong class="count-up"
                                data-target="<?= (int)$traveler['total_km'] ?>">
                                0
                            </strong>km
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="status-box reveal-right">
                <h2 class="section-title-gold small">TVOJ STATUS</h2>
                <div class="status-avatar">
                    <?php
                    $profileImageSrc = null;
                    if (!empty($profilna_slika)) {
                        $cleanPath = ltrim($profilna_slika, '/');
                        $absolutePath = __DIR__ . '/' . $cleanPath;
                        if (file_exists($absolutePath)) {
                            $profileImageSrc = $cleanPath;
                        }
                    }
                    ?>
                    <?php if ($profileImageSrc): ?>
                        <img src="<?php echo htmlspecialchars($profileImageSrc); ?>" alt="Profilna slika">
                    <?php else: ?>
                        <img src="media/svg/roo-happy.svg" alt="Default">
                    <?php endif; ?>
                </div>

                <h3><?php echo htmlspecialchars($display_name); ?></h3>
                <div class="status-main">
                        <?= htmlspecialchars($status_nadimak) ?>
                    </div>
                <div class="status-distance">PRIJEĐENO <strong class="count-up" data-target="<?= $totalKilometers ?>">0</strong>km</div>
            </div>
        </aside>
    </section>

    <!-- Bottom banner -->
    <section class="bottom-banner-home reveal-righ">
        <div class="bottom-banner-bg"></div>
        <h2>NE BOJ SE, SVIJET TE ČEKA</h2>
        <img src="media/svg/roo-wink.svg" alt="" class="bottom-banner-mascot">
    </section>
    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>
</body>
</html>