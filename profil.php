<?php
session_start();
require_once 'db.php';
require_once 'auth_helpers.php';
require_once 'bootstrap.php';


if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM adventures
    WHERE user_id = ?
    AND status = 'active'
    ORDER BY created_at DESC
");

$stmt->execute([$user_id]);

$activeAdventures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT *
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
    ORDER BY created_at DESC
");

$stmt->execute([$user_id]);

$completedAdventures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$user_id]);

$totalCompletedTrips = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(distance_km), 0)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$user_id]);

$totalKilometers = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT id, ime, prezime, korisnicko_ime, bio, profilna_slika, title
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$user_id]);

$totalAdventures = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM friendships
    WHERE user_one = ?
    OR user_two = ?
");

$stmt->execute([$user_id, $user_id]);

$totalFriends = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM adventure_participants
    WHERE user_id = ?
");

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(distance_km), 0)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$user_id]);

$totalKm = $stmt->fetchColumn();

$stmt->execute([$user_id]);

$totalParticipants = $stmt->fetchColumn();

if (!$user) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT interest_name
    FROM user_interests
    WHERE user_id = ?
    ORDER BY id ASC
");
$stmt->execute([$user_id]);
$interests = $stmt->fetchAll(PDO::FETCH_COLUMN);

$korisnicko_ime = trim($user['korisnicko_ime'] ?? '');
$ime = trim($user['ime'] ?? '');
$prezime = trim($user['prezime'] ?? '');
$bio = trim($user['bio'] ?? '');
$profilna_slika = $user['profilna_slika'] ?? '';

if ($korisnicko_ime !== '') {
    $display_name = $korisnicko_ime;
} else {
    $display_name = trim($ime . ' ' . $prezime);
}

if ($display_name === '') {
    $display_name = 'Korisnik';
}

$profileImageSrc = null;
if (!empty($profilna_slika)) {
    $cleanPath = ltrim($profilna_slika, '/');
    $absolutePath = __DIR__ . '/' . $cleanPath;

    if (file_exists($absolutePath)) {
        $profileImageSrc = $cleanPath;
    }
}

$status_nadimak = $user['title'] ?? 'Dnevni sanjar';

