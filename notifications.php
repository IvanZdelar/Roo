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
mark_all_read($pdo, $user_id);

// Filter po kategoriji
$filter = $_GET['filter'] ?? 'all';

$allowed_filters = ['all', 'achievement', 'buddy_request', 'buddy_accepted', 'friend_request', 'friend_accepted', 'sleep_request', 'sleep_accepted'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'all';
}

// Dohvati notifikacije
if ($filter === 'all') {
    $stmt = $pdo->prepare("
        SELECT n.*, 
               u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
        FROM notifications n
        LEFT JOIN users u ON u.id = n.from_user_id
        WHERE n.user_id = ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->prepare("
        SELECT n.*, 
               u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
        FROM notifications n
        LEFT JOIN users u ON u.id = n.from_user_id
        WHERE n.user_id = ? AND n.type = ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$user_id, $filter]);
}

$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kategorije za filter gumbe
$categories = [
    'all'             => 'Sve',
    'achievement'     => 'Trofeji',
    'buddy_request'   => 'Buddy zahtjevi',
    'buddy_accepted'  => 'Buddy prihvaćeno',
    'friend_request'  => 'Prijateljstvo',
    'friend_accepted' => 'Prijatelj prihvaćen',
    'sleep_request'   => 'Smještaj zahtjev',
    'sleep_accepted'  => 'Smještaj prihvaćen',
];

// Boja lijevog bordera po tipu (kao na dizajnu)
function notif_color(string $type): string {
    return match($type) {
        'achievement'               => '#4a9eca',   // plava
        'buddy_request'             => '#c9922a',   // zlatna
        'buddy_accepted'            => '#c9922a',
        'friend_request'            => '#2a7a5c',   // zelena
        'friend_accepted'           => '#2a7a5c',
        'sleep_request', 'sleep_accepted' => '#7a5c2a', // smeđa
        default                     => '#999',
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

function notif_has_reject(string $type): bool {
    return in_array($type, ['buddy_request', 'friend_request', 'sleep_request']);
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
        <a href="javascript:history.back()" class="notif-back-btn">
            <img src="media/svg/prevBtn.svg" alt="Nazad">
        </a>
        <h1>MOJE OBAVIJESTI</h1>
        <div class="notif-header-icons">
            <a href="notifications.php?filter=sleep_request" class="notif-header-icon <?= $filter === 'sleep_request' ? 'active' : '' ?>">
                <img src="media/svg/bed.svg" alt="Smještaj">
            </a>
            <a href="notifications.php?filter=friend_request" class="notif-header-icon <?= $filter === 'friend_request' ? 'active' : '' ?>">
                <img src="media/svg/user.svg" alt="Prijatelji">
            </a>
            <a href="notifications.php?filter=buddy_request" class="notif-header-icon <?= $filter === 'buddy_request' ? 'active' : '' ?>">
                <img src="media/svg/user-plus.svg" alt="Buddy">
            </a>
        </div>
    </div>

    <!-- Filter pills -->
    <div class="notif-filters">
        <?php foreach ($categories as $key => $label): ?>
            <a href="notifications.php?filter=<?= $key ?>" 
               class="notif-filter-pill <?= $filter === $key ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Notifikacije -->
    <div class="notif-list-page">
        <?php if (empty($notifications)): ?>
            <div class="notif-page-empty">
                <p>Nema obavijesti u ovoj kategoriji.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
            <div class="notif-card" style="--notif-color: <?= notif_color($n['type']) ?>">
                <div class="notif-card-label"><?= notif_label($n['type']) ?></div>
                <div class="notif-card-body">
                    <p><?= notif_text($n) ?></p>
                </div>
                <div class="notif-card-actions">
                    <?php if (notif_has_reject($n['type'])): ?>
                        <a href="reject-notification.php?id=<?= (int)$n['id'] ?>" class="notif-btn-reject">✕</a>
                    <?php endif; ?>
                    <a href="<?= notif_link($n) ?>" class="notif-btn-arrow">→</a>
                </div>
                <div class="notif-card-time"><?= date('d.m.Y. H:i', strtotime($n['created_at'])) ?></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>