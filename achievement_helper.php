<?php
// achievement_helper.php

require_once 'notifications_helper.php';

function award_achievement(PDO $pdo, int $user_id, string $achievement_code): bool
{
    $stmt = $pdo->prepare("SELECT * FROM achievements WHERE code = ? LIMIT 1");
    $stmt->execute([$achievement_code]);
    $achievement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$achievement) return false;

    // Provjeri je li već osvojeno
    $stmt = $pdo->prepare("SELECT id FROM user_achievements WHERE user_id = ? AND achievement_id = ?");
    $stmt->execute([$user_id, $achievement['id']]);
    if ($stmt->fetch()) return false;

    // Spremi achievement
    $stmt = $pdo->prepare("INSERT INTO user_achievements (user_id, achievement_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $achievement['id']]);

    // Dodaj XP
    $stmt = $pdo->prepare("UPDATE users SET total_xp = total_xp + ? WHERE id = ?");
    $stmt->execute([$achievement['xp_reward'], $user_id]);

    // Ažuriraj title
    update_user_title($pdo, $user_id);

    // Pošalji notifikaciju
    create_notification(
        $pdo,
        $user_id,
        'achievement',
        null,
        $achievement['id'],
        '🏆 ' . $achievement['name'] . ' — ' . $achievement['description'] . ' (+' . $achievement['xp_reward'] . ' XP)'
    );

    return true;
}

function get_title_from_xp(int $xp): string
{
    if ($xp >= 25000) return 'Legenda Roo-a';
    if ($xp >= 18000) return 'Osvajač kontinenata';
    if ($xp >= 12000) return 'Nomad';
    if ($xp >= 8000)  return 'Kapetan ekspedicije';
    if ($xp >= 5000)  return 'Svjetski istraživač';
    if ($xp >= 3000)  return 'Lovac na horizonte';
    if ($xp >= 1500)  return 'Iskusni putnik';
    if ($xp >= 700)   return 'Avanturist';
    if ($xp >= 300)   return 'Radoznali istraživač';
    if ($xp >= 100)   return 'Početnik putnik';
    return 'Dnevni sanjar';
}

function update_user_title(PDO $pdo, int $user_id): void
{
    $stmt = $pdo->prepare("SELECT total_xp FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $xp = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("UPDATE users SET title = ? WHERE id = ?");
    $stmt->execute([get_title_from_xp($xp), $user_id]);
}

