<?php 
// Start the session and include the database connection
session_start();
include "./DB/db_connect.php";

// Initialize an array to store admin data and any messages
$admins = [];
$admin_added = "";
$admin_deleted = "";
$admin_update_status = "";

// Check for messages in the session and assign them to variables
if (isset($_SESSION['admin_added'])) {
    $admin_added = $_SESSION['admin_added'];
    unset($_SESSION['admin_added']);
}
if (isset($_SESSION['admin_deleted'])) {
    $admin_deleted = $_SESSION['admin_deleted'];
    unset($_SESSION['admin_deleted']);
}
if (isset($_SESSION['admin_update_status'])) {
    $admin_update_status = $_SESSION['admin_update_status'];
    unset($_SESSION['admin_update_status']);
}

// Fetch admin users from the database
try {
    $stmt = $pdo->query("SELECT * FROM tbl_admin");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $admin_update_status = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Category Page</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
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

    <!-- Main content Section for managing admin page -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Manage Admin</h1>
            <br><br>

            <!-- Display messages using alternative syntax -->
            <?php if ($admin_added): ?>
                <div class="message"><?php echo $admin_added; ?></div>
            <?php endif; ?>
            <?php if ($admin_deleted): ?>
                <div class="message"><?php echo $admin_deleted; ?></div>
            <?php endif; ?>
            <?php if ($admin_update_status): ?>
                <div class="message"><?php echo $admin_update_status; ?></div>
            <?php endif; ?>

            <br><br>
            <a href="add-admin.php" class="btn-primary">Add Admin</a>
            <br><br>

            <!-- Admin table -->
            <table class="tbl-full">
                <tr>
                    <th>Admin No.</th>
                    <th>Full name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
                
                <!-- Showing Admins from database using alternative syntax -->
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                        <td>
                            <a href='update-admin.php?id=<?php echo $admin['id']; ?>' class='btn-secondary'>Update Admin</a>
                            <a href='delete-admin.php?id=<?php echo $admin['id']; ?>' class='btn-danger'>Delete Admin</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
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
