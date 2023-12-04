<?php
session_start();
include "./DB/db_connect.php";

// Initialization
$id = $title = $description = $price = $category = $featured = $active = $image_name = "";
$current_image = "";
$categories = [];

// Fetch the food item details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM tbl_food WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($food) {
        $title = $food['title'];
        $description = $food['description'];
        $price = $food['price'];
        $category = $food['category_id'];
        $featured = $food['featured'];
        $active = $food['active'];
        $current_image = $food['image_name'];
    } else {
        $_SESSION['error'] = "Food not found!";
        header('Location: manage-food.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid food ID.";
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
    $id = intval($_POST['id']);
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
    $featured = htmlspecialchars($_POST['featured'], ENT_QUOTES, 'UTF-8');
    $active = htmlspecialchars($_POST['active'], ENT_QUOTES, 'UTF-8');
    $image_name = $current_image;  // Keep the current image by default

    // Image handling
    if (isset($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // New image is uploaded
            $image_name = $_FILES['image']['name'];
            $source_path = $_FILES['image']['tmp_name'];
            $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_extension, $valid_extensions)) {
                $image_name = "Food-Name-" . rand(0000, 9999) . "." . $file_extension;
                $destination_path = "../images/food/" . $image_name;

                if (!move_uploaded_file($source_path, $destination_path)) {
                    $_SESSION['upload-error'] = "Failed to upload new image.";
                    header('Location: update-food.php?id=' . $id);
                    exit();
                }

                // Delete the old image if it exists
                if ($food['image_name'] != "" && file_exists("../images/food/" . $food['image_name'])) {
                    unlink("../images/food/" . $food['image_name']); 
                }
            } else {
                $_SESSION['upload-error'] = "Invalid file extension. Only JPEG, PNG, and GIF images are allowed.";
                header('Location: update-food.php?id=' . $id);
                exit();
            }
        } else {
            $_SESSION['upload-error'] = "An error occurred during file upload.";
            header('Location: update-food.php?id=' . $id);
            exit();
        }
    } else {
        $image_name = $food['image_name']; // No new image uploaded, use the existing one
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

<!-- HTML: Update Food Page -->
<!DOCTYPE html>
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
        
            <?php
        // Display upload error messages if any
        if (isset($_SESSION['upload-error'])) {
            echo "<div class='error'>" . $_SESSION['upload-error'] . "</div>";
            unset($_SESSION['upload-error']);
        }
        if (isset($_SESSION['update'])) {
            echo "<div class='success'>" . $_SESSION['update'] . "</div>";
            unset($_SESSION['update']);
        }
        if (isset($_SESSION['update-error'])) {
            echo "<div class='error'>" . $_SESSION['update-error'] . "</div>";
            unset($_SESSION['update-error']);
        }
        ?>

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
