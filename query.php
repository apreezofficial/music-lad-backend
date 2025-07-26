<?php
require __DIR__ . '/db.php';

try {
    // 1. USERS TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 2. ARTISTS TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS artists (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            bio TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 3. ALBUMS TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS albums (
            id SERIAL PRIMARY KEY,
            artist_id INT REFERENCES artists(id) ON DELETE CASCADE,
            title VARCHAR(100) NOT NULL,
            release_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 4. MUSIC TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS music (
            id SERIAL PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            artist_id INT REFERENCES artists(id) ON DELETE SET NULL,
            album_id INT REFERENCES albums(id) ON DELETE SET NULL,
            genre VARCHAR(50),
            duration INT,
            file_url TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 5. PLAYLIST TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS playlists (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 6. PLAYLIST_MUSIC (many-to-many)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS playlist_music (
            playlist_id INT REFERENCES playlists(id) ON DELETE CASCADE,
            music_id INT REFERENCES music(id) ON DELETE CASCADE,
            PRIMARY KEY (playlist_id, music_id)
        );
    ");

    echo "All tables created successfully!";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
