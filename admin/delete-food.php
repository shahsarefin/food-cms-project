<?php
session_start();
include "./DB/db_connect.php";

//Deleting id and images from database and local folder
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the image name
    $sql = "SELECT image_name FROM tbl_food WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if($food) {
        $image_name = $food['image_name'];

        // Check if there is an image file and delete it
        if($image_name != "" && file_exists("../images/food/" . $image_name)) {
            unlink("../images/food/" . $image_name);
        }

        $sql = "DELETE FROM tbl_food WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if($result) {
            $_SESSION['delete'] = "Food deleted successfully.";
        } else {
            $_SESSION['delete'] = "Failed to delete food.";
        }
    } else {
        $_SESSION['delete'] = "Food not found.";
    }
} else {
    $_SESSION['delete'] = "Invalid action.";
}

header("Location: manage-food.php");
exit();
?>
