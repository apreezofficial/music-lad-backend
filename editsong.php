<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["error" => "Only PUT method allowed"]);
    exit();
}

// --- Get raw input ---
$data = json_decode(file_get_contents("php://input"), true);
$song_id   = $data['id'] ?? null;
$title     = trim($data['title'] ?? '');
$genre     = trim($data['genre'] ?? '');
$album_id  = $data['album_id'] ?? null;
$cover_url = trim($data['cover_url'] ?? '');
$file_url  = trim($data['file_url'] ?? '');
$duration  = $data['duration'] ?? null;

if (!$song_id) {
    http_response_code(400);
    echo json_encode(["error" => "Song ID is required"]);
    exit();
}

// --- Verify ownership ---
$stmt = $pdo->prepare("SELECT * FROM music WHERE id = ? AND artist_id = ?");
$stmt->execute([$song_id, $user['id']]);
$song = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$song) {
    http_response_code(403);
    echo json_encode(["error" => "You do not own this song or it doesn't exist"]);
    exit();
}

// --- Build dynamic query ---
$fields = [];
$params = [];

if ($title) { $fields[] = "title = ?"; $params[] = $title; }
if ($genre) { $fields[] = "genre = ?"; $params[] = $genre; }
if (!is_null($album_id)) { $fields[] = "album_id = ?"; $params[] = $album_id; }
if ($cover_url) { $fields[] = "cover_url = ?"; $params[] = $cover_url; }
if ($file_url) { $fields[] = "file_url = ?"; $params[] = $file_url; }
if (!is_null($duration)) { $fields[] = "duration = ?"; $params[] = $duration; }

if (empty($fields)) {
    echo json_encode(["message" => "No changes made"]);
    exit();
}

$params[] = $song_id;

$query = "UPDATE music SET " . implode(", ", $fields) . ", created_at = created_at WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute($params);

// --- Return updated record ---
$stmt = $pdo->prepare("
    SELECT m.*, a.name AS artist_name, al.title AS album_title
    FROM music m
    LEFT JOIN artists a ON m.artist_id = a.id
    LEFT JOIN albums al ON m.album_id = al.id
    WHERE m.id = ?
");
$stmt->execute([$song_id]);
$updated_song = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "message" => "Song updated successfully",
    "song" => $updated_song
]);