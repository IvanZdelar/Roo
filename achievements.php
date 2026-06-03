<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Dohvati sve achievemente
$stmt = $pdo->prepare("
    SELECT a.*, 
           ua.earned_at,
           CASE WHEN ua.id IS NOT NULL THEN 1 ELSE 0 END AS earned
    FROM achievements a
    LEFT JOIN user_achievements ua ON ua.achievement_id = a.id AND ua.user_id = ?
    ORDER BY earned DESC, a.xp_reward ASC
");
$stmt->execute([$user_id]);
$achievements = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($achievements);
$earned = count(array_filter($achievements, fn($a) => $a['earned']));

// Dohvati XP
$stmt = $pdo->prepare("SELECT total_xp, title FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$userStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Grupiraj po kategoriji
$categories = [
    'ADV'    => ['name' => 'Kreiranje avantura', 'icon' => '🗺️'],
    'COMP'   => ['name' => 'Završene avanture',  'icon' => '✅'],
    'JOIN'   => ['name' => 'Pridruživanja',       'icon' => '🤝'],
    'FRIEND' => ['name' => 'Prijatelji',          'icon' => '👥'],
    'KM'     => ['name' => 'Kilometri',           'icon' => '📍'],
    'CITY'   => ['name' => 'Gradovi',             'icon' => '🏙️'],
    'GROUP'  => ['name' => 'Grupna putovanja',    'icon' => '🧳'],
    'SAVE'   => ['name' => 'Spremljene avanture', 'icon' => '⭐'],
    'XP'     => ['name' => 'XP Leveli',           'icon' => '⚡'],
    'OTHER'  => ['name' => 'Posebni',             'icon' => '🏆'],
];

$grouped = [];
foreach ($achievements as $a) {
    $prefix = explode('_', $a['code'])[0];
    $cat = isset($categories[$prefix]) ? $prefix : 'OTHER';
    $grouped[$cat][] = $a;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Achievementi</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/hamburger.css">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
    <style>
        .achievements-page {
            min-height: 100vh;
            background: var(--bez);
        }

        .achievements-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 100px 20px 60px;
        }

        .achievements-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 32px;
        }

        .achievements-header h1 {
            font-size: 3rem;
            color: var(--tplava);
            margin: 0;
        }

        /* XP bar */
        .xp-bar-box {
            background: var(--tplava);
            border-radius: 24px;
            padding: 1.5rem 2rem;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .xp-bar-info {
            color: white;
        }

        .xp-bar-info h2 {
            color: var(--naran);
            font-size: 1.8rem;
            margin: 0;
        }

        .xp-bar-info p {
            color: rgba(255,255,255,0.75);
            margin: 0;
            font-size: 0.95rem;
        }

        .xp-bar-track {
            flex: 1;
            min-width: 200px;
        }

        .xp-bar-label {
            display: flex;
            justify-content: space-between;
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .xp-bar-bg {
            height: 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 999px;
            overflow: hidden;
        }

        .xp-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--naran), #f5d06a);
            border-radius: 999px;
            transition: width 1s ease;
        }

        .xp-count {
            color: white;
            text-align: center;
        }

        .xp-count strong {
            display: block;
            font-size: 2.5rem;
            font-family: var(--naslov);
            color: var(--naran);
        }

        .xp-count span {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        /* Progress summary */
        .achievements-summary {
            display: flex;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .summary-pill {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            text-align: center;
            flex: 1;
            min-width: 120px;
        }

        .summary-pill strong {
            display: block;
            font-size: 2rem;
            font-family: var(--naslov);
            color: var(--tplava);
        }

        .summary-pill span {
            font-size: 0.85rem;
            color: var(--siva);
        }

        /* Category */
        .achievement-category {
            margin-bottom: 36px;
        }

        .category-title {
            font-family: var(--naslov);
            color: var(--tplava);
            font-size: 1.5rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        /* Achievement card */
        .achievement-card {
            background: white;
            border-radius: 20px;
            padding: 1.4rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 3px solid transparent;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .achievement-card.earned {
            border-color: var(--naran);
            background: linear-gradient(135deg, #fff8ec, #ffffff);
        }

        .achievement-card.locked {
            opacity: 0.55;
            filter: grayscale(0.4);
        }

        .achievement-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        }

        .achievement-card.earned:hover {
            border-color: var(--smeda);
        }

        .achievement-icon {
            font-size: 2.5rem;
            margin-bottom: 0.6rem;
            display: block;
        }

        .achievement-name {
            font-family: var(--naslov);
            color: var(--tplava);
            font-size: 1rem;
            margin-bottom: 0.3rem;
            line-height: 1.2;
        }

        .achievement-desc {
            font-size: 0.82rem;
            color: var(--siva);
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .achievement-xp {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--tplava);
            color: white;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .achievement-card.earned .achievement-xp {
            background: var(--naran);
        }

        .achievement-earned-date {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.72rem;
            color: var(--smeda);
            font-weight: 600;
        }

        .achievement-lock-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.1rem;
            opacity: 0.4;
        }

        /* Earned badge */
        .earned-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--naran);
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 4px 10px;
            border-bottom-left-radius: 12px;
            letter-spacing: 0.5px;
        }

        @media (max-width: 600px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .achievements-header h1 { font-size: 2rem; }
            .xp-bar-box { flex-direction: column; gap: 1rem; }
        }
    </style>
</head>
<body class="achievements-page">
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span></div>
</div>

<?php include 'nav.php'; ?>

<div class="achievements-wrap">

    <!-- Header -->
    <div class="achievements-header">
        <a href="profil.php" class="notif-back-btn transition-link">
            <img src="media/svg/prevBtn.svg" alt="Nazad">
        </a>
        <h1>ACHIEVEMENTI</h1>
    </div>

    <!-- XP Bar -->
    <?php
    $xp = (int)($userStats['total_xp'] ?? 0);
    $title = $userStats['title'] ?? 'Dnevni sanjar';
    $levels = [0, 100, 300, 700, 1500, 3000, 5000, 8000, 12000, 18000, 25000];
    $nextLevel = 25000;
    $prevLevel = 0;
    foreach ($levels as $i => $level) {
        if ($xp < $level) {
            $nextLevel = $level;
            $prevLevel = $levels[$i - 1] ?? 0;
            break;
        }
    }
    $progress = $nextLevel > $prevLevel 
        ? min(100, round(($xp - $prevLevel) / ($nextLevel - $prevLevel) * 100)) 
        : 100;
    ?>
    <div class="xp-bar-box">
        <div class="xp-bar-info">
            <h2><?= htmlspecialchars($title) ?></h2>
            <p>Tvoj trenutni status</p>
        </div>
        <div class="xp-bar-track">
            <div class="xp-bar-label">
                <span><?= number_format($prevLevel) ?> XP</span>
                <span><?= number_format($nextLevel) ?> XP</span>
            </div>
            <div class="xp-bar-bg">
                <div class="xp-bar-fill" style="width: <?= $progress ?>%"></div>
            </div>
        </div>
        <div class="xp-count">
            <strong><?= number_format($xp) ?></strong>
            <span>ukupno XP</span>
        </div>
    </div>

    <!-- Summary -->
    <div class="achievements-summary">
        <div class="summary-pill">
            <strong><?= $earned ?></strong>
            <span>Osvojeno</span>
        </div>
        <div class="summary-pill">
            <strong><?= $total - $earned ?></strong>
            <span>Zaključano</span>
        </div>
        <div class="summary-pill">
            <strong><?= $total > 0 ? round($earned / $total * 100) : 0 ?>%</strong>
            <span>Završeno</span>
        </div>
        <div class="summary-pill">
            <strong><?= number_format($xp) ?></strong>
            <span>Ukupno XP</span>
        </div>
    </div>

    <!-- Achievement categories -->
    <?php foreach ($grouped as $prefix => $items): ?>
        <?php $cat = $categories[$prefix] ?? ['name' => 'Ostalo', 'icon' => '🏅']; ?>
        <div class="achievement-category">
            <div class="category-title">
                <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
                <span style="font-size:1rem;color:var(--siva);font-family:var(--tekst);margin-left:8px;">
                    (<?= count(array_filter($items, fn($a) => $a['earned'])) ?>/<?= count($items) ?>)
                </span>
            </div>
            <div class="category-grid">
                <?php foreach ($items as $a): ?>
                <div class="achievement-card <?= $a['earned'] ? 'earned' : 'locked' ?>">
                    <?php if ($a['earned']): ?>
                        <div class="earned-badge">✓ OSVOJENO</div>
                        <span class="achievement-earned-date">
                            <?= date('d.m.Y', strtotime($a['earned_at'])) ?>
                        </span>
                    <?php else: ?>
                        <span class="achievement-lock-icon">🔒</span>
                    <?php endif; ?>

                    <span class="achievement-icon">
                        <?php
                        $icons = [
                            'ADV' => '🗺️', 'COMP' => '✅', 'JOIN' => '🤝',
                            'FRIEND' => '👥', 'KM' => '📍', 'CITY' => '🏙️',
                            'GROUP' => '🧳', 'SAVE' => '⭐', 'XP' => '⚡',
                        ];
                        $p = explode('_', $a['code'])[0];
                        echo $icons[$p] ?? '🏆';
                        ?>
                    </span>

                    <div class="achievement-name"><?= htmlspecialchars($a['name']) ?></div>
                    <div class="achievement-desc"><?= htmlspecialchars($a['description']) ?></div>
                    <span class="achievement-xp">⚡ <?= number_format($a['xp_reward']) ?> XP</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<script src="js/main.js"></script>
<script src="js/hamburger.js"></script>
</body>
</html>