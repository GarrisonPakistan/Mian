// otp-verification.php
<?php
// Decode the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['email']) || !isset($data['phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$email = htmlspecialchars($data['email']);
$phone = htmlspecialchars($data['phone']);

// Generate a simple OTP (for demonstration purposes, use a secure method in production)
$otp = "111"; // Hardcoded OTP for now

// Return the OTP response (in production, send the OTP via email/SMS)
echo json_encode(['status' => 'success', 'otp' => $otp]);
?>

// search-records.php
<?php
// search-records.php

// Include database connection
include('database.php');

// Validate and sanitize input
if (!isset($_GET['phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number is required.']);
    exit;
}

$phone = htmlspecialchars($_GET['phone']);

try {
    // Prepare the SQL query using prepared statements to prevent SQL injection
    $query = "SELECT * FROM records WHERE phone_number = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("s", $phone);

        // Execute the prepared statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Check for results and return them
        if ($result->num_rows > 0) {
            $records = [];
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
            echo json_encode(['status' => 'success', 'records' => $records]);
        } else {
            echo json_encode(['status' => 'no_records', 'message' => 'No records found.']);
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
?>
