<?php
// notifications_helper.php

function create_notification(PDO $pdo, int $user_id, string $type, ?int $from_user_id = null, ?int $reference_id = null): void
{
    $stmt = $pdo->prepare("
        INSERT INTO notifications (user_id, type, from_user_id, reference_id)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $type, $from_user_id, $reference_id]);
}

function get_unread_count(PDO $pdo, int $user_id): int
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM notifications
        WHERE user_id = ? AND is_read = 0
    ");
    $stmt->execute([$user_id]);
    return (int) $stmt->fetchColumn();
}

function get_notifications(PDO $pdo, int $user_id, int $limit = 20): array
{
    $stmt = $pdo->prepare("
        SELECT n.*, 
               u.korisnicko_ime, u.ime, u.prezime, u.profilna_slika
        FROM notifications n
        LEFT JOIN users u ON u.id = n.from_user_id
        WHERE n.user_id = ?
        ORDER BY n.created_at DESC
        LIMIT " . (int)$limit . "
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function mark_all_read(PDO $pdo, int $user_id): void
{
    $stmt = $pdo->prepare("
        UPDATE notifications SET is_read = 1
        WHERE user_id = ? AND is_read = 0
    ");
    $stmt->execute([$user_id]);
}