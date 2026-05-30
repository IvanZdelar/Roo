<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$adventureId = (int)($_GET['id'] ?? 0);

if ($adventureId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT a.*, u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
    FROM adventures a
    LEFT JOIN users u ON a.user_id = u.id
    WHERE a.id = ?
");
$stmt->execute([$adventureId]);
$adventure = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$adventure) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT tag_type, tag_value
    FROM adventure_tags
    WHERE adventure_id = ?
");
$stmt->execute([$adventureId]);
$tagsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tags = [];

foreach ($tagsRaw as $tag) {
    $tags[$tag['tag_type']][] = $tag['tag_value'];
}

$creatorName = trim($adventure['korisnicko_ime'] ?? '');

if ($creatorName === '') {
    $creatorName = trim(($adventure['ime'] ?? '') . ' ' . ($adventure['prezime'] ?? ''));
}

if ($creatorName === '') {
    $creatorName = 'Korisnik';
}

$profileImage = 'media/svg/roo-happy.svg';

if (!empty($adventure['profilna_slika'])) {
    $cleanPath = ltrim($adventure['profilna_slika'], '/');

    if (file_exists(__DIR__ . '/' . $cleanPath)) {
        $profileImage = $cleanPath;
    }
}

$locations = $tags['location_days'] ?? [];
$activities = $tags['activity'] ?? [];
$locationActivities = $tags['location_activity'] ?? [];
$stayOptions = $tags['stay_option'] ?? [];
$startDate = $tags['start_date'][0] ?? '';
$endDate = $tags['end_date'][0] ?? '';
$buddySlots = $tags['buddy_slots'][0] ?? null;
$isBuddyOpen = isset($tags['travel_buddy_open']);

$isOwner = (int)$adventure['user_id'] === (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_adventure'])) {

    if ($isOwner && $adventure['status'] !== 'completed') {

        $stmt = $pdo->prepare("
            UPDATE adventures
            SET status = 'completed'
            WHERE id = ?
        ");

        $stmt->execute([$adventureId]);

        header("Location: adventure-details.php?id=" . $adventureId);
        exit;
    }
}

require_once 'notifications_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_adventure'])) {
    $adventure_owner_id = (int)$adventure['user_id'];
    $requester_id       = (int)$_SESSION['user_id'];

    create_notification($pdo, $adventure_owner_id, 'buddy_request', $requester_id, $adventureId);

    header('Location: adventure-details.php?id=' . $adventureId);
    exit;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($adventure['naziv']) ?> - Roo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/hamburger.css">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>

<body class="adventure-details-body">

<?php include 'nav.php'; ?>

<main class="adventure-details-page">

    <section class="adventure-details-hero">
        <div>
            <p class="details-small-title">ROO AVANTURA</p>
            <h1><?= htmlspecialchars($adventure['naziv']) ?></h1>
            <p class="details-route"><?= htmlspecialchars($adventure['destination']) ?></p>

            <div class="details-pill-row">
                <span><?= htmlspecialchars($adventure['trip_type'] ?? 'Putovanje') ?></span>
                <span><?= htmlspecialchars($adventure['budget_per_day']) ?>€ / dan</span>
                <span><?= htmlspecialchars($adventure['travel_with']) ?></span>
                <span><?= htmlspecialchars($adventure['transport_mode']) ?></span>
            </div>
        </div>

        <img src="media/svg/roo-happy.svg" class="details-hero-roo" alt="Roo">
    </section>

    <section class="details-main-grid">

        <div class="details-left">

            <div class="details-card">
                <h2>📅 Datumi</h2>
                <p>
                    <strong><?= htmlspecialchars($startDate ?: 'Nije odabrano') ?></strong>
                    →
                    <strong><?= htmlspecialchars($endDate ?: 'Nije odabrano') ?></strong>
                </p>
            </div>

            <div class="details-card">
                <h2>🧭 Plan lokacija</h2>

                <?php if ($locations): ?>
                    <div class="details-timeline">
                        <?php foreach ($locations as $loc): ?>
                            <?php
                                [$city, $days] = array_pad(explode('|', $loc), 2, '');
                            ?>
                            <div class="details-timeline-item">
                                <strong><?= htmlspecialchars($city) ?></strong>
                                <span><?= htmlspecialchars($days) ?> dana</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nema unesenih lokacija.</p>
                <?php endif; ?>
            </div>

            <div class="details-card">
                <h2>🎯 Aktivnosti</h2>

                <?php if ($activities): ?>
                    <div class="details-chip-wrap">
                        <?php foreach ($activities as $activity): ?>
                            <span class="details-chip"><?= htmlspecialchars($activity) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nema odabranih aktivnosti.</p>
                <?php endif; ?>
            </div>

            <div class="details-card">
                <h2>📍 Aktivnosti po lokaciji</h2>

                <?php if ($locationActivities): ?>
                    <?php foreach ($locationActivities as $item): ?>
                        <?php
                            [$city, $activity] = array_pad(explode('|', $item), 2, '');
                        ?>
                        <div class="details-location-activity">
                            <strong><?= htmlspecialchars($city) ?></strong>
                            <span><?= htmlspecialchars($activity) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nema posebnih aktivnosti po lokaciji.</p>
                <?php endif; ?>
            </div>

            <div class="details-card">
                <h2>🏨 Smještaj</h2>

                <?php if ($stayOptions): ?>
                    <?php foreach ($stayOptions as $stay): ?>
                        <?php
                            $decoded = json_decode($stay, true);
                        ?>

                        <?php if (is_array($decoded)): ?>
                            <?php foreach ($decoded as $city => $value): ?>
                                <div class="details-location-activity">
                                    <strong><?= htmlspecialchars($city) ?></strong>
                                    <span><?= htmlspecialchars(str_replace('|', ': ', $value)) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?= htmlspecialchars(str_replace('|', ': ', $stay)) ?></p>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Smještaj nije odabran.</p>
                <?php endif; ?>
            </div>

        </div>

        <aside class="details-right">

            <div class="details-creator-card">
                <img src="<?= htmlspecialchars($profileImage) ?>" alt="">
                <h3><?= htmlspecialchars($creatorName) ?></h3>
                <p>Organizator avanture</p>
            </div>

            <div class="details-card">
                <h2>👥 Travel Buddy</h2>

                <?php if ($isBuddyOpen): ?>
                    <p>Ova avantura prima nove putnike.</p>
                    <p>
                        Slobodna mjesta:
                        <strong><?= htmlspecialchars($buddySlots ?? 'Nije navedeno') ?></strong>
                    </p>

                    <?php if ((int)$adventure['user_id'] !== (int)$_SESSION['user_id']): ?>
                        <form method="POST">
                            <button type="submit" name="join_adventure" class="details-join-btn">
                                Želim se pridružiti
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="details-owner-note">Ovo je tvoja avantura.</p>
                    <?php endif; ?>

                <?php else: ?>
                    <p>Ova avantura trenutno nije otvorena za druge korisnike.</p>
                <?php endif; ?>
            </div>
            <?php if ((int)$adventure['user_id'] === (int)$_SESSION['user_id']): ?>
                <?php if (($adventure['status'] ?? 'active') !== 'completed'): ?>
                    <div class="details-complete-box">
                        <a
                            href="complete-adventure.php?id=<?= (int)$adventure['id'] ?>"
                            class="details-complete-btn"
                        >
                            ✅ Označi avanturu završenom
                        </a>
                    </div>
                <?php else: ?>
                    <div class="details-complete-box">
                        <button class="details-complete-btn completed" disabled>
                            ✔ Avantura završena
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </aside>

    </section>

</main>

<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>