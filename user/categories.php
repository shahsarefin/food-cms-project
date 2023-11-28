<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Categories</h2>

            <?php include 'db_connect.php'; ?>

            <?php try {
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
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

    <!-- Footer -->
    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved, 2023</p>
        </div>
    </section>
    
</body>
</html>
