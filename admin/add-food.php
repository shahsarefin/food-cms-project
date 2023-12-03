<?php
//6.1 - add image by a form upload and image-ness test
session_start();
include "./DB/db_connect.php";

if (isset($_POST['submit'])) {
    // 4.1 Validate Title
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    if(empty($title)) {
        // Handle error - title is required
        $message = "Title is required"; // Validation message for 4.1
        $_SESSION['add_food_error'] = $message;
        header("location: add-food.php");
        exit;
    }

    // 4.1 Validate Description
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    if(empty($description)) {
        // Handle error - description is required
        $message = "Description is required"; // Validation message for 4.1
        $_SESSION['add_food_error'] = $message;
        header("location: add-food.php");
        exit;
    }

    // 4.2 Validate and Sanitize Price
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    if($price === false) {
        // Handle error - price must be a valid number
        $message = "Price must be a valid number"; // Validation message for 4.2
        $_SESSION['add_food_error'] = $message;
        header("location: add-food.php");
        exit;
    }
    // 4.2 Validate and Sanitize Category 
    $category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    if($category === false) {
        // Handle error - category must be a valid ID
        $message = "Category must be selected"; // Validation message for 4.2
        $_SESSION['add_food_error'] = $message;
        header("location: add-food.php");
        exit;
    }

    // 4.2 Validate Featured and Active as they are expected to be 'Yes' or 'No'
    $featured = filter_input(INPUT_POST, 'featured', FILTER_SANITIZE_STRING);
    $featured = $featured === 'Yes' ? 'Yes' : 'No'; 

    $active = filter_input(INPUT_POST, 'active', FILTER_SANITIZE_STRING);
    $active = $active === 'Yes' ? 'Yes' : 'No'; 
    
    $image_name = ""; //6.1 Images are optional. Pages can still be created and updated without adding an image.

    if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $valid_extensions)) {
            $_SESSION['upload'] = "<div class='error'>Invalid file extension.</div>";
            header("location: add-food.php");
            exit;
        }

        // 6.2 Testing for image-ness
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES['image']['tmp_name']);
        $valid_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mime_type, $valid_mime_types)) {
            $_SESSION['upload'] = "<div class='error'>Invalid MIME type.</div>";
            header("location: add-food.php");
            exit;
        }

        if (!getimagesize($_FILES['image']['tmp_name'])) {
            $_SESSION['upload'] = "<div class='error'>File is not an actual image.</div>";
            header("location: add-food.php");
            exit;
        }

        $image_name = "Food-Name-" . rand(0000, 9999) . "." . $file_extension;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/food/" . $image_name;

        if (move_uploaded_file($source_path, $destination_path)) {
            // 6.3: Uploads that pass this "image-ness" test are moved to an uploads folder and with their filename added to a row in an images table.
        } else {
            $_SESSION['upload'] = "<div class='error'>Failed to upload image.</div>";
            header("location: add-food.php");
            exit();
        }
    } else {
        if($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $_SESSION['upload'] = "<div class='error'>No file uploaded or an error occurred.</div>";
            header("location: add-food.php");
            exit();
            // 6.4: Uploads that do not pass the "image-ness" test will be gracefully rejected and will not end up in the file system or database.
        }
    }

    $sql2 = "INSERT INTO tbl_food (title, description, price, image_name, category_id, featured, active) 
             VALUES (:title, :description, :price, :image_name, :category_id, :featured, :active)";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':title', $title);
    $stmt2->bindParam(':description', $description);
    $stmt2->bindParam(':price', $price);
    $stmt2->bindParam(':image_name', $image_name);
    $stmt2->bindParam(':category_id', $category);

    $stmt2->bindParam(':featured', $featured);
    $stmt2->bindParam(':active', $active);

    $result2 = $stmt2->execute();
    if ($result2) {
        $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
    } else {
        $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
    }
    header("location: manage-food.php");
    exit();
}
?>


<html>
    <head>
        <title>Manage Food Page</title>
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

        <!-- Main content Section: Manage Food -->
        <div class="main-content">
            <div class="wrapper">
                <h1>Add Food </h1>

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
                    <table>
                        <tr>
                            <td>Title: </td>
                            <td>
                                <input type="text" name="title" placeholder="Name of the Food">
                            </td>
                        </tr>
                        <tr>
                            <td>Description: </td>
                            <td>
                                <textarea name="description" cols="30" rows="5" placeholder="Description of the Food"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Price: </td>
                            <td>
                                <input type="number" name="price">
                            </td>
                        </tr>
                        <tr>
                            <td>Select Image: </td>
                            <td>
                                <input type="file" name="image">
                            </td>
                        </tr>
                        <tr>
                            <td>Category: </td>
                            <td>
                                <select name="category">
                                    <?php
                                        include "./DB/db_connect.php";

                                        // Create SQL to get all active categories from database
                                        $sql = "SELECT * FROM tbl_category WHERE active = 'Yes'";

                                        // Prepare and Execute query
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute();

                                        // Fetch all rows
                                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Check if categories are available
                                        if ($categories) {
                                            // Loop through each category
                                            foreach ($categories as $row) {
                                                // Get the details of categories
                                                $id = $row['id'];
                                                $title = $row['title'];
                                                echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($title) . '</option>';
                                            }
                                        } else {
                                            // No category found
                                            echo '<option value="0">No Category Found</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Featured: </td>
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
                                <input type="submit" name="submit" value="Add Food" class="btn-secondary">
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
