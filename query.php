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
            token TEXT,
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
            cover_url TEXT,
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
            cover_url TEXT,
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

    // 7. LIKES TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS likes (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            music_id INT REFERENCES music(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (user_id, music_id)
        );
    ");

    // 8. COMMENTS TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS comments (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            music_id INT REFERENCES music(id) ON DELETE CASCADE,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 9. FOLLOWERS TABLE
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS followers (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            artist_id INT REFERENCES artists(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (user_id, artist_id)
        );
    ");

    // 10. PLAY HISTORY
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS play_history (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            music_id INT REFERENCES music(id) ON DELETE CASCADE,
            played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo "All tables created successfully with extras!";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}