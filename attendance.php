<?php
// Start session to access employee ID
session_start();

// Include database connection
include('database.php');

// Check if employee ID is set in the session
if (!isset($_SESSION['employee_id'])) {
    echo "Unauthorized access. Please log in.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get employee ID from session
    $employeeId = $_SESSION['employee_id'];

    // Get form input values and sanitize them
    $attendanceDate = htmlspecialchars($_POST['attendance-date']);
    $attendanceStatus = htmlspecialchars($_POST['attendance-status']);
    $attendanceRemarks = htmlspecialchars($_POST['attendance-remarks'] ?? '');

    // Validate input (e.g., attendance date and status should not be empty)
    if (empty($attendanceDate) || empty($attendanceStatus)) {
        echo "Attendance date and status are required.";
        exit;
    }

    // Prepare the SQL query using prepared statements to avoid SQL injection
    $query = "INSERT INTO attendance (employee_id, date, status, remarks) 
              VALUES (?, ?, ?, ?)";

    // Initialize prepared statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssss", $employeeId, $attendanceDate, $attendanceStatus, $attendanceRemarks);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Attendance recorded successfully.";
        } else {
            // Log error to server logs
            error_log("Database error: " . mysqli_stmt_error($stmt));
            echo "An error occurred while recording attendance. Please try again later.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Log error to server logs
        error_log("Database error: " . mysqli_error($conn));
        echo "An error occurred while preparing the statement.";
    }
} else {
    echo "Invalid request method.";
}
?>
