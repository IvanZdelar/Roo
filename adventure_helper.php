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