<?php
// Include database connection
include('database.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize input data
        $clientId = htmlspecialchars($_POST['client-id']);
        $clientName = htmlspecialchars($_POST['client-name']);
        $clientContact = htmlspecialchars($_POST['client-contact']);
        $clientEmail = htmlspecialchars($_POST['client-email']);
        $clientAddress = htmlspecialchars($_POST['client-address']);
        $clientStatus = htmlspecialchars($_POST['client-status']);

        // Validate required fields
        if (empty($clientId) || empty($clientName) || empty($clientContact) || empty($clientEmail) || empty($clientAddress) || empty($clientStatus)) {
            echo "All fields are required.";
            exit;
        }

        // Prepare the SQL query using prepared statements
        $query = "UPDATE clients SET name = ?, contact = ?, email = ?, address = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sssssi", $clientName, $clientContact, $clientEmail, $clientAddress, $clientStatus, $clientId);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Client record updated successfully.";
            } else {
                // Log error to server logs
                error_log("Database error: " . $stmt->error);
                echo "An error occurred while updating the client record. Please try again later.";
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
