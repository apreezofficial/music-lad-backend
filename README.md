# Rhythmic Realms API: A Backend for Music Management 🎵

This robust PHP application serves as the foundational backend for a comprehensive music management system. It provides secure user authentication, a well-structured data model for artists, albums, and music tracks, and a flexible framework for creating and managing user playlists. Crafted with an emphasis on clean architecture and database integrity, this API is designed to power dynamic musical platforms and demonstrates strong backend development principles.

## Usage

Getting this API up and running is straightforward. Follow these steps to set up your environment, initialize the database, and interact with the authentication endpoints.

### 1. Environment Configuration

Before running the application, you'll need to set up your database credentials and other sensitive information in a `.env` file. Create a file named `.env` in the root directory of the project, next to `composer.json`, and populate it with your PostgreSQL database connection details:

```dotenv
DB_HOST=your_database_host
DB_PORT=your_database_port
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASSWORD=your_database_password
```

Ensure these details accurately reflect your PostgreSQL database instance.

### 2. Install Dependencies

This project relies on `vlucas/phpdotenv` for environment variable management. Install it using Composer:

```bash
composer install
```

### 3. Initialize Database Schema

The database tables required for this API are defined in `query.php`. To create these tables in your configured PostgreSQL database, simply run this script from your terminal:

```bash
php query.php
```

You should see a "Connected successfully to remote PostgreSQL!" message followed by "All tables created successfully!" if everything goes well. This script will set up tables for `users`, `artists`, `albums`, `music`, `playlists`, and `playlist_music`, along with an `token` column on the `users` table for session management.

### 4. API Endpoints

Once the database is set up, you can interact with the API endpoints.

#### User Registration (Signup)

To create a new user account, send a `POST` request to `signup.php` with a JSON payload containing `username`, `email`, and `password`.

**Endpoint:** `/signup.php`
**Method:** `POST`
**Content-Type:** `application/json`

**Example Request (using cURL):**

```bash
curl -X POST \
     -H "Content-Type: application/json" \
     -d '{
           "username": "your_new_username",
           "email": "your_email@example.com",
           "password": "your_secure_password"
         }' \
     http://localhost/signup.php
```

**Example Successful Response:**

```json
{
  "message": "User registered successfully"
}
```

**Example Error Response (User Exists):**

```json
{
  "error": "Username or email already exists"
}
```

#### User Login

To log in and obtain an authentication token, send a `POST` request to `login.php` with a JSON payload containing `email` and `password`.

**Endpoint:** `/login.php`
**Method:** `POST`
**Content-Type:** `application/json`

**Example Request (using cURL):**

```bash
curl -X POST \
     -H "Content-Type: application/json" \
     -d '{
           "email": "your_email@example.com",
           "password": "your_secure_password"
         }' \
     http://localhost/login.php
```

**Example Successful Response:**

```json
{
  "message": "Login successful",
  "token": "your_generated_secure_token",
  "user": {
    "id": 1,
    "username": "your_username"
  }
}
```

**Example Error Response (Invalid Credentials):**

```json
{
  "error": "Invalid credentials"
}
```

## Features

This project showcases several key functionalities and architectural considerations:

*   **User Authentication System**: Implements secure user registration and login flows, including password hashing with `password_hash` and secure token generation for session management.
*   **Environment Variable Management**: Utilizes `vlucas/phpdotenv` for securely loading configuration variables, keeping sensitive information out of the codebase.
*   **PostgreSQL Database Integration**: Connects to a PostgreSQL database using PHP Data Objects (PDO), demonstrating robust, parameterized queries to prevent SQL injection.
*   **Comprehensive Data Modeling**: Establishes a well-normalized database schema for `users`, `artists`, `albums`, `music`, and `playlists`, including many-to-many relationships for music within playlists.
*   **CORS Support**: Configures Cross-Origin Resource Sharing (CORS) headers to allow frontend applications from different origins to interact with the API.
*   **Error Handling**: Provides clear JSON-based error responses for various scenarios, such as missing inputs, invalid credentials, and database issues.

## Technologies Used

This project leverages the following technologies:

| Technology         | Description                                                      | Link                                                 |
| :----------------- | :--------------------------------------------------------------- | :--------------------------------------------------- |
| **PHP 8.3+**       | Core server-side scripting language                              | [php.net](https://www.php.net/)                      |
| **PostgreSQL**     | Robust, open-source relational database                          | [postgresql.org](https://www.postgresql.org/)        |
| **PDO**            | PHP Data Objects for database access                             | [php.net/manual/en/book.pdo.php](https://www.php.net/manual/en/book.pdo.php) |
| **Dotenv**         | Library for loading environment variables from `.env` files      | [github.com/vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) |

## License

The licensing for this project is currently not explicitly defined within the repository. Please contact the author for details.

## Author Info

Connect with me and see more of my work!

*   **LinkedIn**: [Your LinkedIn Profile](https://www.linkedin.com/in/your_username)
*   **Twitter**: [Your Twitter Profile](https://twitter.com/your_username)
*   **Portfolio**: [Your Portfolio Website](https://www.yourportfolio.com)

---

[![Built with PHP](https://img.shields.io/badge/Built%20with-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database - PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL-336791?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Status: In Progress](https://img.shields.io/badge/Status-In%20Progress-blue?style=for-the-badge)](https://github.com/your-username/your-repo-name/commits)

[![Readme was generated by Dokugen](https://img.shields.io/badge/Readme%20was%20generated%20by-Dokugen-brightgreen)](https://www.npmjs.com/package/dokugen)