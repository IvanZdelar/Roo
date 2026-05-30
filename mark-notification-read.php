<?php
session_start();
require_once 'db.php';
require_once 'notifications_helper.php';

$pdo = require 'db.php';

if (isset($_SESSION['user_id'])) {
    mark_all_read($pdo, $_SESSION['user_id']);
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'dashboard.php'));
exit;