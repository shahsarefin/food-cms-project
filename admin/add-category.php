<?php
session_start();
include "./DB/db_connect.php";

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $featured = isset($_POST['featured']) ? $_POST['featured'] : 'No';
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';
    $image_name = ""; // 6.1. Images are optional. Pages can still be created and updated without adding an image.

    // Check if a file has been uploaded and there are no errors
    if (isset($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // 6.2. Uploaded images must be tested for “image-ness” as shown in the course notes.
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $valid_extensions)) {
            $_SESSION['upload'] = "<div class='error'>Invalid file extension. Only image files (JPEG, PNG, GIF) are allowed.</div>";
            header("location: add-category.php");
            exit;
        }

        // File passed all checks, proceed with the upload
        $image_name = "Category-File-" . rand(0000, 9999) . "." . $file_extension;
        $source_path = $_FILES['file']['tmp_name'];
        $destination_path = "../images/category/" . $image_name;

        // 6.3. Uploads that pass this "image-ness" test are moved to an uploads folder and with their filename added to a row in an images table.
        if (!move_uploaded_file($source_path, $destination_path)) {
            $_SESSION['upload'] = "<div class='error'>Failed to upload the image.</div>";
            header("location: add-category.php");
            exit();
        }
    } else {
        if($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            // 6.4. Uploads that do not pass the "image-ness" test will be gracefully rejected and will not end up in the file system or database.
            $_SESSION['upload'] = "<div class='error'>No file uploaded or an error occurred.</div>";
            header("location: add-category.php");
            exit();
        }
    }

    // SQL query to add the category
    $stmt = $pdo->prepare("INSERT INTO tbl_category (title, featured, active, image_name) VALUES (:title, :featured, :active, :image_name)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':featured', $featured);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':image_name', $image_name);

    try {
        $stmt->execute();
        $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
    } catch(PDOException $e) {
        $_SESSION['add'] = "<div class='error'>Failed to Add Category: " . $e->getMessage() . "</div>";
    }

    header("Location: manage-category.php");
    exit();
}
?>


<html>
<head>
    <title>Add Category Page</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <!-- header Section -->
    <div class="menu text-center">
        <div class="wrapper">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="manage-admin.php">Admin</a></li>
                <li><a href="manage-category.php">Category</a></li>
                <li><a href="manage-food.php">Food</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="wrapper">
            <h1>Add Category</h1>
            <br><br>
            <?php
                if (isset($_SESSION['add'])) {
                    echo $_SESSION['add'];
                    unset($_SESSION['add']);
                }
                if (isset($_SESSION['upload'])) {
                    echo $_SESSION['upload'];
                    unset($_SESSION['upload']);
                }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <table class="tbl-30">
                    <tr>
                        <td>Title: </td>
                        <td><input type="text" name="title" placeholder="Category Title"></td>
                    </tr>
                    <tr>
                        <td>Select File: </td>
                        <td><input type="file" name="file"></td>
                    </tr>
                    <tr>
                        <td>
                            Featured:
                        </td>
                        <td>
                            <input type="radio" name="featured" value="Yes"> Yes
                            <input type="radio" name="featured" value="No"> No
                        </td>
                    </tr>
                    <tr>
                        <td>Active: </td>
                        <td>
                            <input type="radio" name="active" value="Yes"> Yes
                            <input type="radio" name="active" value="No"> No
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <div class="wrapper">
            <p class="text-center">&copy; 2023 All rights reserved, Food Manitoba</p>
        </div>
    </div>
</body>
</html>
