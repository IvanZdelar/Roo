<?php
session_start();

require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$adventureId = (int)($_GET['id'] ?? 0);

if ($adventureId <= 0) {
    header('Location: profil.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM adventures
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([$adventureId, $userId]);

$adventure = $stmt->fetch();

if (!$adventure) {
    header('Location: profil.php');
    exit;
}

if ($adventure['status'] === 'completed') {
    header('Location: adventure-details.php?id=' . $adventureId);
    exit;
}

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

$distanceKm = max(50, $days * 120);

$stmt = $pdo->prepare("
    UPDATE adventures
    SET status = 'completed',
        distance_km = ?
    WHERE id = ?
");

$stmt->execute([$distanceKm, $adventureId]);

header('Location: profil.php');
exit;