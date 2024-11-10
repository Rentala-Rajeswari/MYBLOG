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
    $confirm_password = $_POST['confirm_password'];

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: register.html");
        exit();
    }

    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username already exists";
        header("Location: register.html");
        exit();
    }

    // Hash the password and insert the new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. You can now log in.";
        header("Location: index.html");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: register.html");
        exit();
    }
}
?>
