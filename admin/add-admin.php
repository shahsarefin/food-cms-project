<?php 
session_start();
include "./DB/db_connect.php";

$message = "";

if(isset($_POST['submit'])) {
    //7.2 - Userbname and password are stored in admin table
    //7.3 passwords stored in admin table hasded and salted
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO tbl_admin (full_name, username, password) VALUES (:full_name, :username, :password)");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();
    
    $message = "New admin added successfully";
    $_SESSION['admin_added'] = $message;
    
    header('Location: manage-admin.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Admin Page</title>
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

    <div class="main-content">
        <div class="wrapper">
            <h1>Add Admin</h1>
            <br><br>

            <?php if(!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

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
            <p class="text-center">&copy; 2023 All rights reserved, Food Manitoba</p>
        </div>
    </div>
</body>
</html>
