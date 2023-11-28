<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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

    <!-- Food Search Section -->
    <section class="food-search text-center">
        <div class="container">
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required />
                <input type="submit" name="submit" value="Search" class="btn btn-primary" />
            </form>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Categories</h2>

            <?php include 'db_connect.php'; ?>

            <?php try {
                $sql = "SELECT * FROM tbl_category WHERE active='Yes' LIMIT 3  ";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) :
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
            ?>
                        <a href="category-foods.php?category_id=<?php echo $id; ?>">
                            <div class="box-3 float-container">
                                <?php if ($image_name == "") : ?>
                                    <div class='error'>Image not available.</div>
                                <?php else : ?>
                                    <img src="../images/category/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="img-responsive img-curve" />
                                <?php endif; ?>
                                <h3 class="float-text text-white"><?php echo htmlspecialchars($title); ?></h3>
                            </div>
                        </a>
            <?php
                    endwhile;
                else :
                    echo "<div class='error'>Category not available.</div>";
                endif;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <div class="clearfix"></div>
        </div>
    </section>

<!-- Food Menu Section -->
<section class="food-menu">
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
