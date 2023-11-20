<?php
session_start();
include "./DB/db_connect.php";

// Fetch data from database
try {
    $stmt = $pdo->query("SELECT * FROM tbl_category");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<html>
    <head>
        <title>Manage Category Page</title>
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

        <!-- Main content Section: Manage Category -->
        <div class="main-content">
            <div class="wrapper">
            <h1>Manage Category</h1>

            <br><br>

            <!-- Display session message if set -->
            <?php
                if(isset($_SESSION['add'])) {
                    echo $_SESSION['add'];
                    unset($_SESSION['add']);
                }
            ?>

            <a href="add-category.php" class="btn-primary">Add Category</a>
            <br><br>

            <table class="tbl-full">
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Featured</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($categories as $index => $category): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($category['title']); ?></td>
                    <td>
                        <?php 
                        if ($category['image_name'] != "") {
                            echo '<img src="../images/category/' . htmlspecialchars($category['image_name']) . '" width="100px">';
                        } else {
                            echo "No Image";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($category['featured']); ?></td>
                    <td><?php echo htmlspecialchars($category['active']); ?></td>
                    <td>
                        <a href="#" class="btn-secondary">Update Category</a>
                        <a href="#" class="btn-danger">Delete Category</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
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
