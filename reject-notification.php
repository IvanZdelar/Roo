<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$notif_id = (int)($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($notif_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
    $stmt->execute([$notif_id, $user_id]);
}

header('Location: notifications.php');
exit;