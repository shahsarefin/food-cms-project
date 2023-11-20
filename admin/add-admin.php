<html>
    <head>
        <title>Add Admin Page</title>
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
                <h1> Add Admin</h1>
                <br><br>

                <?php 
                    if(isset($_SESSION['admin_added']))
                    {
                        echo $_SESSION['admin_added'];
                        unset($_SESSION['admin_added']);
                    } ?>

                <form action="" method="post">
                    <table class="tbl-30">
                        <tr>
                            <td>Full Name: </td>
                            <td><input type="text" name="full_name" placeholder="Enter your full name"></td>
                        </tr>
                        <tr>
                            <td>Username: </td>
                            <td><input type="text" name="username" placeholder="Enter your username"></td>
                        </tr>
                        <tr>
                            <td>Password: </td>
                            <td><input type="password" name="password" placeholder="Enter your password"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
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

<?php 
session_start();     
include "./DB/db_connect.php";
    // Check whether the submit button is clicked
    if(isset($_POST['submit'])) {
        // Get the data from the form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

        // Prepare SQL query
        $stmt = $pdo->prepare("INSERT INTO tbl_admin (full_name, username, password) VALUES (:full_name, :username, :password)");
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the query
        try {
            $stmt->execute();
            
            // Set a session variable upon success
            $_SESSION['admin_added'] = "New admin added successfully";
    
            // Redirect to the manage-admin page
            header('Location: manage-admin.php');
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>