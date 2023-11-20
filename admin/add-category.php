<?php
session_start();
include "./DB/db_connect.php"; 

if (isset($_POST["submit"])) {
    $title = $_POST['title'];
    $featured = isset($_POST['featured']) ? $_POST['featured'] : 'No';
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';

    // Initialize image_name to empty
    $image_name = "";

    // Check if the image is selected and set the image_name
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];

        //rename the image
        $ext = end(explode('.', $image_name));
        $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext;

        
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/category/" . $image_name;

        // Attempt to upload the image
        $upload = move_uploaded_file($source_path, $destination_path);
        if(!$upload) {
            // Set error message if upload fails and redirect
            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
            header("Location: add-category.php");
            exit();
        }
    }

    // Prepare SQL query
    $stmt = $pdo->prepare("INSERT INTO tbl_category (title, featured, active, image_name) VALUES (:title, :featured, :active, :image_name)");
    
    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':featured', $featured);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':image_name', $image_name);

    // Execute the query
    try {
        $stmt->execute();
        $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
    } catch(PDOException $e) {
        $_SESSION['add'] = "<div class='error'>Failed to Add Category: " . $e->getMessage() . "</div>";
    }

    // Redirect to manage-category page
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
                    <li><a href="manage-category.php
                    ">Category</a></li>
                    <li><a href="manage-food.php">Food</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="wrapper">
                <h1> Add Category</h1>
                <br><br>
            <?php
                if(isset($_SESSION['add'])) {
                    echo $_SESSION['add'];
                    unset($_SESSION['add']);
                }
            ?>

            <form action="" method="post" enctype= "multipart/form-data">
                <table class="tbl-30">
                    <tr>
                        <td>Title: </td>
                        <td><input type="text" name="title" placeholder="Category Title"></td>
                    </tr>

                    <tr>
                        <td>Select Image: </td>
                        <td><input type="file" name="image"></td>
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
                <p class="text-center"> 2023 All rights reserved, Food Manitoba, Developed by <a href="#">Shah Sultanul Arefin</a></p>
            </div>
        </div>
    </body>
   
</html>





