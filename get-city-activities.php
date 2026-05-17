<?php
session_start();
require_once 'db.php';
require_once 'auth_helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Nisi prijavljen.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$city = trim($input['city'] ?? '');
$budgetLevel = trim($input['budget_level'] ?? 'mid');
$activityTypes = $input['activity_types'] ?? [];

if (!is_array($activityTypes)) {
    $activityTypes = [];
}

$cityMap = [
    'zagreb' => 'Zagreb',
    'karlovac' => 'Karlovac',
    'osijek' => 'Osijek',
    'split' => 'Split',
    'dubrovnik' => 'Dubrovnik'
];

$cityKey = strtolower(trim($city));

if (!isset($cityMap[$cityKey])) {
    echo json_encode([
        'success' => true,
        'city' => $city,
        'activities' => []
    ]);
    exit;
}

$normalizedCity = $cityMap[$cityKey];

$allowedBudget = ['low', 'mid', 'high'];

if (!in_array($budgetLevel, $allowedBudget, true)) {
    $budgetLevel = 'mid';
}

$activityTypes = array_values(array_filter(array_map('trim', $activityTypes)));

if (empty($activityTypes)) {
    echo json_encode([
        'success' => true,
        'city' => $normalizedCity,
        'activities' => []
    ]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($activityTypes), '?'));

if ($budgetLevel === 'low') {
    $allowedBudgets = ['low', 'all'];
} elseif ($budgetLevel === 'mid') {
    $allowedBudgets = ['low', 'mid', 'all'];
} else {
    $allowedBudgets = ['low', 'mid', 'high', 'all'];
}

$budgetPlaceholders = implode(',', array_fill(0, count($allowedBudgets), '?'));

$sql = "
    SELECT id, city, name, activity_type, budget_level, description
    FROM city_activities
    WHERE city = ?
    AND activity_type IN ($placeholders)
    AND budget_level IN ($budgetPlaceholders)
    ORDER BY 
        CASE 
            WHEN budget_level = 'low' THEN 1
            WHEN budget_level = 'mid' THEN 2
            WHEN budget_level = 'high' THEN 3
            WHEN budget_level = 'all' THEN 4
            ELSE 5
        END,
        name ASC
";

$params = array_merge([$normalizedCity], $activityTypes, $allowedBudgets);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode([
    'success' => true,
    'city' => $normalizedCity,
    'activities' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);