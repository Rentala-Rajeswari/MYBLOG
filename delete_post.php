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

// Delete post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $current_user);

if ($stmt->execute()) {
    header("Location: dashboard.php");
} else {
    echo "Error deleting post.";
}
?>
