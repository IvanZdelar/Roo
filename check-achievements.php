<?php

require_once 'achievement_helper.php';

function check_user_achievements(PDO $pdo, int $user_id): void
{
    /*
    |--------------------------------------------------------------------------
    | KREIRANE AVANTURE
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM adventures
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);

    $count = (int)$stmt->fetchColumn();

    if ($count >= 1)  award_achievement($pdo,$user_id,'ADV_1');
    if ($count >= 5)  award_achievement($pdo,$user_id,'ADV_5');
    if ($count >= 10) award_achievement($pdo,$user_id,'ADV_10');
    if ($count >= 25) award_achievement($pdo,$user_id,'ADV_25');
    if ($count >= 50) award_achievement($pdo,$user_id,'ADV_50');


    /*
    |--------------------------------------------------------------------------
    | ZAVRŠENE AVANTURE
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM adventures
        WHERE user_id = ?
        AND status='completed'
    ");
    $stmt->execute([$user_id]);

    $count = (int)$stmt->fetchColumn();

    if ($count >= 1)  award_achievement($pdo,$user_id,'COMP_1');
    if ($count >= 5)  award_achievement($pdo,$user_id,'COMP_5');
    if ($count >= 10) award_achievement($pdo,$user_id,'COMP_10');
    if ($count >= 25) award_achievement($pdo,$user_id,'COMP_25');
    if ($count >= 50) award_achievement($pdo,$user_id,'COMP_50');


    /*
    |--------------------------------------------------------------------------
    | PRIJATELJSTVA
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM friendships
        WHERE user_one = ?
        OR user_two = ?
    ");
    $stmt->execute([$user_id, $user_id]);

    $count = (int)$stmt->fetchColumn();

    if ($count >= 1)  award_achievement($pdo,$user_id,'FRIEND_1');
    if ($count >= 5)  award_achievement($pdo,$user_id,'FRIEND_5');
    if ($count >= 10) award_achievement($pdo,$user_id,'FRIEND_10');
    if ($count >= 25) award_achievement($pdo,$user_id,'FRIEND_25');
    if ($count >= 50) award_achievement($pdo,$user_id,'FRIEND_50');


    /*
    |--------------------------------------------------------------------------
    | SPAŠENE AVANTURE
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM saved_adventures
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);

    $count = (int)$stmt->fetchColumn();

    if ($count >= 1)  award_achievement($pdo,$user_id,'SAVE_1');
    if ($count >= 10) award_achievement($pdo,$user_id,'SAVE_10');
    if ($count >= 25) award_achievement($pdo,$user_id,'SAVE_25');
    if ($count >= 50) award_achievement($pdo,$user_id,'SAVE_50');


    /*
    |--------------------------------------------------------------------------
    | SUDJELOVANJA U TUĐIM AVANTURAMA
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM adventure_participants
        WHERE user_id = ?
        AND role = 'participant'
    ");
    $stmt->execute([$user_id]);

    $count = (int)$stmt->fetchColumn();

    if ($count >= 1)  award_achievement($pdo,$user_id,'JOIN_1');
    if ($count >= 5)  award_achievement($pdo,$user_id,'JOIN_5');
    if ($count >= 10) award_achievement($pdo,$user_id,'JOIN_10');
    if ($count >= 25) award_achievement($pdo,$user_id,'JOIN_25');


    /*
    |--------------------------------------------------------------------------
    | PRIJEĐENI KILOMETRI
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(distance_km),0)
        FROM adventures
        WHERE user_id = ?
        AND status='completed'
    ");
    $stmt->execute([$user_id]);

    $km = (int)$stmt->fetchColumn();

    if ($km >= 100)   award_achievement($pdo,$user_id,'KM_100');
    if ($km >= 500)   award_achievement($pdo,$user_id,'KM_500');
    if ($km >= 1000)  award_achievement($pdo,$user_id,'KM_1000');
    if ($km >= 5000)  award_achievement($pdo,$user_id,'KM_5000');
    if ($km >= 10000) award_achievement($pdo,$user_id,'KM_10000');


    /*
    |--------------------------------------------------------------------------
    | POSJEĆENI GRADOVI
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT destination)
        FROM adventures
        WHERE user_id = ?
        AND destination IS NOT NULL
        AND destination <> ''
    ");
    $stmt->execute([$user_id]);

    $cities = (int)$stmt->fetchColumn();

    if ($cities >= 1)  award_achievement($pdo,$user_id,'CITY_1');
    if ($cities >= 5)  award_achievement($pdo,$user_id,'CITY_5');
    if ($cities >= 10) award_achievement($pdo,$user_id,'CITY_10');
    if ($cities >= 25) award_achievement($pdo,$user_id,'CITY_25');
    if ($cities >= 50) award_achievement($pdo,$user_id,'CITY_50');
    /*
    |--------------------------------------------------------------------------
    | PUTOVANJA S DRUGIMA
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT ap.adventure_id)
        FROM adventure_participants ap
        INNER JOIN adventures a ON a.id = ap.adventure_id
        WHERE a.user_id = ?
    ");
    $stmt->execute([$user_id]);

    $groupTrips = (int)$stmt->fetchColumn();

    if ($groupTrips >= 1)  award_achievement($pdo,$user_id,'GROUP_1');
    if ($groupTrips >= 5)  award_achievement($pdo,$user_id,'GROUP_5');
    if ($groupTrips >= 10) award_achievement($pdo,$user_id,'GROUP_10');


    /*
    |--------------------------------------------------------------------------
    | XP LEVELI
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT total_xp
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);

    $xp = (int)$stmt->fetchColumn();

    if ($xp >= 100)   award_achievement($pdo,$user_id,'XP_100');
    if ($xp >= 500)   award_achievement($pdo,$user_id,'XP_500');
    if ($xp >= 1000)  award_achievement($pdo,$user_id,'XP_1000');
    if ($xp >= 5000)  award_achievement($pdo,$user_id,'XP_5000');
    if ($xp >= 10000) award_achievement($pdo,$user_id,'XP_10000');
}