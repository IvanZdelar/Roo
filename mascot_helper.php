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
            ['id' => 'rajf',    'name' => 'Rozi rajf',         'file' => 'hats/hat-rajf.svg'],
            ['id' => 'rajf2', 'name' => 'Narančasti rajf',   'file' => 'hats/hat-rajf2.svg'],
            ['id' => 'rajf3',  'name' => 'Srce rajf',       'file' => 'hats/hat-rajf3.svg'],
            ['id' => 'sesir',  'name' => 'sesir',       'file' => 'hats/hat-sesir.svg'],
            ['id' => 'sparkle',  'name' => 'sparkle',       'file' => 'hats/hat-sparkle.svg'],
            ['id' => 'sparkle2',  'name' => 'sparkle2',       'file' => 'hats/hat-sparkle2.svg'],
            ['id' => 'hr',  'name' => 'hr',       'file' => 'hats/hat-hr.svg'],
        ],
        'shirts' => [
            ['id' => 'naran',   'name' => 'Naran',     'file' => 'shirts/shirt-naran.svg'],
            ['id' => 'plava',    'name' => 'Plava', 'file' => 'shirts/shirt-plava.svg'],
            ['id' => 'roza',    'name' => 'Roza', 'file' => 'shirts/shirt-roza.svg'],
            ['id' => 'srce',    'name' => 'Srce', 'file' => 'shirts/shirt-srce.svg'],
            ['id' => 'narod',    'name' => 'Narod', 'file' => 'shirts/shirt-narod.svg'],
        ],
        'hand_items' => [
            ['id' => 'hand_srce', 'name' => 'Srce',       'file' => 'hand/hand-srce.svg'],
            ['id' => 'zastava',    'name' => 'Zastava',       'file' => 'hand/hand-zastava.svg'],
        ],
    ];
}