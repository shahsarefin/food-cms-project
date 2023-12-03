<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Categories</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        // JavaScript function to load foods based on category
        function loadFoods(categoryId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("food-menu").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "loadFoods.php?category_id=" + categoryId, true);
            xhttp.send();
        }
    </script>
</head>
<body>
    <!-- Navbar Section -->
    <section class="navbar">
        <div class="container">
            <div class="menu text-right">
            <ul>
            <div class="logo-container">
            <a href="index.php">
                <img src="../user/img/logo.png" alt="Food Manitoba Logo" />
            </a>
        </div>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Browse Categories</a></li>
                <li><a href="foods.php">All Foods</a></li>
                <li><a href="../admin/login.php">Admin Site</a></li>
            </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

<!-- Food Search Section -->
<section class="food-search text-center">
    <div class="container">
        <form action="food-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for Food.." required />

            <!-- Search Button -->
            <input type="submit" name="submit" value="Search" class="btn btn-primary" />
            <!-- 8.1 AJAX : Dynamic food loading -->
            <!-- 2.4 Categories to pages using Dropdown menu-->
            <p>Browse Foods by Category - Select a Category:</p>
            <select name="category" onchange="loadFoods(this.value)">
                <option value="">Select a Category</option>
                <?php
                include 'db_connect.php'; // Include your database connection
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='".$row['id']."'>".$row['title']."</option>";
                }
                ?>
            </select>
        </form>
    </div>
</section>
                     

<!-- Food Menu Section -->
<section class="food-menu" id="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php try {
            $sql = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 6";
            $stmt = $pdo->query($sql);

            if ($stmt->rowCount() > 0) :
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
        ?>

        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php if ($image_name != "") : ?>
                    <img src="../images/food/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="img-responsive img-curve">
                <?php else : ?>
                    <div class="error">Image not available.</div>
                <?php endif; ?>
            </div>

            <div class="food-menu-desc">
                <h4><?php echo htmlspecialchars($title); ?></h4>
                <p class="food-price">$<?php echo htmlspecialchars($price); ?></p>
                <p class="food-detail"><?php echo htmlspecialchars($description); ?></p>
                <br />
                <a href="#" class="btn btn-primary">Order Now</a>
            </div>
        </div>

        <?php
                endwhile;
            else :
                echo "<div class='error'>Food not available.</div>";
            endif;
        } catch (PDOException $e) {
            echo 'Query failed: ' . $e->getMessage();
        }
        ?>

        <div class="clearfix"></div>
        <div class="text-center">
            <a href="foods.php" class="btn btn-primary">See All Foods</a>
        </div>
    </div>
</section>





    <!-- Footer Section -->
    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved, 2023</p>
        </div>
    </section>
</body>
</html>
