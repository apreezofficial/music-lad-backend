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
    echo json_encode(["error" => "Only POST allowed"]);
    exit();
}

$user = authenticate();

$stmt = $pdo->prepare("UPDATE users SET token = NULL WHERE id = ?");
$stmt->execute([$user['id']]);

echo json_encode(["message" => "Logged out successfully"]);