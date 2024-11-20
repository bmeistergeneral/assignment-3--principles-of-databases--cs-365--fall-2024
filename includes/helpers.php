<?php

require_once 'config.php';

function connectDB() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function searchPasswords($searchTerm) {
    $pdo = connectDB();
    $query = "SELECT w.website_name, w.website_url, u.username, u.email,
              p.enc_password as password, p.comment, p.create_time
              FROM Passwords p
              JOIN Users u ON p.user_id = u.user_id
              JOIN Websites w ON p.website_id = w.website_id
              WHERE w.website_name LIKE ? OR w.website_url LIKE ?
              OR u.username LIKE ? OR u.email LIKE ?";
    $statement = $pdo->prepare($query);
    $searchPattern = "%$searchTerm%";
    $statement->execute([$searchPattern, $searchPattern, $searchPattern, $searchPattern]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
