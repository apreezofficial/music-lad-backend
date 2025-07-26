# Harmony Hub API: A Comprehensive Music Management Backend 🎵

Welcome to the **Harmony Hub API**, a robust and scalable backend designed to power your next digital music ecosystem. This project provides a complete set of RESTful endpoints for user authentication, music content management, and rich data interaction, built with a focus on efficiency and data integrity.

From managing user accounts to handling song uploads and creating intricate data models for artists, albums, playlists, and more, Harmony Hub API lays the groundwork for a sophisticated music platform. It's crafted to serve as the core engine for various front-end applications, enabling seamless interaction with a structured music database.

## 🚀 Usage

This project functions as a backend API. To interact with it, you'll send HTTP requests to its various endpoints. Below are detailed instructions and examples for key operations.

### Initial Setup

Before making API calls, ensure your environment is set up:

1.  **Environment Configuration**: Create a `.env` file in the project root based on the `db.php` requirements. This file should define your PostgreSQL database connection details:
    ```
    DB_HOST=your_database_host
    DB_PORT=5432
    DB_NAME=your_database_name
    DB_USER=your_database_user
    DB_PASSWORD=your_database_password
    ```
2.  **Database Schema Creation**: Execute the `query.php` file once to set up all necessary database tables and their relationships. This script will create tables for users, artists, albums, music, playlists, and more.
    ```bash
    php query.php
    ```
    You should see a "Connected successfully to remote PostgreSQL!" message from `db.php` if your `.env` is correct, followed by "All tables created successfully with extras!" from `query.php`.

### API Endpoints

All API endpoints expect and return JSON, unless otherwise specified (e.g., file uploads). Authentication is handled via a Bearer token in the `Authorization` header for protected routes.

#### 🔐 User Authentication

*   **Sign Up a New User**
    *   `POST /signup.php`
    *   **Body (JSON)**:
        ```json
        {
            "username": "newuser",
            "email": "user@example.com",
            "password": "strongpassword"
        }
        ```
    *   **Example `curl` Request**:
        ```bash
        curl -X POST -H "Content-Type: application/json" -d '{"username": "johndoe", "email": "john@example.com", "password": "SecurePassword123"}' http://localhost/signup.php
        ```

*   **Log In an Existing User**
    *   `POST /login.php`
    *   **Body (JSON)**:
        ```json
        {
            "email": "user@example.com",
            "password": "strongpassword"
        }
        ```
    *   **Example `curl` Request**:
        ```bash
        curl -X POST -H "Content-Type: application/json" -d '{"email": "john@example.com", "password": "SecurePassword123"}' http://localhost/login.php
        ```
    *   **Response**: Returns a `token` for subsequent authenticated requests.

*   **Log Out a User**
    *   `POST /logout.php`
    *   **Headers**: `Authorization: Bearer <your_token>`
    *   **Example `curl` Request**:
        ```bash
        curl -X POST -H "Authorization: Bearer YOUR_AUTH_TOKEN" http://localhost/logout.php
        ```

#### 🎶 Music Management

*   **Add a New Song (Requires Authentication)**
    *   `POST /addsong.php`
    *   **Body (multipart/form-data)**:
        *   `title` (string, required)
        *   `genre` (string, optional)
        *   `album_id` (int, optional)
        *   `song_file` (file, required): The audio file to upload.
        *   `cover_file` (file, optional): The cover image file.
    *   **Headers**: `Authorization: Bearer <your_token>`
    *   **Example `curl` Request**:
        ```bash
        curl -X POST -H "Authorization: Bearer YOUR_AUTH_TOKEN" \
             -F "title=My New Song" \
             -F "genre=Pop" \
             -F "song_file=@/path/to/your/song.mp3" \
             -F "cover_file=@/path/to/your/cover.jpg" \
             http://localhost/addsong.php
        ```

*   **Edit an Existing Song (Requires Authentication & Ownership)**
    *   `PUT /editsong.php`
    *   **Body (JSON)**:
        ```json
        {
            "id": 1,
            "title": "Updated Song Title",
            "genre": "Rock",
            "album_id": 5,
            "cover_url": "http://example.com/new_cover.jpg",
            "file_url": "http://example.com/new_song.mp3",
            "duration": 240
        }
        ```
    *   **Headers**: `Authorization: Bearer <your_token>`, `Content-Type: application/json`
    *   **Example `curl` Request**:
        ```bash
        curl -X PUT -H "Authorization: Bearer YOUR_AUTH_TOKEN" \
             -H "Content-Type: application/json" \
             -d '{"id": 1, "title": "My Awesome Song", "genre": "Indie Pop"}' \
             http://localhost/editsong.php
        ```

