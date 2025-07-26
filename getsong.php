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

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(["error" => "Valid song ID is required"]);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT music.*, 
               artists.name AS artist_name, 
               albums.title AS album_title
        FROM music
        LEFT JOIN artists ON music.artist_id = artists.id
        LEFT JOIN albums ON music.album_id = albums.id
        WHERE music.id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$song) {
        http_response_code(404);
        echo json_encode(["error" => "Song not found"]);
        exit();
    }

    echo json_encode($song);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}