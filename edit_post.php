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

// Fetch post details
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $current_user);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $update_stmt->bind_param("ssii", $title, $content, $post_id, $current_user);
    
    if ($update_stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error updating post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post - My Blog</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container form-container">
    <h1>Edit Post</h1>
    <form action="edit_post.php?id=<?php echo $post_id; ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        
        <button type="submit" class="submit-btn">Update Post</button>
    </form>
</div>


    
</body>
</html>
