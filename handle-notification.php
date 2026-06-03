<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id  = (int)$_SESSION['user_id'];
$notif_id = (int)($_GET['id'] ?? 0);
$action   = $_GET['action'] ?? '';

if ($notif_id <= 0 || !in_array($action, ['accept', 'reject', 'seen'])) {
    header('Location: notifications.php');
    exit;
}

// Dohvati notifikaciju i provjeri pripada li korisniku
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ?");
$stmt->execute([$notif_id, $user_id]);
$notif = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$notif) {
    header('Location: notifications.php');
    exit;
}

$type = $notif['type'];

if ($action === 'seen') {
    $stmt = $pdo->prepare("UPDATE notifications SET status = 'seen', is_read = 1 WHERE id = ?");
    $stmt->execute([$notif_id]);

} elseif ($action === 'reject') {
    $stmt = $pdo->prepare("UPDATE notifications SET status = 'rejected', is_read = 1 WHERE id = ?");
    $stmt->execute([$notif_id]);

} elseif ($action === 'accept') {

    $stmt = $pdo->prepare("UPDATE notifications SET status = 'accepted', is_read = 1 WHERE id = ?");
    $stmt->execute([$notif_id]);

    if ($type === 'friend_request') {
        $stmt = $pdo->prepare("
            SELECT id FROM friendships 
            WHERE (user_one = ? AND user_two = ?) 
            OR (user_one = ? AND user_two = ?)
        ");
        $stmt->execute([$user_id, $notif['from_user_id'], $notif['from_user_id'], $user_id]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("
                INSERT INTO friendships (user_one, user_two) 
                VALUES (?, ?)
            ");
            $stmt->execute([$user_id, $notif['from_user_id']]);
        }

        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, type, from_user_id, reference_id) 
            VALUES (?, 'friend_accepted', ?, ?)
        ");
        $stmt->execute([$notif['from_user_id'], $user_id, $notif['reference_id']]);
        require_once 'check-achievements.php';
        check_user_achievements($pdo, $user_id);
        check_user_achievements($pdo, $notif['from_user_id']);

    } elseif ($type === 'buddy_request') {
        $stmt = $pdo->prepare("
            SELECT id FROM adventure_participants 
            WHERE adventure_id = ? AND user_id = ?
        ");
        $stmt->execute([$notif['reference_id'], $notif['from_user_id']]);

        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("
                INSERT INTO adventure_participants (adventure_id, user_id, role)
                VALUES (?, ?, 'participant')
            ");
            $stmt->execute([$notif['reference_id'], $notif['from_user_id']]);
        }

        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, type, from_user_id, reference_id) 
            VALUES (?, 'buddy_accepted', ?, ?)
        ");
        $stmt->execute([$notif['from_user_id'], $user_id, $notif['reference_id']]);

        require_once 'check-achievements.php';
        check_user_achievements($pdo, $notif['from_user_id']);
    } elseif ($type === 'sleep_request') {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, type, from_user_id, reference_id) 
            VALUES (?, 'sleep_accepted', ?, ?)
        ");
        $stmt->execute([$notif['from_user_id'], $user_id, $notif['reference_id']]);
    }
}

header('Location: notifications.php');
exit;