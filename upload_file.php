<?php
// Set the directory where uploaded files will be saved
$target_dir = "uploads/";  // Folder for file storage
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);  // Create the directory if it doesn't exist
}

// Get the uploaded file's name and sanitize it
$originalFileName = basename($_FILES["uploadedFile"]["name"]);
$sanitizedFileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalFileName);  // Replace invalid characters
$target_file = $target_dir . $sanitizedFileName;

// Initialize upload flag
$uploadOk = 1;

// Get the file extension and MIME type
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$fileMimeType = mime_content_type($_FILES["uploadedFile"]["tmp_name"]);

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, the file already exists.";
    $uploadOk = 0;
}

// Check file size (e.g., max 10MB)
if ($_FILES["uploadedFile"]["size"] > 10485760) {  // 10MB limit
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow only CSV and TXT files based on MIME type
$allowedMimeTypes = ['text/plain', 'text/csv'];
if (!in_array($fileMimeType, $allowedMimeTypes)) {
    echo "Sorry, only CSV and TXT files are allowed.";
    $uploadOk = 0;
}

// If everything is okay, move the uploaded file to the 'uploads/' folder
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars($sanitizedFileName) . " has been uploaded successfully.";
    } else {
        // Log error to server logs
        error_log("File upload error: Unable to move the uploaded file.");
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
