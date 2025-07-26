<?php
require __DIR__ . '/db.php';

function authenticate() {
    global $pdo;

    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token required"]);
        exit();
    }

    $token = $matches[1];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid or expired token"]);
        exit();
    }

    return $user;
}