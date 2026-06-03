<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';
require_once 'notifications_helper.php';


if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$logged_user_id  = (int)$_SESSION['user_id'];
$profile_user_id = isset($_GET['id']) ? (int)$_GET['id'] : $logged_user_id;
$is_own_profile  = $profile_user_id === $logged_user_id;

if (!$is_own_profile && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_friend_request'])) {
        create_notification($pdo, $profile_user_id, 'friend_request', $logged_user_id, null);
        header('Location: profil.php?id=' . $profile_user_id);
        exit;
    }

    if (isset($_POST['cancel_friend_request']) && $pending_notif_id) {
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ? AND from_user_id = ?");
        $stmt->execute([$pending_notif_id, $logged_user_id]);
        header('Location: profil.php?id=' . $profile_user_id);
        exit;
    }

    if (isset($_POST['remove_friend'])) {
        $stmt = $pdo->prepare("
            DELETE FROM friendships
            WHERE (user_one = ? AND user_two = ?)
               OR (user_one = ? AND user_two = ?)
        ");
        $stmt->execute([$logged_user_id, $profile_user_id, $profile_user_id, $logged_user_id]);
        header('Location: profil.php?id=' . $profile_user_id);
        exit;
    }
}

$friendship_status = null;
$pending_notif_id  = null;

if (!$is_own_profile) {
    // Postoji li već prijateljstvo?
    $stmt = $pdo->prepare("
        SELECT id FROM friendships
        WHERE (user_one = ? AND user_two = ?)
           OR (user_one = ? AND user_two = ?)
    ");
    $stmt->execute([$logged_user_id, $profile_user_id, $profile_user_id, $logged_user_id]);
    
    if ($stmt->fetch()) {
        $friendship_status = 'friends';
    } else {
        // Postoji li pending zahtjev?
        $stmt = $pdo->prepare("
            SELECT id FROM notifications
            WHERE user_id = ? AND from_user_id = ? 
            AND type = 'friend_request' AND status = 'pending'
        ");
        $stmt->execute([$profile_user_id, $logged_user_id]);
        $pending = $stmt->fetch();
        
        if ($pending) {
            $friendship_status = 'pending';
            $pending_notif_id  = $pending['id'];
        }
    }
}

$stmt = $pdo->prepare("
    SELECT *
    FROM adventures
    WHERE user_id = ?
    AND status = 'active'
    ORDER BY created_at DESC
");

$stmt->execute([$profile_user_id]);

$activeAdventures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT *
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
    ORDER BY created_at DESC
");

$stmt->execute([$profile_user_id]);

$completedAdventures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$profile_user_id]);

$totalCompletedTrips = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(distance_km), 0)
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$profile_user_id]);

$totalKilometers = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT id, ime, prezime, korisnicko_ime, bio, profilna_slika, title
    FROM users
    WHERE id = ?
