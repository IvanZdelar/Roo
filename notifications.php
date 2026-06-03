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

$user_id = $_SESSION['user_id'];

// Označi sve kao pročitano kad otvori stranicu
if (isset($_GET['mark_read'])) {
    mark_all_read($pdo, $user_id);
    header('Location: notifications.php');
    exit;
}

// Filter po kategoriji
$filter = $_GET['filter'] ?? 'all';

$filter_map = [
    'all'      => [],
    'smjestaj' => ['sleep_request', 'sleep_accepted'],
    'nagrade'  => ['achievement'],
    'prijatelji' => ['friend_request', 'friend_accepted', 'buddy_request', 'buddy_accepted'],
];

if (!array_key_exists($filter, $filter_map)) {
    $filter = 'all';
}

$types = $filter_map[$filter];

$show_seen = isset($_GET['seen']);

if (empty($types)) {
    $stmt = $pdo->prepare("
        SELECT n.*, u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
        FROM notifications n
        LEFT JOIN users u ON u.id = n.from_user_id
        WHERE n.user_id = ?
        AND (" . ($show_seen ? "n.status = 'seen' OR n.status = 'accepted' OR n.status = 'rejected'" : "n.status = 'pending'") . ")
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$user_id]);
} else {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $stmt = $pdo->prepare("
        SELECT n.*, u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
        FROM notifications n
        LEFT JOIN users u ON u.id = n.from_user_id
        WHERE n.user_id = ? AND n.type IN ($placeholders)
        AND (" . ($show_seen ? "n.status = 'seen' OR n.status = 'accepted' OR n.status = 'rejected'" : "n.status = 'pending'") . ")
        ORDER BY n.created_at DESC
    ");
    $stmt->execute(array_merge([$user_id], $types));
}

$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Boja lijevog bordera po tipu (kao na dizajnu)
function notif_color(string $type): string {
    return match($type) {
        'achievement'               => '#294F69',   // plava
        'buddy_request'             => '#CD9842',   // zlatna
        'buddy_accepted'            => '#CD9842',
        'friend_request'            => '#CD9842',   // zelena
        'friend_accepted'           => '#CD9842',
        'sleep_request', 'sleep_accepted' => '#3A5A40', // smeđa
        default                     => '#D9D9D9',
    };
}

function notif_label(string $type): string {
    return match($type) {
        'achievement'     => 'Roo trofeji',
        'buddy_request'   => 'Zahtjevi za "Buddyja"',
        'buddy_accepted'  => 'Buddy prihvaćen',
        'friend_request'  => 'Zahtjev za prijateljstvo',
        'friend_accepted' => 'Prijatelj prihvaćen',
        'sleep_request'   => 'Domaćini i smještaj',
        'sleep_accepted'  => 'Domaćini i smještaj',
        default           => 'Obavijest',
    };
}

function notif_text(array $n): string {
    $from = htmlspecialchars(trim($n['korisnicko_ime'] ?: ($n['ime'] . ' ' . $n['prezime'])) ?: 'Korisnik');
    return match($n['type']) {
        'achievement'     => htmlspecialchars($n['message'] ?? 'Osvojio si novi achievement!'),
        'buddy_request'   => "{$from} želi se pridružiti tvojoj avanturi. Pogledaj profil i baci mu poruku!",
        'buddy_accepted'  => "{$from} je prihvatio tvoj zahtjev za pridruživanje avanturi.",
        'friend_request'  => "{$from} šalje ti zahtjev za prijateljstvo.",
        'friend_accepted' => "{$from} je prihvatio tvoj zahtjev za prijateljstvo!",
        'sleep_request'   => "{$from} želi prespavati kod tebe. Provjeri detalje!",
        'sleep_accepted'  => "{$from} je prihvatila tvoj upit za sobu! Pripremi ruksak i dogovori detalje oko kave.",
        default           => 'Nova obavijest.',
    };
}

function notif_link(array $n): string {
    return match($n['type']) {
        'buddy_request', 'buddy_accepted' => 'adventure-details.php?id=' . ((int)($n['reference_id'] ?? 0)),
        'friend_request', 'friend_accepted' => 'profil.php?id=' . ((int)($n['from_user_id'] ?? 0)),
        'sleep_request', 'sleep_accepted'   => 'adventure-details.php?id=' . ((int)($n['reference_id'] ?? 0)),
        default => '#',
    };
}

function notif_has_accept(array $n): bool {
    return in_array($n['type'], ['friend_request', 'buddy_request', 'sleep_request'])
        && ($n['status'] ?? 'pending') === 'pending';
}

function notif_has_reject(string $type, array $n): bool {
    return in_array($type, ['buddy_request', 'friend_request', 'sleep_request'])
        && ($n['status'] ?? 'pending') === 'pending';
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Moje obavijesti</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/hamburger.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body>
    <div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">
        Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
    </div>
</div>
<?php include 'nav.php'; ?>
<div class="notif-page">

    <!-- Header -->
    <div class="notif-page-header">
        <a href="profil.php" class="notif-back-btn transition-link">
            <img src="media/svg/prevBtn.svg" alt="Nazad">
        </a>
        <h1>MOJE OBAVIJESTI</h1>
        <div class="notif-header-icons">
        <a href="notifications.php" 
        class="notif-header-icon sve-btn <?= $filter === 'all' && !$show_seen ? 'active' : '' ?>" data-no-transition>
            <img src="media/svg/notif-bell.svg" alt="Sve">
        </a>
        <a href="notifications.php?filter=smjestaj<?= $show_seen ? '&seen' : '' ?>" 
        class="notif-header-icon krevet-btn <?= $filter === 'smjestaj' ? 'active' : '' ?>" data-no-transition>
            <img src="media/svg/krevet.svg" alt="Smještaj">
        </a>
        <a href="notifications.php?filter=nagrade<?= $show_seen ? '&seen' : '' ?>" 
        class="notif-header-icon nagrada-btn <?= $filter === 'nagrade' ? 'active' : '' ?>" data-no-transition>
            <img src="media/svg/nagrada.svg" alt="Nagrade">
        </a>
        <a href="notifications.php?filter=prijatelji<?= $show_seen ? '&seen' : '' ?>" 
        class="notif-header-icon prijatelj-btn <?= $filter === 'prijatelji' ? 'active' : '' ?>" data-no-transition>
            <img src="media/svg/prijatelj.svg" alt="Prijatelji">
        </a>
        <!-- Toggle seen/unseen -->
        <a href="notifications.php?filter=<?= $filter ?><?= $show_seen ? '' : '&seen' ?>" 
        class="notif-header-icon seen-btn <?= $show_seen ? 'active' : '' ?>" data-no-transition>
            <img src="media/svg/seen.png" alt="Pregledane">
        </a>
    </div>
    </div>

    <!-- Notifikacije -->
    <div class="notif-list-page reveal-up">
        <?php if (empty($notifications)): ?>
            <div class="notif-page-empty">
                <p>Nema obavijesti u ovoj kategoriji.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
            <div class="notif-card" style="--notif-color: <?= notif_color($n['type']) ?>">
                <div>
                    <div class="notif-card-label"><?= notif_label($n['type']) ?></div>
                    <div class="notif-card-body">
                        <p><?= notif_text($n) ?></p>
                    </div>
                </div>
                <div class="notif-card-actions">
                    <?php if (notif_has_accept($n)): ?>
                        <a href="handle-notification.php?id=<?= (int)$n['id'] ?>&action=accept" 
                        class="notif-btn-accept">✓</a>
                    <?php endif; ?>

                    <?php if (notif_has_reject($n['type'], $n)): ?>
                        <a href="handle-notification.php?id=<?= (int)$n['id'] ?>&action=reject" 
                        class="notif-btn-reject"><img src="media/svg/x.svg" alt=""></a>
                    <?php endif; ?>

                    <?php if ($n['type'] === 'achievement' && ($n['status'] ?? 'pending') !== 'seen'): ?>
                        <a href="handle-notification.php?id=<?= (int)$n['id'] ?>&action=seen" 
                        class="notif-btn-seen"><img src="media/svg/seen.png" alt=""></a>
                    <?php endif; ?>

                    <a href="<?= notif_link($n) ?>" class="notif-btn-arrow"><img src="media/svg/nextBtn.svg" alt="Slijedeće"></a>
                </div>
                <div class="notif-card-time"><?= date('d.m.Y. H:i', strtotime($n['created_at'])) ?></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
<?php include 'chatbot.php'; ?>
<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>