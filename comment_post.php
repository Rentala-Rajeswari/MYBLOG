<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
session_start();
include 'db.php';
include('db_connection.php'); 
if ($conn === null) {
    die("Database connection not established.");
}
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$post_id = $_GET['id'];
$current_user = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO comments (content, post_id, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $content, $post_id, $current_user);

    if ($stmt->execute()) {
        header("Location: view_post.php?id=$post_id");
    } else {
        echo "Error adding comment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comment on Post - My Blog</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="comment-form-container">
    <h1>Comment on Post</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="content">Your Comment:</label>
            <textarea name="content" id="content" required></textarea>
        </div>
        <button type="submit" class="submit-btn">Submit Comment</button>
    </form>
</div>

</body>
</html>