");
$stmt->execute([$profile_user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM adventures
    WHERE user_id = ?
    AND status = 'completed'
");

$stmt->execute([$profile_user_id]);

$totalAdventures = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM friendships
    WHERE user_one = ?
    OR user_two = ?
");

$stmt->execute([$profile_user_id, $profile_user_id]);

$totalFriends = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM adventure_participants
    WHERE user_id = ?
");

$stmt->execute([$profile_user_id]);

$totalJoined = $stmt->fetchColumn();

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
$stmt->execute([$profile_user_id]);
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

$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.naziv        AS title,
        a.destination,
        a.trip_type,
        a.budget_per_day,
        a.adventure_image,
        a.status,
        u.korisnicko_ime,
        u.ime,
        u.prezime,
        sa.saved_at
    FROM saved_adventures sa
    INNER JOIN adventures a ON a.id = sa.adventure_id
    INNER JOIN users u      ON u.id = a.user_id
    WHERE sa.user_id = ?
    ORDER BY sa.saved_at DESC
");
$stmt->execute([$profile_user_id]);
$saved_adventures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 
        u.id,
        u.korisnicko_ime,
        u.ime,
        u.prezime,
        u.profilna_slika,
        u.title,

        COALESCE(SUM(a.distance_km), 0) AS total_km

    FROM friendships f

    INNER JOIN users u
        ON (
            (f.user_one = ? AND u.id = f.user_two)
            OR
            (f.user_two = ? AND u.id = f.user_one)
        )

    LEFT JOIN adventures a
        ON u.id = a.user_id
        AND a.status = 'completed'

    GROUP BY u.id

    ORDER BY total_km DESC
");

$stmt->execute([$profile_user_id, $profile_user_id]);

$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT total_xp FROM users WHERE id = ?");
$stmt->execute([$profile_user_id]);
$totalXP = (int)$stmt->fetchColumn();
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

    <?php include 'nav.php'; ?>

        <main class="profile-page-wrap">
            <?php if ($is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                TVOJI PODACI
            </h1>
            <?php endif; ?>
            <?php if (!$is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                BOK, JA SAM <?= htmlspecialchars($user['ime'] . ' ' . $user['prezime']) ?>!
            </h1>
            <?php endif; ?>
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

                    <?php
                    require_once 'mascot_helper.php';
                    $profile_mascot = get_user_mascot($pdo, $profile_user_id);
                    $catalog = get_items_catalog();
                    $all_items = array_merge(
                        $catalog['hats'],
                        $catalog['shirts'],
                        $catalog['hand_items']
                    );
                    ?>

                    <a href="<?= $is_own_profile ? 'customize-mascot.php' : '#' ?>"
                    class="profile-mascot-card <?= $is_own_profile ? 'transition-link' : '' ?>"
                    title="Roo">
                        <div class="mascot-preview" style="position:relative;">
                            <!-- Baza — emocija -->
                            <img src="media/svg/<?= htmlspecialchars($profile_mascot['emotion'] ?? 'roo') ?>.svg"
                                style="width:100%;height:100%;object-fit:contain;">

                            <!-- Odjevni predmeti -->
                            <?php foreach (['shirt' => null, 'hat' => null, 'hand_item' => null] as $slot => $_):
                                $equipped = $profile_mascot[$slot] ?? null;
                                if (!$equipped) continue;
                                $item = array_values(array_filter($all_items, fn($i) => $i['id'] === $equipped))[0] ?? null;
                                if (!$item) continue;
                            ?>
                                <img src="media/svg/mascot/<?= htmlspecialchars($item['file']) ?>"
                                    style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;"
                                    alt="">
                            <?php endforeach; ?>
                        </div>
                    </a>
                    </div>
                </div>

                <div class="profile-main-box reveal-right">
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
                        🏅 <?= htmlspecialchars($status_nadimak) ?> <a href="achievements.php" class="profile-btn edit-btn transition-link">🏆 Achievementi</a>
                    </div>

                    <div class="profile-info-list">
                        <div class="profile-info-row">
                            <span class="profile-info-label"><h4>O MENI:</h4></span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($bio !== '' ? $bio : 'Korisnik još nije dodao opis.') ?>
                            </span>
                        </div>

                        <div class="profile-info-row">
                            <span class="profile-info-label"><h4>INTERESI:</h4></span>
                            <span class="profile-info-value">
                                <?= htmlspecialchars($interest_text) ?>
                            </span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <?php if ($is_own_profile): ?>
                            <a href="kviz.php" class="profile-btn edit-btn transition-link">✏️ Uredi profil</a>
                            <a href="logout.php" class="profile-btn logout-btn transition-link">🚪 Logout</a>
                        <?php else: ?>
                            <form method="POST">
                                <?php if ($friendship_status === 'friends'): ?>
                                    <button type="submit" name="remove_friend" class="profile-btn friend-btn friend-remove">
                                        ✓ Prijatelji &nbsp;·&nbsp; Ukloni
                                    </button>
                                <?php elseif ($friendship_status === 'pending'): ?>
                                    <button type="submit" name="cancel_friend_request" class="profile-btn friend-btn friend-pending">
                                        ⏳ Zahtjev poslan &nbsp;·&nbsp; Otkaži
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="send_friend_request" class="profile-btn friend-btn friend-add">
                                        + Dodaj prijatelja
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <?php if ($is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                TVOJE AKTIVNE AVANTURE
            </h1>
            <?php endif; ?>
            <?php if (!$is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                MOJE AKTIVNE AVANTURE
            </h1>
            <?php endif; ?>
            <section class="profile-trips-box profile-active-trips reveal-up">
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
                            <?php
                                $adventureImage = 'media/slike/map1.jpg';
                                if (!empty($adventure['adventure_image'])) {
                                    $possiblePath = __DIR__ . '/' . $adventure['adventure_image'];
                                    if (file_exists($possiblePath)) {
                                        $adventureImage =
                                            htmlspecialchars($adventure['adventure_image']);
                                    }
                                }
                                ?>
                                <img
                                    class="profile-trip-img"
                                    src="<?= $adventureImage ?>"
                                    alt="Mapa putovanja"
                                >
                            <div class="profile-trip-content">
                                <h3><?= htmlspecialchars($adventure['naziv']) ?></h3>

                                <p>Trajanje putovanja: <strong><?= htmlspecialchars($durationText) ?></strong></p>

                                <div class="profile-trip-tags">
                                    <?= htmlspecialchars($tagText) ?>
                                </div>

                                <a href="adventure-details.php?id=<?= (int)$adventure['id'] ?>" class="detail-btn hero-btn-home small-btn transition-link">
                                    Pogledaj detalje
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="profile-empty-trips">
                        <?php if ($is_own_profile): ?>
                        <h3>Trenutno nemaš aktivnih avantura.</h3>
                        <p>Kreni planirati svoje novo putovanje s Roo.</p>
                            <a href="create-adventure.php" class="hero-btn-home small-btn transition-link">OSMISLI PUTOVANJE</a>
                        <?php endif; ?>
                        <?php if (!$is_own_profile): ?>
                        <h3><?= htmlspecialchars($user['ime']) ?> još nema aktivnih avantura.</h3>
                        <p>Kada osmisliti novo putovanje, pojavit će se ovdje.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
            <?php if ($is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                TVOJE ZAVRŠENE AVANTURE
            </h1>
            <?php endif; ?>
            <?php if (!$is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">
                MOJE ZAVRŠENE AVANTURE
            </h1>
            <?php endif; ?>
            <section class="profile-trips-box profile-finished-trips reveal-up">
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
                                <?php
                                    $adventureImage = 'media/slike/map1.jpg';
                                    if (!empty($adventure['adventure_image'])) {
                                        $possiblePath = __DIR__ . '/' . $adventure['adventure_image'];

                                        if (file_exists($possiblePath)) {
                                            $adventureImage = htmlspecialchars($adventure['adventure_image']);
                                        }
                                    }
                                ?>
                                <img
                                    class="profile-trip-img"
                                    src="<?= $adventureImage ?>"
                                    alt="Mapa putovanja"
                                >
                                <div class="profile-trip-content">
                                    <h3>
                                        <?= htmlspecialchars(
                                            $adventure['naziv']
                                        ) ?>
                                    </h3>
                                    <p>Završeno putovanje <strong>
                                        <?= htmlspecialchars($durationText) ?>
                                    </strong></p>
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
                            <?php if ($is_own_profile): ?>
                            <h3>
                                Još nemaš završenih avantura.
                            </h3>
                            <p>
                                Kada završiš putovanje,
                                pojavit će se ovdje.
                            </p>
                            <?php endif; ?>
                            <?php if (!$is_own_profile): ?>
                            <h3>
                                <?= htmlspecialchars($user['ime'])?> još nije završio svoje avanture.
                            </h3>
                            <p>
                                Kada završi putovanje,
                                pojavit će se ovdje.
                            </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <section class="profile-bottom-grid reveal-up">
                <div class="profile-gallery-box">
                    <div class="gallery-top-row">
                        <h1><?= $is_own_profile ? 'SPREMLJENE AVANTURE' : 'SPREMLJENE AVANTURE' ?></h1>
                    </div>
 
                    <?php if (!empty($saved_adventures)): ?>
                        <div class="profile-gallery-grid">
                            <?php foreach ($saved_adventures as $adv): ?>
                                <?php
                                    $advImage = 'media/slike/map1.jpg';
                                    if (!empty($adv['adventure_image'])) {
                                        $cleanPath = ltrim($adv['adventure_image'], '/');
                                        if (file_exists(__DIR__ . '/' . $cleanPath)) {
                                            $advImage = $cleanPath;
                                        }
                                    }
                                    $advCreator = trim($adv['korisnicko_ime'] ?: ($adv['ime'] . ' ' . $adv['prezime'])) ?: 'Korisnik';
                                    $advTags = array_filter([
                                        $adv['trip_type'] ?? null,
                                        !empty($adv['budget_per_day']) ? $adv['budget_per_day'] . '€/dan' : null,
                                    ]);
                                ?>
                                <a href="adventure-details.php?id=<?= (int)$adv['id'] ?>"
                                   class="gallery-post-card gallery-post-card--link transition-link">
                                    <img
                                        src="<?= htmlspecialchars($advImage) ?>"
                                        class="gallery-post-image"
                                        alt="<?= htmlspecialchars($adv['title']) ?>"
                                    >
                                    <div class="gallery-post-content">
                                        <h3><?= htmlspecialchars($adv['title']) ?></h3>
                                        <?php if (!empty($advTags)): ?>
                                            <div class="profile-trip-tags" style="margin-top:6px;font-size:1rem;">
                                                <?= htmlspecialchars(implode(' · ', $advTags)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="gallery-saved-by">
                                            <?= htmlspecialchars($advCreator) ?>
                                        </span>
                                    </div>
                                    <?php if ($is_own_profile): ?>
                                    <button
                                        class="unsave-btn"
                                        data-id="<?= (int)$adv['id'] ?>"
                                        title="Ukloni iz spremljenih"
                                        onclick="event.preventDefault(); unsaveAdventure(this)"
                                    >✕</button>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="profile-empty-trips">
                            <?php if ($is_own_profile): ?>
                                <h3>Još nemaš spremljenih avantura.</h3>
                                <p>Istraži tuđa putovanja i spremi ona koja te inspiriraju.</p>
                                <a href="putovanja.php" class="hero-btn-home small-btn transition-link">
                                    ISTRAŽI AVANTURE
                                </a>
                            <?php else: ?>
                                <h3><?= htmlspecialchars($user['ime']) ?> još nema spremljenih avantura.</h3>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-friends-box reveal-right">
                    <h2>PRIJATELJI</h2>
                    <?php if (!empty($friends)): ?>
                        <?php foreach ($friends as $friend): ?>
                            <?php
                                $friendName = trim(
                                    $friend['korisnicko_ime']
                                    ?: ($friend['ime'] . ' ' . $friend['prezime'])
                                );
                                if ($friendName === '') {
                                    $friendName = 'Korisnik';
                                }
                                $friendImage = 'media/svg/roo-happy.svg';
                                if (!empty($friend['profilna_slika'])) {

                                    $cleanPath = ltrim($friend['profilna_slika'], '/');

                                    if (file_exists(__DIR__ . '/' . $cleanPath)) {
                                        $friendImage = $cleanPath;
                                    }
                                }
                            ?>
                            <a href="profil.php?id=<?= (int)$friend['id'] ?>" class="traveler-row traveler-row-link">
                                <div class="traveler-avatar">
                                    <img
                                        src="<?= htmlspecialchars($friendImage) ?>"
                                        class="traveler-avatar-img"
                                        alt=""
                                    >
                                </div>
                                <div class="traveler-info">
                                    <strong>
                                        <?= htmlspecialchars($friendName) ?>
                                    </strong>
                                    <span>
                                        <?= htmlspecialchars($friend['title'] ?? 'Putnik') ?>
                                    </span>
                                    <p>
                                        PRIJEĐENO
                                        <strong class="count-up"
                                            data-target="<?= (int)$friend['total_km'] ?>">
                                            0
                                        </strong>km
                                    </p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php if ($is_own_profile): ?>
                            <p>Još nemaš prijatelja.</p>
                            <p>Kreni istraživati, upoznavati ljude i stvarati prijateljstva!</p>
                        <?php endif; ?>
                        <?php if (!$is_own_profile): ?>
                            <p><?= htmlspecialchars($user['ime']) ?> još nema prijatelja.</p>
                            <p>Dodaj ga kao prijatelja i putujte zajedno!</p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="#" class="discover-more-link">VIŠE>>></a>
                </div>
            </section>
            <?php if ($is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue">ZABILJEŽI SVOJ KILOMETAR</h1>
            <?php endif; ?>
            <?php if (!$is_own_profile): ?>
            <h1 class="profile-section-title section-title-blue"><?= htmlspecialchars($user['ime']) ?> - KILOMETRI</h1>
            <?php endif; ?>
            <section class="profile-minigames reveal-up">
                <?php if ($is_own_profile): ?>
                <div class="profile-fridge-box">
                    <img src="media/svg/frizider.svg" alt="Fridge">
                </div>
                <div class="profile-minigame-intro">
                    <div class="profile-minimage-intro-box">
                        <h2>PRETVORI PUTOVANJE U IGRU</h2>
                        <strong>Skupljaj kilometre i osvajaj bedževe!</strong>
                        <p>Roo pretvara tvoje korake u kilometre, a tvoja putovanja u postignuća. Poveži se s ekipom, sudjeluj u tjednim izazovima i prati svoj napredak na globalnoj mapi.</p>
                        <button type="button" class="wizard-arrow wizard-next"><img src="media/svg/nextBtn.svg" alt="dalje"></button>
                    </div>
                </div>
                <div class="profile-my-status">
                    <h2 class="profile-status-title">TVOJ STATUS</h2>
                    <div class="profile-main-avatar">
                        <?php if ($profileImageSrc): ?>
                            <img src="<?= htmlspecialchars($profileImageSrc) ?>" alt="Profilna slika">
                        <?php else: ?>
                            <img src="media/svg/roo-happy.svg" alt="Default">
                        <?php endif; ?>
                    </div>
                        <h2 class="profile-main-name"><?= htmlspecialchars($display_name) ?></h2>
                        <?= htmlspecialchars($status_nadimak) ?>
                        <div class="quick-stats-box">
                            <div class="quick-stat">
                                <p>PRIJEĐENO</p>
                                <h3><b class="count-up" data-target="<?= $totalKilometers ?>">0</b>km</h3>
                            </div>
                            <div class="quick-stat">
                                <p>XP</p>
                                <h3><b class="count-up" data-target="<?= $totalXP ?>">0</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!$is_own_profile): ?>
                <div class="profile-fridge-box">
                    <img src="media/svg/frizider.svg" alt="Fridge">
                </div>
                <div class="profile-minigame-intro">
                    <div class="profile-minimage-intro-box">
                        <h2>Popis posjećenih gradova</h2>

                    </div>
                </div>
                <div class="profile-my-status">
                    <h2 class="profile-status-title">TVOJ STATUS</h2>
                    <div class="profile-main-avatar">
                        <?php if ($profileImageSrc): ?>
                            <img src="<?= htmlspecialchars($profileImageSrc) ?>" alt="Profilna slika">
                        <?php else: ?>
                            <img src="media/svg/roo-happy.svg" alt="Default">
                        <?php endif; ?>
                    </div>
                        <h2 class="profile-main-name"><?= htmlspecialchars($display_name) ?></h2>
                        <?= htmlspecialchars($status_nadimak) ?>
                        <div class="quick-stats-box">
                            <div class="quick-stat">
                                <p>PRIJEĐENO</p>
                                <h3><b class="count-up" data-target="<?= $totalKilometers ?>">0</b>km</h3>
                            </div>
                            <div class="quick-stat">
                                <p>BODOVI</p>
                                <h3><b class="count-up" data-target="<?= $totalKilometers * 6.7 ?>">0</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </section>
            <?php if ($is_own_profile): ?>
                <section class="profile-place-to-stay reveal-up">
                    <h1 class="stay-title section-title-blue profile-section-title">PONUDI SMJEŠTAJ</h1><br>
                    <strong>
                        Imaš sobu viška ili apartman koji želiš posuditi?
                    </strong>
                    <p>Ne dopusti da ti prostor skuplja prašinu. Pretvori ga u u bazu za nove svjetkse putnike.</p>
                    <button type="button" class="wizard-arrow stay-btn"><img src="media/svg/nextBtn.svg" alt="dalje"></button>
                </section>
            <?php endif; ?>
        </main>
    <?php include 'chatbot.php'; ?>
    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>
</body>
</html>