*   **Get a Single Song by ID**
    *   `GET /getsong.php?id=<song_id>`
    *   **Example `curl` Request**:
        ```bash
        curl http://localhost/getsong.php?id=1
        ```

*   **Get a List of Songs**
    *   `GET /getsongs.php`
    *   **Optional Query Parameters**:
        *   `artist` (string): Filter by artist name (case-insensitive).
        *   `limit` (int): Limit the number of results (default: 50).
    *   **Example `curl` Requests**:
        ```bash
        curl http://localhost/getsongs.php
        curl http://localhost/getsongs.php?artist=Queen&limit=10
        ```

*   **Get Songs Uploaded by Current User (Requires Authentication)**
    *   `GET /getusersongs.php`
    *   **Headers**: `Authorization: Bearer <your_token>`
    *   **Example `curl` Request**:
        ```bash
        curl -H "Authorization: Bearer YOUR_AUTH_TOKEN" http://localhost/getusersongs.php
        ```

## ✨ Features

*   **User Authentication**: Secure signup, login, and logout functionalities with token-based authorization.
*   **Music Content Management**: Comprehensive CRUD operations for songs, including title, genre, artist, album, and file/cover URLs.
*   **Secure File Uploads**: Handles multi-part form data for audio files and cover images, storing them securely on the server.
*   **Dynamic Data Updates**: Utilizes a dynamic query builder for efficient song updates, modifying only specified fields.
*   **Detailed Data Models**: Robust PostgreSQL schema supporting users, artists, albums, music tracks, playlists, likes, comments, artist following, and play history.
*   **Flexible Song Retrieval**: Fetch individual songs or lists, with options to filter by artist and limit results.
*   **User-Specific Content**: Retrieve songs uploaded specifically by the authenticated user.
*   **Cross-Origin Resource Sharing (CORS)**: Configured to allow requests from various front-end origins, making integration straightforward.
*   **Environment Variable Management**: Securely loads sensitive configuration details (e.g., database credentials) using `phpdotenv`.
*   **RESTful API Design**: Clear and consistent use of HTTP methods (GET, POST, PUT, OPTIONS) for distinct operations.

## 🛠️ Technologies Used

This project is built using a modern PHP stack, leveraging powerful tools and databases to ensure reliability and performance.

| Category          | Technology                                                                                                                                                           | Description                                                      |
| :---------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :--------------------------------------------------------------- |
| **Backend**       | [PHP 8.3+](https://www.php.net/)                                                                                                                                     | The core programming language.                                   |
| **Database**      | [PostgreSQL](https://www.postgresql.org/)                                                                                                                            | A powerful, open-source object-relational database system.       |
| **DB Access**     | [PDO](https://www.php.net/manual/en/book.pdo.php)                                                                                                                    | PHP Data Objects for database access, ensuring security and flexibility. |
| **Dependencies**  | [Composer](https://getcomposer.org/)                                                                                                                                 | A dependency manager for PHP.                                    |
| **Environments**  | [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)                                                                                                              | Manages environment variables from a `.env` file.                |

## 👤 Author

Feel free to connect with me!

*   **Your Name**: [Your Name Here]
*   **LinkedIn**: [https://linkedin.com/in/yourprofile](https://linkedin.com/in/yourprofile)
*   **Twitter**: [https://twitter.com/yourhandle](https://twitter.com/yourhandle)

## ⚖️ License

All Rights Reserved. No specific open-source license has been applied to this project.

---

![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![REST API](https://img.shields.io/badge/API-RESTful-brightgreen?style=for-the-badge&logo=rest&logoColor=white)
![PDO](https://img.shields.io/badge/Database%20Access-PDO-orange?style=for-the-badge)

---

[![Readme was generated by Dokugen](https://img.shields.io/badge/Readme%20was%20generated%20by-Dokugen-brightgreen)](https://www.npmjs.com/package/dokugen)