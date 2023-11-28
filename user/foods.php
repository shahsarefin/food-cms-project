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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="foods.php">Foods</a></li>
                    
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <section class="food-search text-center">
        <div class="container">
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required />
                <input type="submit" name="submit" value="Search" class="btn btn-primary" />
            </form>
        </div>
    </section>

    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php include 'db_connect.php'; ?>

            <?php try {
                $sql = "SELECT * FROM tbl_food WHERE active='Yes'";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) :
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
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
                                <h4><?php echo $title; ?></h4>
                                <p class="food-price">$<?php echo $price; ?></p>
                                <p class="food-detail">
                                    <?php echo $description; ?>
                                </p>
                                <br />
                                <a href="order.html?food_id=<?php echo $row['id']; ?>" class="btn btn-primary">Order Now</a>
                            </div>
                        </div>

            <?php
                    endwhile;
                else :
                    echo '<div class="error">Food not available.</div>';
                endif;
            } catch (PDOException $e) {
                echo 'Query failed: ' . $e->getMessage();
            }
            ?>

            <div class="clearfix"></div>
        </div>
    </section>

    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved, 2023</p>
        </div>
    </section>
</body>
</html>
