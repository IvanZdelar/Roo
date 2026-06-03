<?php

require_once 'notifications_helper.php';

function award_achievement(
    PDO $pdo,
    int $user_id,
    string $achievement_code
): bool
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM achievements
        WHERE code = ?
        LIMIT 1
    ");

    $stmt->execute([$achievement_code]);

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
        $user_id,
        $achievement['id']
    ]);

    if ($stmt->fetch()) {
        return false;
    }

    $stmt = $pdo->prepare("
        INSERT INTO user_achievements
        (
            user_id,
            achievement_id
        )
        VALUES (?, ?)
    ");

    $stmt->execute([
        $user_id,
        $achievement['id']
    ]);

    /*
    XP
    */

    $stmt = $pdo->prepare("
        UPDATE users
        SET total_xp = total_xp + ?
        WHERE id = ?
    ");

    $stmt->execute([
        $achievement['xp_reward'],
        $user_id
    ]);

    /*
    Notification
    */

    create_notification(
        $pdo,
        $user_id,
        'achievement',
        null,
        $achievement['id'],
        '🏆 Osvojio si achievement "' .
        $achievement['name'] .
        '" (+'.$achievement['xp_reward'].' XP)'
    );

    return true;
}