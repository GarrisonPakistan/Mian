<?php
// Include database connection
include('database.php');

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle password change
        if (isset($_POST['current-password'], $_POST['new-password'], $_POST['confirm-password'])) {
            $currentPassword = htmlspecialchars($_POST['current-password']);
            $newPassword = htmlspecialchars($_POST['new-password']);
            $confirmPassword = htmlspecialchars($_POST['confirm-password']);

            // Check if new password matches confirmation
            if ($newPassword === $confirmPassword) {
                // Fetch current password hash from the database
                $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = :admin_id");
                $stmt->execute(['admin_id' => $_SESSION['admin_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && password_verify($currentPassword, $result['password'])) {
                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $updateStmt = $pdo->prepare("UPDATE admins SET password = :new_password WHERE id = :admin_id");
                    $updateStmt->execute(['new_password' => $hashedPassword, 'admin_id' => $_SESSION['admin_id']]);

                    echo "Password updated successfully.";
                } else {
                    echo "Current password is incorrect.";
                }
            } else {
                echo "New password and confirmation do not match.";
            }
        }

        // Handle user permissions update
        if (isset($_POST['user-id'], $_POST['permissions'])) {
            $userId = filter_input(INPUT_POST, 'user-id', FILTER_SANITIZE_NUMBER_INT);
            $permissions = $_POST['permissions']; // Array of selected permissions

            // Delete existing permissions for the user
            $deleteStmt = $pdo->prepare("DELETE FROM user_permissions WHERE user_id = :user_id");
            $deleteStmt->execute(['user_id' => $userId]);

            // Insert new permissions
            $insertStmt = $pdo->prepare("INSERT INTO user_permissions (user_id, permission) VALUES (:user_id, :permission)");
            foreach ($permissions as $permission) {
                $sanitizedPermission = htmlspecialchars($permission);
                $insertStmt->execute(['user_id' => $userId, 'permission' => $sanitizedPermission]);
            }

            echo "Permissions updated successfully.";
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "An error occurred: " . $e->getMessage();
    }
}
?>
