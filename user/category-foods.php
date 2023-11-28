<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>categories-foods</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
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

    <?php
    include 'db_connect.php';
    if(isset($_GET['category_id']) && is_numeric($_GET['category_id'])) :
        $category_id = $_GET['category_id'];
        $sql = "SELECT title FROM tbl_category WHERE id = :category_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) :
            $category_title = $row['title'];
        else :
            header('location: index.php');
            exit;
        endif;
    else :
        header('location: index.php');
        exit;
    endif;
    ?>

    <!-- Food Search -->
    <section class="food-search text-center">
        <div class="container">
            <h2>Foods on <a href="#" class="text-white">"<?php echo isset($category_title) ? $category_title : 'Category'; ?>"</a></h2>
        </div>
    </section>

    <!-- Food Menu -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php
            include 'db_connect.php';

            $category_id = $_GET['category_id'];

            $sql2 = "SELECT * FROM tbl_food WHERE category_id = :category_id AND active = 'Yes'";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt2->execute();
            $count2 = $stmt2->rowCount();

            if ($count2 > 0) :
                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) :
                    $foodName = $row['food_name'];
                    $description = $row['description'];
                    $price = $row['price'];
                    $image = $row['image_name'];

                    echo '<div class="food-menu-box">';
                    echo '<div class="food-menu-img">';
                    echo '<img src="' . $image . '" alt="' . $foodName . '" class="img-responsive img-curve" />';
                    echo '</div>';

                    echo '<div class="food-menu-desc">';
                    echo '<h4>' . $foodName . '</h4>';
                    echo '<p class="food-price">$' . $price . '</p>';
                    echo '<p class="food-detail">' . $description . '</p>';
                    echo '<br />';
                    echo '<a href="#" class="btn btn-primary">Order Now</a>';
                    echo '</div>';
                    echo '</div>';
                endwhile;
            else :
                echo "No records found.";
            endif;
            ?>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved, 2023</p>
        </div>
    </section>
</body>
</html>
