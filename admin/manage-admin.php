<?php 
session_start();
include "./DB/db_connect.php";
?>
<html>
    <head>
        <title>Manage Admin Page</title>
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
        <!-- Main content Section manage admin page -->
        <div class="main-content">
        <div class="wrapper">
                <h1>Manage Admin</h1>
                <br><br>

                <?php 
                    if(isset($_SESSION['admin_added'])) {
                        echo $_SESSION['admin_added'];
                        unset($_SESSION['admin_added']);
                    }

                    if(isset($_SESSION['admin_deleted'])) {
                        echo $_SESSION['admin_deleted'];
                        unset($_SESSION['admin_deleted']);
                    }
                    
                    if(isset($_SESSION['admin_update_status'])) {
                        echo $_SESSION['admin_update_status'];
                        unset($_SESSION['admin_update_status']);
                    }
                ?>
                <br> <br>

                <a href="add-admin.php
                " class="btn-primary">Add Admin</a>

                <br><br>

                <table class="tbl-full">
                    <tr>
                        <th>Admin No.</th>
                        <th>Full name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                    
                    <!-- Showing Admins from database -->
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM tbl_admin");
                        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($admins as $admin) {
                            echo "<tr>";
                            echo "<td>" . $admin['id'] . "</td>"; 
                            echo "<td>" . htmlspecialchars($admin['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($admin['username']) . "</td>";
                            echo "<td>
                                    <a href='update-admin.php?id=" . $admin['id'] . "' class='btn-secondary'>Update Admin</a>
                                    <a href='delete-admin.php?id=" . $admin['id'] . "' class='btn-danger'>Delete Admin</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </table>
        </div>
          
        <!-- Footer Section -->
        <div class="footer">
        <div class="wrapper">
                <p class="text-center"> 2023 All rights reserved, Food Manitoba, Developed by <a href="#">Shah Sultanul Arefin</a></p>
            </div>
        </div>
    </body>
   
</html>
