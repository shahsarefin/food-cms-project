<?php
// 7.4: Logins are handled by an HTML form submitted to a PHP script.
session_start();
include './DB/db_connect.php';

if (isset($_POST["submit"])) :
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username, password FROM tbl_admin WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) :
        // 7.3: Passwords must be verified during the login process using PHP's password_verify function.
        if (password_verify($password, $user['password'])) :
            $_SESSION['loggedin'] = "Login Successful."; // Successful login message (7.4)
            header("location: index.php");
            exit;
        else :
            $_SESSION['login_err'] = "Invalid username or password."; // Failure message (7.4)
            header("location: login.php");
            exit;
        endif;
    else :
        $_SESSION['login_err'] = "Invalid username or password."; // Failure message (7.4)
        header("location: login.php");
        exit;
    endif;
endif;
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

        <?php if(isset($_SESSION['login_err'])) : ?>
            <p class="error"><?= $_SESSION['login_err'] ?></p>
            <?php unset($_SESSION['login_err']); ?>
        <?php endif; ?>

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
