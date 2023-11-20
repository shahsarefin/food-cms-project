<?php
session_start();
include './DB/db_connect.php';

//Processing the Form 
if(isset($_POST['submit'])) {
    // Extract and sanitize form data
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];

    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE tbl_admin SET full_name = :full_name, username = :username WHERE id = :id");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if($stmt->execute()) {
        $_SESSION['admin_update_status'] = "Admin updated successfully.";
    } else {
        $_SESSION['admin_update_status'] = "Failed to update admin.";
    }

    header('Location: manage-admin.php');
    exit();
}

//Fetching Admin Data 
if(isset($_GET['id']) && !isset($_POST['submit'])) { 
    $adminId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE id = :id");
    $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$admin) {
        header("Location: manage-admin.php");
        exit();
    }
}
?>
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
