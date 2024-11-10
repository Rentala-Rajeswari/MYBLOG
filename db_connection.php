<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Show all errors except warnings and notices
ini_set('display_errors', '0'); // Do not display errors
?>

<?php
$servername = "localhost";
$username = "root";  // Default username in XAMPP is 'root'
$password = "";      // Default password in XAMPP is empty
$dbname = "blog";    // Name of your database

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

