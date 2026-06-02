<?php
require_once 'notifications_helper.php';
if (!isset($user_id)) {
    $user_id = $_SESSION['user_id'] ?? null;
}
$notif_count = isset($user_id) ? get_unread_count($pdo, $user_id) : 0;
$nav_notifications = isset($user_id) ? get_notifications($pdo, $user_id, 10) : [];
?>
    
    <div class="roo-menu">
        <div class="roo-hamburger" id="rooHamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="roo-notif" id="rooNotif">
            <a href="notifications.php" class="notif-bell transition-link" id="notifBell">
                <img src="media/svg/notif-bell.svg" alt="Notifikacije">
                <?php if ($notif_count > 0): ?>
                    <span class="notif-badge"><?= $notif_count > 99 ? '99+' : $notif_count ?></span>
                <?php endif; ?>
            </a>
        </div>

        <div class="roo-menu-panel" id="rooMenuPanel">
            <a href="dashboard.php" class="roo-menu-link transition-link">Početna</a>
            <a href="profil.php" class="roo-menu-link transition-link">Profil</a>
            <a href="putovanja.php" class="roo-menu-link transition-link">Putovanja</a>
            <a href="razgovori.php" class="roo-menu-link transition-link">Razgovori</a>
            <a href="create-adventure.php" class="create-adventure-btn  transition-link">Osmisli Putovanje</a>
        </div>
    </div>

    <header class="home-topbar">
        <a href="#"><img src="media/svg/LOGO.svg" class="logo" alt=""></a>
    </header>