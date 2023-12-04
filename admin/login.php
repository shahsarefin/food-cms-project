<?php 
session_start();
include './DB/db_connect.php';

if (isset($_POST["submit"])) :
    
    // 4.3: Sanitize user inputs to prevent HTML injection
    //ENT_QUOTES: Converts quotes to HTML entities,UTF-8 Ensures character encoding compatibility, especially for non-standard characters, maintaining correct display and interpretation.
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : '';

    // 4.1: Validate username and password
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM tbl_admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) :
            $_SESSION['loggedin'] = "Login Successful.";
            header("location: index.php");
            exit;
        else :
            $_SESSION['login_err'] = "Invalid username or password.";
            header("location: login.php");
            exit;
        endif;
    } else {
        $_SESSION['login_err'] = "Please enter username and password.";
        header("location: login.php");
        exit;
    }
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
