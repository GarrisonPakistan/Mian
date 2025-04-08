<?php
// Include database connection
include('database.php');

// Get the phone number from the request (via GET or POST)
$phone = isset($_GET['phone']) ? $_GET['phone'] : (isset($_POST['phone']) ? $_POST['phone'] : '');

// Validate the phone number input
if (empty($phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number is required.']);
    exit();
}

try {
    // Prepare the SQL query using prepared statements to prevent SQL injection
    $query = "SELECT * FROM client_records WHERE phone_number = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("s", $phone);

        // Execute the prepared statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if records were found
        if ($result->num_rows > 0) {
            $records = [];

            // Fetch records and store them in an array
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }

            // Return the records in JSON format
            echo json_encode(['status' => 'success', 'records' => $records]);
        } else {
            // If no records found
            echo json_encode(['status' => 'no_records', 'message' => 'No records found for the provided phone number.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Log error to server logs
        error_log("Database error: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while preparing the query.']);
    }
} catch (Exception $e) {
    // Log exception to server logs
    error_log("Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
}

// Close the database connection
$conn->close();
?>
