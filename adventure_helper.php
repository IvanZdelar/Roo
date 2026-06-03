<?php

function awardAchievement(PDO $pdo, int $userId, string $code): bool
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM achievements
        WHERE code = ?
    ");

    $stmt->execute([$code]);

    $achievement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$achievement) {
        return false;
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM user_achievements
        WHERE user_id = ?
        AND achievement_id = ?
    ");

    $stmt->execute([
        $userId,
        $achievement['id']
    ]);

    if ($stmt->fetch()) {
        return false;
    }

    $stmt = $pdo->prepare("
        INSERT INTO user_achievements
        (user_id, achievement_id)
        VALUES (?, ?)
    ");

    $stmt->execute([
        $userId,
        $achievement['id']
    ]);

    $stmt = $pdo->prepare("
        UPDATE users
        SET total_xp = total_xp + ?
        WHERE id = ?
    ");

    $stmt->execute([
        $achievement['xp_reward'],
        $userId
    ]);

    updateUserTitle($pdo, $userId);

    $stmt = $pdo->prepare("
        INSERT INTO notifications
        (
            user_id,
            type,
            reference_id,
            status
        )
        VALUES
        (?, 'achievement', ?, 'seen')
    ");

    $stmt->execute([
        $userId,
        $achievement['id']
    ]);

    return true;
}

function getTitleFromXp(int $xp): string
{
    if ($xp >= 10000) return 'Legenda Roo svijeta';
    if ($xp >= 7500) return 'Svjetski istraživač';
    if ($xp >= 5000) return 'Veliki avanturist';
    if ($xp >= 2500) return 'Iskusni putnik';
    if ($xp >= 1000) return 'Avanturist';
    if ($xp >= 500) return 'Istraživač';

    return 'Dnevni sanjar';
}

function updateUserTitle(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare("
        SELECT total_xp
        FROM users
        WHERE id = ?
    ");

    $stmt->execute([$userId]);

    $xp = (int)$stmt->fetchColumn();

    $title = getTitleFromXp($xp);

    $stmt = $pdo->prepare("
        UPDATE users
        SET title = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $title,
        $userId
    ]);
}

function checkAdventureAchievements(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM adventures
        WHERE user_id = ?
    ");

    $stmt->execute([$userId]);

    $count = (int)$stmt->fetchColumn();

    $map = [
        1  => 'CREATE_1',
        5  => 'CREATE_5',
        10 => 'CREATE_10',
        25 => 'CREATE_25',
        50 => 'CREATE_50'
    ];

    foreach ($map as $needed => $code) {
        if ($count >= $needed) {
            awardAchievement($pdo, $userId, $code);
        }
    }
}

function checkCompletedAchievements(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM adventures
        WHERE user_id = ?
        AND status='completed'
    ");

    $stmt->execute([$userId]);

    $count = (int)$stmt->fetchColumn();

    $map = [
        1  => 'COMPLETE_1',
        5  => 'COMPLETE_5',
        10 => 'COMPLETE_10',
        25 => 'COMPLETE_25'
    ];

    foreach ($map as $needed => $code) {
        if ($count >= $needed) {
            awardAchievement($pdo, $userId, $code);
        }
    }
}

function checkSavedAchievements(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM saved_adventures
        WHERE user_id = ?
    ");

    $stmt->execute([$userId]);

    $count = (int)$stmt->fetchColumn();

    $map = [
        1  => 'SAVE_1',
        10 => 'SAVE_10',
        50 => 'SAVE_50'
    ];

    foreach ($map as $needed => $code) {
        if ($count >= $needed) {
            awardAchievement($pdo, $userId, $code);
        }
    }
}

function checkFriendAchievements(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM friendships
        WHERE user_one = ?
        OR user_two = ?
    ");

    $stmt->execute([$userId, $userId]);

    $count = (int)$stmt->fetchColumn();

    $map = [
        1  => 'FRIEND_1',
        5  => 'FRIEND_5',
        20 => 'FRIEND_20',
        50 => 'FRIEND_50'
    ];

    foreach ($map as $needed => $code) {
        if ($count >= $needed) {
            awardAchievement($pdo, $userId, $code);
        }
    }
}
