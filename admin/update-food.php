<?php
session_start();
include "./DB/db_connect.php";

$id = $title = $description = $price = $category = $featured = $active = $image_name = "";
$current_image = "";
$categories = [];

// 6.2 - Uploaded images must be tested for “image-ness”
function is_valid_image($file_path)
{
    $image_info = @getimagesize($file_path);
    return $image_info !== false && in_array($image_info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF]);
}

// Fetch the food item details
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid food ID.";
    header('Location: manage-food.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM tbl_food WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$food = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$food) {
    $_SESSION['error'] = "Food not found!";
    header('Location: manage-food.php');
    exit;
}

// Fetch categories for the dropdown
$sql = "SELECT * FROM tbl_category WHERE active = 'Yes'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if (isset($_POST['submit'])) {
    // Process form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $featured = $_POST['featured'];
    $active = $_POST['active'];

    // Image handling
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        // New image is uploaded
        $image_name = $_FILES['image']['name'];
        $source_path = $_FILES['image']['tmp_name'];
        $file_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // 6.2 Check if the uploaded file is a valid image
        if (!is_valid_image($source_path)) {
            // 6.4 - Uploads that do not pass the "image-ness" test will be gracefully rejected and will not end up in the file system or database.
            $_SESSION['upload-error'] = "Invalid image format. Only JPEG, PNG, and GIF images are allowed.";
            header('Location: manage-food.php'); // Redirect to the manage-food page with error message
            exit();
        }

        // Generate a new filename for the image
        $image_name = "Food-Name-" . rand(0000, 9999) . "." . $file_extension;
        $destination_path = "../images/food/" . $image_name;

        // 6.3 Upload the new image
        $upload = move_uploaded_file($source_path, $destination_path);
        if ($upload == false) {
            $_SESSION['upload-error'] = "Failed to upload new image.";
            header('Location: update-food.php?id=' . $id);
            exit();
        }

        // Delete the old image if it exists
        if ($food['image_name'] != "" && file_exists("../images/food/" . $food['image_name'])) {
            //remove an associated image from a page
            unlink("../images/food/" . $food['image_name']);
        }
    } else {
        // 6.1 - Images are optional. Pages can still be created and updated without adding an image.
        $image_name = $food['image_name'];
    }

    // Update query
    $sql = "UPDATE tbl_food SET title = :title, description = :description, price = :price, image_name = :image_name, category_id = :category, featured = :featured, active = :active WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image_name', $image_name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':featured', $featured);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':id', $id);
    
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['update'] = "Food updated successfully.";
    } else {
        $_SESSION['update'] = "Failed to update food.";
    }

    header('Location: manage-food.php');
    exit();
}
?>



<html>
<head>
    <title>Update Food Page</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Header Section -->
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

    <!-- Main Content Section: Manage Food -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Update Food</h1>

            <form action="update-food.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
                <table>
                    <!-- Title -->
                    <tr>
                        <td>Title: </td>
                        <td>
                            <input type="text" name="title" placeholder="Name of the Food" value="<?= htmlspecialchars($food['title']) ?>">
                        </td>
                    </tr>

                    <!-- Description -->
                    <tr>
                        <td>Description: </td>
                        <td>
                            <textarea name="description" cols="30" rows="5"><?= htmlspecialchars($food['description']) ?></textarea>
                        </td>
                    </tr>

                    <!-- Price -->
                    <tr>
                        <td>Price: </td>
                        <td>
                            <input type="number" name="price" value="<?= htmlspecialchars($food['price']) ?>">
                        </td>
                    </tr>

                    <!-- Current Image -->
                    <tr>
                        <td>Current Image:</td>
                        <td>
                            <?php if ($food['image_name'] != ""): ?>
                                <img src="../images/food/<?= htmlspecialchars($food['image_name']) ?>" width="150px">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Select New Image -->
                    <tr>
                        <td>Select New Image: </td>
                        <td>
                            <input type="file" name="image">
                        </td>
                    </tr>

                    <!-- Category -->
                    <tr>
                        <td>Category: </td>
                        <td>
                            <select name="category">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $food['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <!-- Featured -->
                    <tr>
                        <td>Featured: </td>
                        <td>
                            <input type="radio" name="featured" value="Yes" <?= $food['featured'] == 'Yes' ? 'checked' : '' ?>> Yes
                            <input type="radio" name="featured" value="No" <?= $food['featured'] == 'No' ? 'checked' : '' ?>> No
                        </td>
                    </tr>

                    <!-- Active -->
                    <tr>
                        <td>Active: </td>
                        <td>
                            <input type="radio" name="active" value="Yes" <?= $food['active'] == 'Yes' ? 'checked' : '' ?>> Yes
                            <input type="radio" name="active" value="No" <?= $food['active'] == 'No' ? 'checked' : '' ?>> No
                        </td>
                    </tr>

                    <!-- Submit Button -->
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="Update Food" class="btn-secondary">
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
