<?php
require_once "includes/helpers.php";

$message = '';
$results = null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';

        switch($action) {
            case 'search':
                $results = searchPasswords($_POST['searchTerm']);
                break;
            case 'insert':
                insertPassword(
                    $_POST['websiteName'],
                    $_POST['websiteUrl'],
                    $_POST['email'],
                    $_POST['username'],
                    $_POST['password'],
                    $_POST['comment'],
                );
                $message = "Password added successfully!";
                break;
            case 'update':
                updatePassword($_POST['websiteName'], $_POST['newPassword']);
                $message = "Password updated successfully!";
                break;
            case 'delete':
                deletePassword($_POST['websiteName']);
                $message = "Password deleted successfully!";
                break;
        }
    } catch(Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
