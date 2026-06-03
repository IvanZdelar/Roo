<?php
// mascot_helper.php

function get_user_mascot(PDO $pdo, int $user_id): array
{
    $stmt = $pdo->prepare("SELECT * FROM user_mascot WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $mascot = $stmt->fetch(PDO::FETCH_ASSOC);

    return $mascot ?: ['hat' => null, 'shirt' => null, 'hand_item' => null, 'unlocked_items' => '[]', 'emotion' => 'roo'];
}

function save_user_mascot(PDO $pdo, int $user_id, ?string $hat, ?string $shirt, ?string $hand_item, ?string $emotion): void
{
    $stmt = $pdo->prepare("
        INSERT INTO user_mascot (user_id, hat, shirt, hand_item, emotion)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE hat = ?, shirt = ?, hand_item = ?, emotion = ?
    ");
    $stmt->execute([$user_id, $hat, $shirt, $hand_item, $emotion, $hat, $shirt, $hand_item, $emotion]);
}

// Katalog svih dostupnih predmeta
function get_items_catalog(): array
{
    return [
        'emotions' => [
            ['id' => 'roo',       'name' => 'Normalan',   'file' => 'roo.svg'],
            ['id' => 'roo-happy', 'name' => 'Sretan', 'file' => 'roo-happy.svg'],
            ['id' => 'roo-wink',  'name' => 'Namig',    'file' => 'roo-wink.svg'],
            ['id' => 'roo-wink',  'name' => 'Namig',    'file' => 'roo-wink.svg'],
            ['id' => 'roo-sad',   'name' => 'Tužan',    'file' => 'roo-sad.svg'],
            ['id' => 'roo-mad',  'name' => 'Ljut',    'file' => 'roo-mad.svg'],
            ['id' => 'roo-sleep',  'name' => 'Spava',    'file' => 'roo-sleep.svg'],
        ],
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