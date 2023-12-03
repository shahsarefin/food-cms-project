<?php
session_start();
include "./DB/db_connect.php";

$title = $featured = $active = $current_image = "";
$id = 0;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_category WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $title = $category['title'];
        $featured = $category['featured'];
        $active = $category['active'];
        $current_image = $category['image_name'];
    } else {
        $_SESSION['no-category-found'] = "<div class='error'>Category not found.</div>";
        header("Location: manage-category.php");
        exit();
    }
}

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $featured = $_POST['featured'];
    $active = $_POST['active'];
    $image_name = $current_image;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($file_extension, $valid_extensions)) {
                $image_name = "Category-File-" . rand(0000, 9999) . "." . $file_extension;
                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/category/" . $image_name;

                if (!move_uploaded_file($source_path, $destination_path)) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload the image.</div>";
                    header("location: update-category.php?id=" . $id);
                    exit();
                }

                if ($current_image != "" && $current_image != $image_name) {
                    $remove_path = "../images/category/" . $current_image;
                    if (file_exists($remove_path) && !unlink($remove_path)) {
                        $_SESSION['failed-remove'] = "<div class='error'>Failed to remove current image.</div>";
                        header("Location: update-category.php?id=" . $id);
                        exit();
                    }
                }
            } else {
                $_SESSION['upload'] = "<div class='error'>Invalid file extension. Only image files (JPEG, PNG, GIF) are allowed.</div>";
                header("location: update-category.php?id=" . $id);
                exit();
            }
        } else {
            $_SESSION['upload'] = "<div class='error'>An error occurred during file upload.</div>";
            header("location: update-category.php?id=" . $id);
            exit();
        }
    }

    $sql = "UPDATE tbl_category SET title = :title, featured = :featured, active = :active, image_name = :image_name WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':featured', $featured);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':image_name', $image_name);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $_SESSION['update'] = "<div class='success'>Category updated successfully.</div>";
    } catch(PDOException $e) {
        $_SESSION['update-error'] = "<div class='error'>Error updating category: " . $e->getMessage() . "</div>";
    }

    header("Location: manage-category.php");
    exit();
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

        <?php
        if (isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }
        if (isset($_SESSION['failed-remove'])) {
            echo $_SESSION['failed-remove'];
            unset($_SESSION['failed-remove']);
        }
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        if (isset($_SESSION['update-error'])) {
            echo $_SESSION['update-error'];
            unset($_SESSION['update-error']);
        }
        ?>

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
