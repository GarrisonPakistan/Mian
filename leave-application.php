<?php
// Include database connection
include('database.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start session to access employee ID
        session_start();
        if (!isset($_SESSION['employee_id'])) {
            echo "Unauthorized access. Please log in.";
            exit;
        }

        $employeeId = $_SESSION['employee_id'];

        // Sanitize input data
        $leaveType = htmlspecialchars($_POST['leave-type']);
        $leaveStartDate = htmlspecialchars($_POST['leave-start-date']);
        $leaveEndDate = htmlspecialchars($_POST['leave-end-date']);
        $leaveReason = htmlspecialchars($_POST['leave-reason']);

        // Validate required fields
        if (empty($leaveType) || empty($leaveStartDate) || empty($leaveEndDate) || empty($leaveReason)) {
            echo "All fields are required.";
            exit;
        }

        // Prepare the SQL query using prepared statements
        $query = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("issss", $employeeId, $leaveType, $leaveStartDate, $leaveEndDate, $leaveReason);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Leave request submitted successfully.";
            } else {
                // Log error to server logs
                error_log("Database error: " . $stmt->error);
                echo "An error occurred while submitting your leave request. Please try again later.";
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
