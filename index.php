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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Password Manager</title>
        <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <button class= "refresh-btn" onclick= "window.location.reload()">Refresh Page</button>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <div class= "form-container">
        <h2>Search Passwords</h2>
        <form method="POST">
            <input type="hidden" name="action" value="search">
            <input type="text" name="searchTerm" placeholder="Enter search term" required>
            <button type="submit">Search</button>
        </form>
    </div>
    <div class= "form-container">
        <h2>Add New Password</h2>
        <form method="POST">
            <input type="hidden" name="action" value="insert">
            <input type="text" name="websiteName" placeholder="Website Name" required>
            <input type="url" name="websiteUrl" placeholder="Website URL" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <textarea name="comment" placeholder="Comments"></textarea>
            <button type="submit">Add Password</button>
        </form>
    </div>
    <div class= "form-container">
        <h2>Update Password</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="text" name="websiteName" placeholder="Website Name" required>
            <input type="password" name="newPassword" placeholder="New Password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
    <div class= "form-container">
        <h2>Delete Password</h2>
        <form method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="text" name="websiteName" placeholder="Website Name" required>
            <button type="submit">Delete Password</button>
        </form>
    </div>
    <?php if (isset($results)): ?>
        <div class="results">
            <h2>Search Results</h2>
            <?php if (empty($results)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Website</th>
                        <th>URL</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Comment</th>
                        <th>Created</th>
                    </tr>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['website_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['website_url']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['password']); ?></td>
                            <td><?php echo htmlspecialchars($row['comment']); ?></td>
                            <td><?php echo htmlspecialchars($row['create_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
