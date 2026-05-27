<?php
session_start();
$pdo = require 'db.php';
require_once 'auth_helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Nisi prijavljen.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$cities = $input['cities'] ?? [];
$type = trim($input['type'] ?? '');
$maxPrice = (int)($input['max_price'] ?? 300);

if (!is_array($cities)) $cities = [];

$allowedTypes = ['hotel_motel', 'hostel_apartment'];
if (!in_array($type, $allowedTypes, true)) {
    echo json_encode(['success' => false, 'message' => 'Krivi tip smještaja.']);
    exit;
}

$cityMap = [
    'zagreb' => 'Zagreb',
    'karlovac' => 'Karlovac',
    'osijek' => 'Osijek',
    'split' => 'Split',
    'dubrovnik' => 'Dubrovnik'
];

$cleanCities = [];

foreach ($cities as $city) {
    $key = strtolower(trim($city));
    if (isset($cityMap[$key])) {
        $cleanCities[] = $cityMap[$key];
    }
}

$cleanCities = array_values(array_unique($cleanCities));

if (empty($cleanCities)) {
    echo json_encode(['success' => true, 'accommodations' => []]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($cleanCities), '?'));

$sql = "
    SELECT id, city, name, accommodation_type, max_price_per_night, image, description
    FROM accommodations
    WHERE city IN ($placeholders)
    AND accommodation_type = ?
    AND max_price_per_night <= ?
    ORDER BY city ASC, max_price_per_night ASC, name ASC
";

$params = array_merge($cleanCities, [$type, $maxPrice]);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode([
    'success' => true,
    'accommodations' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);