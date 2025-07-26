<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.title, m.genre, m.duration, m.file_url, m.cover_url, m.created_at,
               a.name AS artist_name,
               al.title AS album_title
        FROM music m
        LEFT JOIN artists a ON m.artist_id = a.id
        LEFT JOIN albums al ON m.album_id = al.id
        WHERE m.artist_id = ?
        ORDER BY m.created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["songs" => $songs]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch songs"]);
}