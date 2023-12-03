<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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

    <section class="food-search text-center">
        <div class="container">
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required />
                <input type="submit" name="submit" value="Search" class="btn btn-primary" />
            </form>
        </div>
    </section>

    <!-- Food Menu Section -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Search Results</h2>

            <?php
            include 'db_connect.php'; 

            if (isset($_POST['search'])) {
                $search_keyword = $_POST['search']; // Get the search keyword from the form

                // SQL query to search the food table and join with the category table
                $sql = "SELECT f.*, c.title as category_title 
                        FROM tbl_food f 
                        INNER JOIN tbl_category c ON f.category_id = c.id 
                        WHERE f.title LIKE :keyword 
                        OR f.description LIKE :keyword 
                        OR c.title LIKE :keyword";

                $stmt = $pdo->prepare($sql); 
                $stmt->execute(['keyword' => "%{$search_keyword}%"]); 

                // Fetch all the matching records
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    // Display the search results
                    foreach ($results as $row) {
                        
                        ?>
                        <a href="item-details.php?food_id=<?php echo $row['id']; ?>" class="food-menu-box-link">
                            <div class="food-menu-box">
                                <div class="food-menu-img">
                                    <?php if ($row['image_name'] != "") : ?>
                                        <img src="../images/food/<?php echo htmlspecialchars($row['image_name']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="img-responsive img-curve">
                                    <?php else : ?>
                                        <div class="error">Image not available.</div>
                                    <?php endif; ?>
                                </div>

                                <div class="food-menu-desc">
                                    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                                    
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                } else {
                    echo '<div class="error">No foods found matching your search.</div>';
                }
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
