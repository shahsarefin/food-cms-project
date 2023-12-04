<?php

session_start();
include "./DB/db_connect.php";

$message = "";


if (isset($_POST['submit'])) {
    
        // 4.2: Sanitize and validate the ID
        $id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;

        // 4.1: Validate input fields
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
    
        // 4.3: Sanitize inputs to prevent HTML injection
        $full_name = htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8');
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    

    
    $stmt = $pdo->prepare("UPDATE tbl_admin SET full_name = :full_name, username = :username WHERE id = :id");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['admin_update_status'] = "Admin updated successfully.";
    } else {
        $_SESSION['admin_update_status'] = "Failed to update admin.";
    }

    header('Location: manage-admin.php');
    exit();
}


if (isset($_GET['id']) && !isset($_POST['submit'])) {
    $adminId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE id = :id");
    $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        header("Location: manage-admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Admin Page</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="main-content">
        <div class="wrapper">
            <h1>Update Admin</h1>
            <br><br>
            <form action="" method="post">
                <table class="tbl-30">
                    <tr>
                        <td>Full Name: </td>
                        <td><input type="text" name="full_name" value="<?php echo $admin['full_name']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Username: </td>
                        <td><input type="text" name="username" value="<?php echo $admin['username']; ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                            <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
