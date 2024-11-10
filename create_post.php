<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
session_start();
include 'db.php';
include('db_connection.php'); // Include the database connection

// Check if connection was successful
if ($conn === null) {
    die("Connection failed: Unable to connect to the database.");
}
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $content, $user_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - My Blog</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="create-post-form">
        <h1>Create New Post</h1>
        <form action="create_post.php" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="content">Content:</label>
            <textarea name="content" id="content" required></textarea>
            <button type="submit">Create Post</button>
        </form>
    </div>
   
</body>
</html>
