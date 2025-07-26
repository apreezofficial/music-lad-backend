<?php
require __DIR__ . '/db.php';

// --- CORS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- ONLY POST ALLOWED ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

// --- READ JSON INPUT ---
$input = json_decode(file_get_contents("php://input"), true);

$username = trim($input['username'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// --- VALIDATION ---
if (!$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required"]);
    exit();
}

// --- HASH PASSWORD ---
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);

    echo json_encode(["message" => "User registered successfully"]);
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'unique')) {
        http_response_code(409);
        echo json_encode(["error" => "Username or email already exists"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
    }
}