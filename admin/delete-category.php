<?php
session_start();
include "./DB/db_connect.php";

//Deleting id and images from database and local folder
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the image name
    $sql = "SELECT image_name FROM tbl_category WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if($category) {
        $image_name = $category['image_name'];

        // Check if there is an image file and delete it
        if($image_name != "" && file_exists("../images/category/" . $image_name)) {
            unlink("../images/category/" . $image_name);
        }

        $sql = "DELETE FROM tbl_category WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if($result) {
            $_SESSION['delete'] = "Category deleted successfully.";
        } else {
            $_SESSION['delete'] = "Failed to delete category.";
        }
    } else {
        $_SESSION['delete'] = "Category not found.";
    }
} else {
    $_SESSION['delete'] = "Invalid action.";
}

header("Location: manage-category.php");
exit();
?>
