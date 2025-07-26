<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

// --- Check user token ---
$user = authenticate();

// --- Get request data ---
$data = json_decode(file_get_contents("php://input"), true);
$title = trim($data['title'] ?? '');
$genre = trim($data['genre'] ?? '');
$file_url = trim($data['file_url'] ?? '');
$cover_url = trim($data['cover_url'] ?? '');
$album_id = $data['album_id'] ?? null;

if (!$title || !$file_url) {
    http_response_code(400);
    echo json_encode(["error" => "Title and file_url are required"]);
    exit();
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO music (title, artist_id, album_id, genre, file_url, cover_url, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        RETURNING *
    ");
    $stmt->execute([$title, $user['id'], $album_id, $genre, $file_url, $cover_url]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "message" => "Song added successfully",
        "song" => $song
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}