$interest_text = !empty($interests) ? implode(', ', $interests) : 'Još nema odabranih interesa.';
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Profil</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/hamburger.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="profile-page-body">
    <div class="profile-bg" id="parallaxBg"></div>
    <div class="page-transition" id="pageTransition">
        <img src="media/svg/roo-happy.svg" alt="Roo loading">
        <div class="page-transition-text">
            Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
        </div>
    </div>

    <?php
    include 'nav.php';
    ?>

        <main class="profile-page-wrap">
            <h1>
                MOJI PODACI
            </h1>
            <section class="profile-top-grid reveal-up">
                <div class="profile-left-column">
                    <div class="profile-stats-box">
                        <div class="profile-stat-card">
                            <img class="profile-stat-icon" src="media/slike/putovanja.png">
                            <div class="profile-stat-text">
                                <span>PUTOVANJA</span>
                                <strong class="count-up" data-target="<?= $totalCompletedTrips ?>">0</strong>
                            </div>
                        </div>

                        <div class="profile-stat-card">
                            <img class="profile-stat-icon" src="media/slike/km.png">
                            <div class="profile-stat-text">
                                <span>KILOMETRI</span>
                                <strong class="count-up" data-target="<?= $totalKilometers ?>">0</strong>
                            </div>
                        </div>

                        <div class="profile-stat-card">
                            <img class="profile-stat-icon" src="media/slike/prijatelji.png">
                            <div class="profile-stat-text">
                                <span>PRIJATELJI</span>
                                <strong class="count-up" data-target="<?= $totalFriends ?>">0</strong>
                            </div>
                        </div>

                        <div class="profile-stat-card">
                            <img class="profile-stat-icon" src="media/slike/pridruzivanja.png">
                            <div class="profile-stat-text">
                                <span>PRIDRUŽIVANJA</span>
                                <strong class="count-up" data-target="<?= $totalJoined ?>">0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="profile-middle-grid">
                        <div class="profile-side-badges">
                            <h3>ZNAČKE</h3>
                            <div class="profile-side-badges-grid">
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                                <div class="mini-badge empty">+</div>
                            </div>
                        </div>

                        <div class="profile-mascot-card">
                            <img class="profile-mascot-placeholder" src="media/svg/maskot.svg">
                        </div>
                    </div>
                </div>

                <div class="profile-main-box">
                    <div class="profile-main-top">
                        <div class="profile-main-avatar">
                            <?php if ($profileImageSrc): ?>
                                <img src="<?= htmlspecialchars($profileImageSrc) ?>" alt="Profilna slika">
                            <?php else: ?>
                                <img src="media/svg/roo-happy.svg" alt="Default">
                            <?php endif; ?>
                        </div>
                    </div>

                    <h1 class="profile-main-name"><?= htmlspecialchars($display_name) ?></h1>

                    <div class="profile-main-status">
                        🏅 <?= htmlspecialchars($status_nadimak) ?>
                    </div>

                    <div class="profile-info-list">
                        <div class="profile-info-row">
                            <span class="profile-info-label">USERNAME:</span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($korisnicko_ime !== '' ? $korisnicko_ime : $display_name) ?>
                            </span>
                        </div>

                        <div class="profile-info-row">
                            <span class="profile-info-label">O MENI:</span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($bio !== '' ? $bio : 'Korisnik još nije dodao opis.') ?>
                            </span>
                        </div>

                        <div class="profile-info-row">
                            <span class="profile-info-label">INTERESI:</span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($interest_text) ?>
                            </span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="kviz.php" class="profile-btn edit-btn transition-link">
                            ✏️ Uredi profil
                        </a>

                        <a href="logout.php" class="profile-btn logout-btn transition-link">
                            🚪 Logout
                        </a>
                    </div>
                </div>
            </section>
            <section class="profile-trips-box profile-active-trips reveal-up">
                <h2 class="profile-section-title">
                    Aktivne avanture
                </h2>
                <?php if (!empty($activeAdventures)): ?>
                    <?php foreach ($activeAdventures as $adventure): ?>
                        <?php
                            $days = 0;

                            if (!empty($adventure['daily_plan'])) {
                                preg_match_all('/:\s*(\d+)\s*dana/u', $adventure['daily_plan'], $matches);

                                if (!empty($matches[1])) {
                                    foreach ($matches[1] as $match) {
                                        $days += (int)$match;
                                    }
                                }
                            }

                            $durationText = $days > 0 ? $days . ' dana' : 'Nije uneseno';
                            $tags = [];

                            if (!empty($adventure['trip_type'])) {
                                $tags[] = $adventure['trip_type'];
                            }

                            if (!empty($adventure['budget_per_day'])) {
                                $tags[] = $adventure['budget_per_day'] . '€ / dan';
                            }

                            $tagText = !empty($tags) ? implode(' · ', $tags) : 'Avantura';
                        ?>

                        <div class="profile-trip-card">
                            <img class="profile-trip-map" src="media/slike/map1.jpg" alt="Mapa putovanja">

                            <div class="profile-trip-content">
                                <h3><?= htmlspecialchars($adventure['destination'] ?: $adventure['naziv']) ?></h3>

                                <p>Trajanje putovanja</p>
                                <strong><?= htmlspecialchars($durationText) ?></strong>

                                <div class="profile-trip-tags">
                                    <?= htmlspecialchars($tagText) ?>
                                </div>

                                <a href="edit-adventure.php?id=<?= (int)$adventure['id'] ?>" class="hero-btn-home small-btn transition-link">
                                    UREDI
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="profile-empty-trips">
                        <h3>Trenutno nemaš aktivnih avantura.</h3>
                        <p>Kreni planirati svoje novo putovanje s Roo.</p>
                        <a href="create-adventure.php" class="hero-btn-home small-btn transition-link">OSMISLI PUTOVANJE</a>
                    </div>
                <?php endif; ?>
            </section>

            <section class="profile-bottom-grid reveal-up">
                <section class="profile-trips-box profile-finished-trips reveal-up">
                    <h2 class="profile-section-title">
                        Završene avanture
                    </h2>
                    <?php if (!empty($completedAdventures)): ?>
                        <?php foreach ($completedAdventures as $adventure): ?>
                            <?php
                                $days = 0;
                                if (!empty($adventure['daily_plan'])) {
                                    preg_match_all(
                                        '/:\s*(\d+)\s*dana/u',
                                        $adventure['daily_plan'],
                                        $matches
                                    );
                                    if (!empty($matches[1])) {

                                        foreach ($matches[1] as $match) {
                                            $days += (int)$match;
                                        }
                                    }
                                }
                                $durationText =
                                    $days > 0
                                    ? $days . ' dana'
                                    : 'Nije uneseno';
                                $tags = [];
                                if (!empty($adventure['trip_type'])) {
                                    $tags[] = $adventure['trip_type'];
                                }
                                if (!empty($adventure['distance_km'])) {
                                    $tags[] =
                                        $adventure['distance_km'] . ' km';
                                }
                                $tagText = !empty($tags)
                                    ? implode(' · ', $tags)
                                    : 'Završena avantura';
                            ?>
                            <div class="profile-trip-card completed-trip">
                                <img
                                    class="profile-trip-map"
                                    src="media/slike/map1.jpg"
                                    alt="Mapa putovanja"
                                >
                                <div class="profile-trip-content">
                                    <h3>
                                        <?= htmlspecialchars(
                                            $adventure['destination']
                                            ?: $adventure['naziv']
                                        ) ?>
                                    </h3>
                                    <p>Završeno putovanje</p>
                                    <strong>
                                        <?= htmlspecialchars($durationText) ?>
                                    </strong>
                                    <div class="profile-trip-tags">
                                        <?= htmlspecialchars($tagText) ?>
                                    </div>
                                    <button
                                        class="hero-btn-home small-btn completed-btn"
                                        disabled
                                    >
                                        ZAVRŠENO
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="profile-empty-trips">
                            <h3>
                                Još nemaš završenih avantura.
                            </h3>
                            <p>
                                Kada završiš putovanje,
                                pojavit će se ovdje.
                            </p>
                        </div>
                    <?php endif; ?>
                </section>

                <div class="profile-friends-box">
                    <h2>PRIJATELJI</h2>

                    <div class="traveler-row">
                        <div class="traveler-avatar">IMG</div>
                        <div class="traveler-info">
                            <strong>MARKO MARIĆ</strong>
                            <span>Digitalni Nomad-pripravnik</span>
                            <p>PRIJEĐENO <strong class="count-up" data-target="859">0</strong>km</p>
                        </div>
                    </div>

                    <div class="traveler-row">
                        <div class="traveler-avatar">IMG</div>
                        <div class="traveler-info">
                            <strong>ANA TOMIĆ</strong>
                            <span>Kulturni ambasador</span>
                            <p>PRIJEĐENO <strong class="count-up" data-target="700">0</strong>km</p>
                        </div>
                    </div>

                    <div class="traveler-row">
                        <div class="traveler-avatar">IMG</div>
                        <div class="traveler-info">
                            <strong>TANJA HORVAT</strong>
                            <span>Nomad ninja</span>
                            <p>PRIJEĐENO <strong class="count-up" data-target="665">0</strong>km</p>
                        </div>
                    </div>

                    <a href="#" class="discover-more-link">VIŠE>>></a>
                </div>
            </section>
        </main>
    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>
</body>
</html>