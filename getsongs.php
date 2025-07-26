<?php
require __DIR__ . '/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Optional query params
$artist = $_GET['artist'] ?? null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

try {
    if ($artist) {
        $stmt = $pdo->prepare("
            SELECT music.*, artists.name AS artist_name, albums.title AS album_title
            FROM music 
            LEFT JOIN artists ON music.artist_id = artists.id 
            LEFT JOIN albums ON music.album_id = albums.id 
            WHERE artists.name ILIKE ? 
            ORDER BY music.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute(["%$artist%", $limit]);
    } else {
        $stmt = $pdo->prepare("
            SELECT music.*, artists.name AS artist_name, albums.title AS album_title
            FROM music 
            LEFT JOIN artists ON music.artist_id = artists.id 
            LEFT JOIN albums ON music.album_id = albums.id 
            ORDER BY music.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
    }

    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["songs" => $songs]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}