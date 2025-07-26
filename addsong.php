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

// --- Handle form data ---
$title = trim($_POST['title'] ?? '');
$genre = trim($_POST['genre'] ?? '');
$album_id = $_POST['album_id'] ?? null;

if (!$title || !isset($_FILES['song_file'])) {
    http_response_code(400);
    echo json_encode(["error" => "Title and song_file are required"]);
    exit();
}

// --- File handling ---
$uploadDir = __DIR__ . '/assets/records/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$songTmp = $_FILES['song_file']['tmp_name'];
$songExt = pathinfo($_FILES['song_file']['name'], PATHINFO_EXTENSION);
$songName = uniqid('song_', true) . '.' . $songExt;
$songPath = $uploadDir . $songName;
move_uploaded_file($songTmp, $songPath);

// Cover file optional
$cover_url = null;
if (isset($_FILES['cover_file'])) {
    $coverTmp = $_FILES['cover_file']['tmp_name'];
    $coverExt = pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION);
    $coverName = uniqid('cover_', true) . '.' . $coverExt;
    move_uploaded_file($coverTmp, $uploadDir . $coverName);
    $cover_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/assets/records/' . $coverName;
}

// Final URLs
$file_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/assets/records/' . $songName;

try {
    $stmt = $pdo->prepare("
        INSERT INTO music (title, artist_id, album_id, genre, file_url, cover_url, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        RETURNING *
    ");
    $stmt->execute([$title, $user['id'], $album_id, $genre, $file_url, $cover_url]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "message" => "Song uploaded and saved successfully",
        "song" => $song
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}