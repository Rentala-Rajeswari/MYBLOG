<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
session_start();
include 'db.php'; // Database connection file

// Database connection
$servername = "localhost";
$username = "root";  // Default MySQL username in XAMPP
$password = "";      // Default password is empty in XAMPP
$dbname = "blog";    // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user data based on the username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // If password matches, set session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        // If login fails, redirect back with an error message
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.html");
        exit();
    }
}
?>
