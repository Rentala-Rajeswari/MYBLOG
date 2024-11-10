<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
session_start();
include 'db.php';

include('db_connection.php'); // Ensure the connection file is included

// Check if the connection was successful
if ($conn === null) {
    die("Connection failed: Unable to connect to the database.");
}

$post_id = $_GET['id'];

// Fetch the post
$stmt = $conn->prepare("SELECT posts.title, posts.content, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Fetch comments
$comment_stmt = $conn->prepare("SELECT comments.content, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ?");
$comment_stmt->bind_param("i", $post_id);
$comment_stmt->execute();
$comments = $comment_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post - My Blog</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container page-container view-post-container">
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="post-content"><?php echo htmlspecialchars($post['content']); ?></p>
    <p class="post-author"><small>Posted by <?php echo htmlspecialchars($post['username']); ?></small></p>

    <h2>Comments</h2>
    <div class="comments-section">
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['content']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

    
</body>
</html>
