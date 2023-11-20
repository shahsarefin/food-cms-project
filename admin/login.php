<?php
session_start();
include './DB/db_connect.php'; 


// Check if the form is submitted
if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to check if the user exists
    $stmt = $pdo->prepare("SELECT id, username, password FROM tbl_admin WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //Authentication check
    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, so start a new session
            $_SESSION['loggedin'] = "Login Successful.";

            // Redirect user to home page
            header("location: index.php");
            exit;
        } else {
            // Store error message in session and redirect
            $_SESSION['login_err'] = "Invalid username or password.";
            header("location: login.php");
            exit;
        }
    } else {
        // Store error message in session and redirect
        $_SESSION['login_err'] = "Invalid username or password.";
        header("location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css"> 
</head>
<body>
    <div class="login">
        <h1 class="text-center">Login</h1>
        <br>

        <?php 
        
        if(isset($_SESSION['login_err'])) {
            echo '<p class="error">' . $_SESSION['login_err'] . '</p>';
            unset($_SESSION['login_err']);
        }
        ?>

        <br>

        <form action="" method="POST" class="text-center">
            Username: <br>
            <input type="text" name="username" placeholder="Enter Username"> <br><br>
            Password: <br>
            <input type="password" name="password" placeholder="Enter Password"> <br><br>
            <input type="submit" name="submit" value="Login" class="btn-primary">
        </form>
        
    </div>
</body>
</html>



