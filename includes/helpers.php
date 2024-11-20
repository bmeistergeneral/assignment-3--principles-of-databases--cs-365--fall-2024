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

function insertPassword($websiteName, $websiteUrl, $email, $username, $password, $comment) {
    $pdo = connectDB();

    try {
        $pdo->beginTransaction();

        // Website insert
        $statement = $pdo->prepare("INSERT IGNORE INTO Websites (website_name, website_url) VALUES (?, ?)");
        $statement->execute([$websiteName, $websiteUrl]);

        $websiteId = $pdo->lastInsertId();
        if (!$websiteId) {
            $statement = $pdo->prepare("SELECT website_id FROM Websites WHERE website_name = ?");
            $statement->execute([$websiteName]);
            $websiteId = $statement->fetchColumn();
        }

        // User insert
        $statement = $pdo->prepare("INSERT IGNORE INTO Users (username, email, first_name, last_name)
                              VALUES (?, ?, '', '')");
        $statement->execute([$username, $email]);

        $userId = $pdo->lastInsertId();
        if (!$userId) {
            $statement = $pdo->prepare("SELECT user_id FROM Users WHERE username = ?");
            $statement->execute([$username]);
            $userId = $statement->fetchColumn();
        }

        // Password insert
        $statement = $pdo->prepare("INSERT INTO Passwords (user_id, website_id, enc_password, comment)
                              VALUES (?, ?, ?, ?)");
        $statement->execute([$userId, $websiteId, $password, $comment]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function updatePassword($websiteName, $newPassword) {
    $pdo = connectDB();
    $query = "UPDATE Passwords p
              JOIN Websites w ON p.website_id = w.website_id
              SET p.enc_password = ?
              WHERE w.website_name = ?";

    $statement = $pdo->prepare($query);
    return $statement->execute([$newPassword, $websiteName]);
}

function deletePassword($websiteName) {
    $pdo = connectDB();
    $query = "DELETE p FROM Passwords p
              JOIN Websites w ON p.website_id = w.website_id
              WHERE w.website_name = ?";

    $statement = $pdo->prepare($query);
    return $statement->execute([$websiteName]);
}
?>

