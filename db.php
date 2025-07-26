<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Fetch environment variables
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// DSN (with SSL optional)
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=prefer";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected successfully to remote PostgreSQL!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
