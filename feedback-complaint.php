<?php
// Include database connection
include('database.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Assuming employee ID is stored in session
        session_start();
        if (!isset($_SESSION['employee_id'])) {
            echo "Unauthorized access. Please log in.";
            exit;
        }

        $employeeId = $_SESSION['employee_id'];

        // Sanitize input data
        $feedbackType = htmlspecialchars($_POST['feedback-type']);
        $feedbackDescription = htmlspecialchars($_POST['feedback-description']);

        // Validate required fields
        if (empty($feedbackType) || empty($feedbackDescription)) {
            echo "All fields are required.";
            exit;
        }

        // Prepare the SQL query using prepared statements
        $query = "INSERT INTO feedback_complaints (employee_id, feedback_type, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sss", $employeeId, $feedbackType, $feedbackDescription);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Your feedback/complaint has been submitted successfully.";
            } else {
                // Log error to server logs
                error_log("Database error: " . $stmt->error);
                echo "An error occurred while submitting your feedback/complaint. Please try again later.";
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
