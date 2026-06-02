<?php
// mascot_helper.php

function get_user_mascot(PDO $pdo, int $user_id): array
{
    $stmt = $pdo->prepare("SELECT * FROM user_mascot WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $mascot = $stmt->fetch(PDO::FETCH_ASSOC);

    return $mascot ?: ['hat' => null, 'shirt' => null, 'hand_item' => null, 'unlocked_items' => '[]'];
}

function save_user_mascot(PDO $pdo, int $user_id, ?string $hat, ?string $shirt, ?string $hand_item): void
{
    $stmt = $pdo->prepare("
        INSERT INTO user_mascot (user_id, hat, shirt, hand_item)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE hat = ?, shirt = ?, hand_item = ?
    ");
    $stmt->execute([$user_id, $hat, $shirt, $hand_item, $hat, $shirt, $hand_item]);
}

// Katalog svih dostupnih predmeta
function get_items_catalog(): array
{
    return [
        'hats' => [
            ['id' => 'hat_cap',    'name' => 'Cap',         'file' => 'hats/hat_cap.svg'],
            ['id' => 'hat_wizard', 'name' => 'Čarobnjak',   'file' => 'hats/hat_wizard.svg'],
            ['id' => 'hat_crown',  'name' => 'Kruna',       'file' => 'hats/hat_crown.svg'],
        ],
        'shirts' => [
            ['id' => 'shirt_blue',   'name' => 'Plava',     'file' => 'shirts/shirt_blue.svg'],
            ['id' => 'shirt_roo',    'name' => 'Roo brand', 'file' => 'shirts/shirt_roo.svg'],
        ],
        'hand_items' => [
            ['id' => 'hand_coffee', 'name' => 'Kava',       'file' => 'hand/hand_coffee.svg'],
            ['id' => 'hand_map',    'name' => 'Mapa',       'file' => 'hand/hand_map.svg'],
            ['id' => 'hand_camera', 'name' => 'Kamera',     'file' => 'hand/hand_camera.svg'],
        ],
    ];
}