<?php

session_start();
require_once __DIR__ . '/bootstrap.php';
$pdo = require __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';
 
header('Content-Type: application/json; charset=utf-8');
 
$user_id = (int)($_SESSION['user_id'] ?? 0);
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}
 
if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}
 
$body        = json_decode(file_get_contents('php://input'), true);
$adventure_id = (int)($body['adventure_id'] ?? 0);
 
if (!$adventure_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Missing adventure_id']);
    exit;
}
 
// Verify adventure exists
$chk = $pdo->prepare('SELECT id, user_id FROM adventures WHERE id = ?');
$chk->execute([$adventure_id]);
$adv = $chk->fetch();
 
if (!$adv) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Adventure not found']);
    exit;
}
 
// Can't save your own adventure
if ((int)$adv['user_id'] === $user_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Cannot save your own adventure']);
    exit;
}
 
// Check if already saved
$existing = $pdo->prepare('SELECT id FROM saved_adventures WHERE user_id = ? AND adventure_id = ?');
$existing->execute([$user_id, $adventure_id]);
 
if ($existing->fetch()) {
    // Unsave
    $pdo->prepare('DELETE FROM saved_adventures WHERE user_id = ? AND adventure_id = ?')
        ->execute([$user_id, $adventure_id]);
    echo json_encode(['ok' => true, 'saved' => false]);
} else {
    // Save
    $pdo->prepare('INSERT INTO saved_adventures (user_id, adventure_id) VALUES (?, ?)')
        ->execute([$user_id, $adventure_id]);
    echo json_encode(['ok' => true, 'saved' => true]);
}