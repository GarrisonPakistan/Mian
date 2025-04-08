<?php
// Include the database connection
include 'database.php';

// Check if the form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Sanitize input data
        $name = htmlspecialchars($_POST['name']);
        $phone = htmlspecialchars($_POST['phone']);
        $cnic = htmlspecialchars($_POST['cnic']);

        // Validate required fields
        if (empty($name) || empty($phone) || empty($cnic)) {
            echo "All fields are required.";
            exit;
        }

        // Prepare the SQL query using prepared statements
        $query = "INSERT INTO clients (name, phone, cnic) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sss", $name, $phone, $cnic);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "New client added successfully!";
            } else {
                // Log error to server logs
                error_log("Database error: " . $stmt->error);
                echo "An error occurred while adding the client. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        } else {
            // Log error to server logs
            error_log("Database error: " . $conn->error);
            echo "An error occurred while preparing the statement.";
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
