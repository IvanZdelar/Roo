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
            ['id' => 'rajf',    'name' => 'Rozi rajf',         'file' => 'hats/hat-rajf.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'rajf2', 'name' => 'Narančasti rajf',   'file' => 'hats/hat-rajf2.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'rajf3',  'name' => 'Srce rajf',       'file' => 'hats/hat-rajf3.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'sesir',  'name' => 'sesir',       'file' => 'hats/hat-sesir.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'sparkle',  'name' => 'sparkle',       'file' => 'hats/hat-sparkle.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'sparkle2',  'name' => 'sparkle2',       'file' => 'hats/hat-sparkle2.svg', 'thumb_viewbox' => '0 0 127 80'],
            ['id' => 'hr',  'name' => 'hr',       'file' => 'hats/hat-hr.svg', 'thumb_viewbox' => '0 0 127 80'],
        ],
        'shirts' => [
            ['id' => 'naran',   'name' => 'Naran',     'file' => 'shirts/shirt-naran.svg', 'thumb_viewbox' => '0 70 127 90'],
            ['id' => 'plava',    'name' => 'Plava', 'file' => 'shirts/shirt-plava.svg', 'thumb_viewbox' => '0 70 127 90'],
            ['id' => 'roza',    'name' => 'Roza', 'file' => 'shirts/shirt-roza.svg', 'thumb_viewbox' => '0 70 127 90'],
            ['id' => 'srce',    'name' => 'Srce', 'file' => 'shirts/shirt-srce.svg', 'thumb_viewbox' => '0 70 127 90'],
            ['id' => 'narod',    'name' => 'Narod', 'file' => 'shirts/shirt-narod.svg', 'thumb_viewbox' => '0 70 127 90'],
        ],
        'hand_items' => [
            ['id' => 'hand_srce', 'name' => 'Srce',       'file' => 'hand/hand-srce.svg', 'thumb_viewbox' => '0 85 127 76'],
            ['id' => 'zastava',    'name' => 'Zastava',       'file' => 'hand/hand-zastava.svg', 'thumb_viewbox' => '0 85 127 76'],
        ],
    ];
}