<?php
session_start();
include './DB/db_connect.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

try {
    $adminStmt = $pdo->query("SELECT COUNT(*) FROM tbl_admin");
    $adminCount = $adminStmt->fetchColumn();

    $categoryStmt = $pdo->query("SELECT COUNT(*) FROM tbl_category");
    $categoryCount = $categoryStmt->fetchColumn();

    $foodStmt = $pdo->query("SELECT COUNT(*) FROM tbl_food");
    $foodCount = $foodStmt->fetchColumn();
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}
?>


<html>
<head>
    <title>Home Page</title>
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
                <li><a href="../user/index.php" class="user-site-button">User Site</a></li>
            </ul>
        </div>
    </div>

    <!-- Admin Home Page Main content Section -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Dashboard</h1>

            <br><br>
            <?php 
            if (isset($_SESSION['loggedin'])) {
                echo '<p>' . $_SESSION['loggedin'] . '</p>';
                //unset($_SESSION['loggedin']);
            }
            ?>

            <br><br>
            
            <div class="col-4 text-center">
                <h1><?= $adminCount ?></h1>
                <br>
                Admins
            </div>
            <div class="col-4 text-center">
                <h1><?= $categoryCount ?></h1>
                <br>
                Categories
            </div>
            <div class="col-4 text-center">
                <h1><?= $foodCount ?></h1>
                <br>
                Food Items
            </div>

            <div class="clearfix"></div>

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
