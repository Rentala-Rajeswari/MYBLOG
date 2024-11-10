<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
session_start();
include 'db.php'; // Database connection file

include('db_connection.php'); // Include the database connection

// Check if connection is successful
if ($conn === null) {
    die("Connection failed: Unable to connect to the database.");
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Fetch all posts from the database
$sql = "SELECT posts.id, posts.title, posts.content, posts.user_id, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

// Get current user's ID
$current_user = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="create_post.php">Create Post</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="welcome-message">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        </div>
    </div>
        
            
    </header>

    <main class="container dashboard">
        <h1>Dashboard</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <?php
                    // Split the content by spaces into an array of words
                    $words = explode(' ', htmlspecialchars($post['content']));

                    // Get the first 25 words
                    $first25Words = array_slice($words, 0, 25);

                    // Join the first 25 words back into a string
                    $preview = implode(' ', $first25Words);

                    // Display the truncated content with an ellipsis
                    echo "<p>{$preview}...</p>";
                    ?>

                    <p><small>Posted by <?php echo htmlspecialchars($post['username']); ?></small></p>

                    <?php if ($post['user_id'] == $current_user): ?>
                        <!-- Options for the posts created by the logged-in user -->
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">View</a>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>">Update</a>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    <?php else: ?>
                        <!-- Options for posts created by other users -->
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">View</a>
                        <a href="comment_post.php?id=<?php echo $post['id']; ?>">Comment</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </main>
</body>
</html>
