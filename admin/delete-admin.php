<?php
session_start();

include './DB/db_connect.php'; 

// Validation and Conversion
$adminId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

if($adminId) {
    $stmt = $pdo->prepare("DELETE FROM tbl_admin WHERE id = :id");
    $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);

    if($stmt->execute()) {
        $_SESSION['admin_deleted'] = "Admin successfully deleted.";
    } else {
        $_SESSION['admin_deleted'] = "Failed to delete admin.";
    }
} else {
    $_SESSION['admin_deleted'] = "Invalid Admin ID.";
}

header('Location: manage-admin.php');
exit();
?>
