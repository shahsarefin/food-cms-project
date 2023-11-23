<?php
session_start();

include './DB/db_connect.php'; 


$adminId = isset($_GET['id']) ? $_GET['id'] : null;

if($adminId) {
    
    $stmt = $pdo->prepare("DELETE FROM tbl_admin WHERE id = :id");
    $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);

    
    if($stmt->execute()) {
        $_SESSION['admin_deleted'] = "Admin successfully deleted.";
    } else {
        $_SESSION['admin_deleted'] = "Failed to delete admin.";
    }
}


header('Location: manage-admin.php');
exit();
?>
