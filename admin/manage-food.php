<?php
// Start the session and include the database connection
session_start();
include "./DB/db_connect.php";

// Initialize an array to store food data and any messages
$foods = [];
$admin_added = isset($_SESSION['admin_added']) ? $_SESSION['admin_added'] : "";
$admin_deleted = isset($_SESSION['admin_deleted']) ? $_SESSION['admin_deleted'] : "";
$admin_update_status = isset($_SESSION['admin_update_status']) ? $_SESSION['admin_update_status'] : "";

// Fetch food items from the database
try {
    $stmt = $pdo->query("SELECT * FROM tbl_food");
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Clear session messages
unset($_SESSION['admin_added'], $_SESSION['admin_deleted'], $_SESSION['admin_update_status']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Food Page</title>
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

    <!-- Main content Section: Manage Food -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Manage Food</h1>
            <br><br>

            <!-- Display session messages -->
            <?php if ($admin_added): ?>
                <p><?php echo $admin_added; ?></p>
            <?php endif; ?>
            <?php if ($admin_deleted): ?>
                <p><?php echo $admin_deleted; ?></p>
            <?php endif; ?>
            <?php if ($admin_update_status): ?>
                <p><?php echo $admin_update_status; ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['upload-error'])): ?> <!-- Add this section -->
    <p><?php echo $_SESSION['upload-error']; ?></p>
    <?php unset($_SESSION['upload-error']); ?> <!-- Clear the session message -->
<?php endif; ?>

            <br><br>

            <a href="add-food.php" class="btn-primary">Add Food</a>
            <br><br>

            <table class="tbl-full">
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Featured</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>

                <!-- Display food items -->
                <?php foreach ($foods as $index => $food): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($food['title']); ?></td>
                    <td><?php echo htmlspecialchars($food['price']); ?></td>
                    <td>
                        <?php if ($food['image_name'] != ""): ?>
                            <img src="../images/food/<?php echo htmlspecialchars($food['image_name']); ?>" width="100px">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($food['featured']); ?></td>
                    <td><?php echo htmlspecialchars($food['active']); ?></td>
                    <td>
                        <a href="update-food.php?id=<?php echo $food['id']; ?>" class="btn-secondary">Update Food</a>
                        <a href="delete-food.php?id=<?php echo $food['id']; ?>" class="btn-danger">Delete Food</a>
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
