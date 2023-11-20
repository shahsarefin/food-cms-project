<?php
session_start();

include './DB/db_connect.php'; // Database connection

// Get the admin ID to delete
$adminId = isset($_GET['id']) ? $_GET['id'] : null;

if($adminId) {
    // Prepare the delete query
    $stmt = $pdo->prepare("DELETE FROM tbl_admin WHERE id = :id");
    $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);

    // Execute and check if the deletion is successful
    if($stmt->execute()) {
        $_SESSION['admin_deleted'] = "Admin successfully deleted.";
    } else {
        $_SESSION['admin_deleted'] = "Failed to delete admin.";
    }
}

// Redirect back to manage-admin page
header('Location: manage-admin.php');
exit();
?>
