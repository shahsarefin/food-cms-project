<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foods</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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


    <!-- Food Item Details Section -->
    <section class="food-item-details">
        <div class="container">
            <?php
            include 'db_connect.php'; 

            // 4.2: Validate that 'food_id' is numeric before using it in the SQL query
            if(isset($_GET['food_id']) && is_numeric($_GET['food_id'])) {
                // Sanitize 'food_id' to prevent SQL injection
                $food_id = filter_input(INPUT_GET, 'food_id', FILTER_SANITIZE_NUMBER_INT);

                // 4.1: Prepare SQL statement to prevent SQL injection
                $sql = "SELECT * FROM tbl_food WHERE id = :food_id AND active = 'Yes'";
                $stmt = $pdo->prepare($sql);
                // 4.2: Explicitly bind 'food_id' as an integer
                $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
                $stmt->execute();
                $food = $stmt->fetch(PDO::FETCH_ASSOC);

                if($food) {
                    // 4.3: Sanitize all output to prevent XSS
                    ?>

                    <div class="food-item-image">
                        <?php if($food['image_name'] != ""): ?>
                            <!-- Image output sanitized to prevent XSS -->
                            <img src="../images/food/<?php echo htmlspecialchars($food['image_name']); ?>" alt="<?php echo htmlspecialchars($food['title']); ?>">
                        <?php else: ?>
                            <p>Image not available.</p>
                        <?php endif; ?>
                    </div>
                    <div class="food-item-description">
                        <h2><?php echo htmlspecialchars($food['title']); ?></h2>
                        <p><?php echo htmlspecialchars($food['description']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($food['price']); ?></p>
                    </div>
                    <?php
                } else {
                    echo "<p>Food item not found.</p>";
                }
            } else {
                echo "<p>Invalid request.</p>";
            }
            ?>
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
