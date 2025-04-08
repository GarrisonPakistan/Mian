<?php
// Database configuration
$servername = "localhost";  // Database host (change if needed)
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "your_database";  // Database name

// Enable error reporting for debugging (remove in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create the database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        throw new Exception("Database connection failed.");
    }
} catch (Exception $e) {
    // Log the error to the server logs
    error_log("Database connection error: " . $e->getMessage());
    die("An error occurred while connecting to the database. Please try again later.");
}
?>
