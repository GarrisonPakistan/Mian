<?php
include 'database.php';  // Include the database connection

// Start session to store user data
session_start();

// Check if login form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Sanitize and retrieve form data
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Prepare the SQL query using prepared statements
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("s", $username);

            // Execute the prepared statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if user exists
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user['id'];

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                echo "Invalid username or password.";
            }

            // Close the statement
            $stmt->close();
        } else {
            // Log error to server logs
            error_log("Database error: " . $conn->error);
            echo "An error occurred. Please try again later.";
        }
    } catch (Exception $e) {
        // Log exception to server logs
        error_log("Exception: " . $e->getMessage());
        echo "An unexpected error occurred. Please try again later.";
    }
} else {
    echo "Invalid request method.";
}
?>
