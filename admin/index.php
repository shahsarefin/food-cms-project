<?php 
session_start();
?>

<html>
    <head>
        <title>Home Page</title>
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
        <!-- Admin Home Page Main content Section -->
        <div class="main-content">
        <div class="wrapper">
                <h1>Dashboard</h1>

                <br><br>
                <?php 
                    if (isset($_SESSION['loggedin'])) {
                        echo '<p>' . $_SESSION['loggedin'] . '</p>';
                        unset($_SESSION['loggedin']);
                    }
                ?>

                <br><br>
                
                <div class="col-4 text-center">
                    <h1>5</h1>
                    <br>
                    Categories
                </div>
                <div class="col-4 text-center">
                    <h1>5</h1>
                    <br>
                    Categories
                </div>
                <div class="col-4 text-center">
                    <h1>5</h1>
                    <br>
                    Categories
                </div>

                <div class="clearfix"></div>

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
