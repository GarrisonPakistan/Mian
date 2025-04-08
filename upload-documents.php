<?php
// Include database connection
include('database.php');

// Start session to access employee ID
session_start();

// Handle document upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload-file'])) {
    try {
        // Validate session
        if (!isset($_SESSION['employee_id'])) {
            echo "Unauthorized access. Please log in.";
            exit;
        }

        $employeeId = $_SESSION['employee_id'];
        $documentType = htmlspecialchars($_POST['document-type']);
        $documentDescription = htmlspecialchars($_POST['document-description'] ?? '');
        $file = $_FILES['upload-file'];

        // Define the directory to store the file
        $uploadDir = '../uploads/employee-docs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        // Sanitize the file name
        $originalFileName = basename($file['name']);
        $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalFileName);
        $uploadFilePath = $uploadDir . $sanitizedFileName;

        // Validate file size (e.g., max 5MB)
        if ($file['size'] > 5242880) { // 5MB limit
            echo "File size exceeds the maximum limit of 5MB.";
            exit;
        }

        // Validate file type (e.g., allow only PDF and DOCX)
        $allowedMimeTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileMimeType = mime_content_type($file['tmp_name']);
        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            echo "Invalid file type. Only PDF and DOCX files are allowed.";
            exit;
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            // Insert the document details into the database using prepared statements
            $query = "INSERT INTO documents (employee_id, document_type, file_path, description) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("isss", $employeeId, $documentType, $uploadFilePath, $documentDescription);

                if ($stmt->execute()) {
                    echo "Document uploaded successfully.";
                } else {
                    // Log error to server logs
                    error_log("Database error: " . $stmt->error);
                    echo "An error occurred while saving the document details. Please try again later.";
                }

                // Close the statement
                $stmt->close();
            } else {
                // Log error to server logs
                error_log("Database error: " . $conn->error);
                echo "An error occurred while preparing the query.";
            }
        } else {
            echo "Error moving the uploaded file.";
        }
    } catch (Exception $e) {
        // Log exception to server logs
        error_log("Exception: " . $e->getMessage());
        echo "An unexpected error occurred. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
