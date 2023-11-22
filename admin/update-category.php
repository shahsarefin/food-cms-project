<?php
session_start();
include "./DB/db_connect.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $featured = $_POST['featured'];
    $active = $_POST['active'];
    $image_name = ""; // 6.1 Images are optional. Pages can still be created and updated without adding an image.

    if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $valid_extensions)) {
            $_SESSION['upload-error'] = "<div class='error'>Invalid file extension. Only image files (JPEG, PNG, GIF) are allowed.</div>";
            header("Location: manage-category.php");
            exit();
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES['image']['tmp_name']);
        $valid_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($mime_type, $valid_mime_types)) {
            $_SESSION['upload-error'] = "<div class='error'>Invalid MIME type. Only image files (JPEG, PNG, GIF) are allowed.</div>";
            header("Location: manage-category.php");
            exit();
        }

        if (!getimagesize($_FILES['image']['tmp_name'])) {
            $_SESSION['upload-error'] = "<div class='error'>File is not an actual image.</div>";
            header("Location: manage-category.php");
            exit();
        }

        $image_name = "Food_Category_" . rand(000, 999) . '.' . $file_extension;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/category/" . $image_name;

        if (move_uploaded_file($source_path, $destination_path)) {
            // 6.2 Uploads that pass this "image-ness" test are moved to an uploads folder and with their filename added to a row in an images table.
        } else {
            $_SESSION['upload'] = "<div class='error'>Failed to upload the image.</div>";
            header("Location: update-category.php?id=" . $id);
            exit();
        }

        if (!empty($_POST['current_image'])) {
            $remove_path = "../images/category/" . $_POST['current_image'];
            if (unlink($remove_path)) {
                // Remove an associated image from a page.
            } else {
                $_SESSION['failed-remove'] = "<div class='error'>Failed to remove the current image.</div>";
                header("Location: update-category.php?id=" . $id);
                exit();
            }
        }
    } else {
        $image_name = $_POST['current_image']; // If no new image is uploaded, keep the current image.
    }

    try {
        $sql = "UPDATE tbl_category SET title = :title, featured = :featured, active = :active, image_name = :image_name WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':featured', $featured);
        $stmt->bindParam(':active', $active);
        $stmt->bindParam(':image_name', $image_name);
        $stmt->execute();

        $_SESSION['update'] = "<div class='success'>Category updated successfully.</div>";
        header("Location: manage-category.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['update-error'] = "<div class='error'>Error updating category: " . $e->getMessage() . "</div>";
        header("Location: update-category.php?id=" . $id);
        exit();
    }
}
?>





<!DOCTYPE html>
<html>
<head>
    <title>Update Category</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>
        <br><br>

        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if (!empty($current_image)): ?>
                            <img src="../images/category/<?php echo htmlspecialchars($current_image); ?>" width="100px">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php echo ($featured == 'Yes') ? 'checked' : ''; ?>>
                        Yes
                        <input type="radio" name="featured" value="No" <?php echo ($featured == 'No') ? 'checked' : ''; ?>>
                        No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php echo ($active == 'Yes') ? 'checked' : ''; ?>>
                        Yes
                        <input type="radio" name="active" value="No" <?php echo ($active == 'No') ? 'checked' : ''; ?>>
                        No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>
