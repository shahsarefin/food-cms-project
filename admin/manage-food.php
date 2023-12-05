<?php
//2.3 sorting added for manage food page
session_start();
include "./DB/db_connect.php";

$foods = [];
$admin_added = isset($_SESSION['admin_added']) ? $_SESSION['admin_added'] : "";
$admin_deleted = isset($_SESSION['admin_deleted']) ? $_SESSION['admin_deleted'] : "";
$admin_update_status = isset($_SESSION['admin_update_status']) ? $_SESSION['admin_update_status'] : "";

// sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;
$allowedSorts = ['title', 'created_at', 'updated_at'];
$orderBy = "";

if (in_array($sort, $allowedSorts)) {
    $orderBy = " ORDER BY $sort";
}

// Fetch food items from the database with optional sorting
try {
    $stmt = $pdo->query("SELECT * FROM tbl_food" . $orderBy);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

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
                <li><a href="../user/index.php" class="user-site-button">User Site</a></li>
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

            <?php if (isset($_SESSION['upload-error'])): ?> 
    <p><?php echo $_SESSION['upload-error']; ?></p>
    <?php unset($_SESSION['upload-error']); ?> 
<?php endif; ?>

            <br><br>

            <a href="add-food.php" class="btn-primary">Add Food</a>
            <br><br>

            <br>
          <!-- 2.3 Sorting  -->
<div class="sorting-options">
<span class="bold-text">Sort by:</span> 
    <a href="?sort=title" class="sorting-button">Title</a> |
    <a href="?sort=created_at" class="sorting-button">Created at</a> | 
    <a href="?sort=updated_at" class="sorting-button">Updated at</a>
</div>


<br><br>
    
<table class="tbl-full">
    <tr>
        <th>Title</th>
        <th>Price</th>
        <th>Image</th>
        <th>Featured</th>
        <th>Active</th>
        <th>Created Date</th>
        <th>Updated Date</th>
        <th style="width: 150px;">Actions</th>
    </tr>

    <!-- Display food items -->
    <?php foreach ($foods as $food): ?>
    <tr>
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
        <td><?php echo htmlspecialchars($food['created_at']); ?></td>
        <td><?php echo htmlspecialchars($food['updated_at']); ?></td>
        <td>
            <a href="update-food.php?id=<?php echo $food['id']; ?>" class="btn-secondary action-button">Update</a>
            <a href="delete-food.php?id=<?php echo $food['id']; ?>" class="btn-danger action-button">Delete</a>
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
