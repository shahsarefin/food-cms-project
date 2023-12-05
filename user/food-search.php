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
        <!-- Navbar content -->
    </section>

    <section class="food-search text-center">
        <div class="container">
            <form action="food-search.php" method="POST">
                <!-- Client-side validation is present here using 'required' attribute -->
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

            // 4.1: Validation for the search input to ensure it is not empty
            if (isset($_POST['search']) && trim($_POST['search']) !== '') {
                // 4.3: Sanitization of the search input to prevent XSS
                $search_keyword = htmlspecialchars(trim($_POST['search']));

                // 4.2: Using prepared statements to validate and sanitize all ids and prevent SQL injection
                $sql = "SELECT f.*, c.title as category_title 
                        FROM tbl_food f 
                        INNER JOIN tbl_category c ON f.category_id = c.id 
                        WHERE f.title LIKE :keyword 
                        OR f.description LIKE :keyword 
                        OR c.title LIKE :keyword";

                $stmt = $pdo->prepare($sql);
                // 4.2 and 4.3: Bind the search keyword to the prepared statement to sanitize the input
                $stmt->bindValue(':keyword', "%{$search_keyword}%");
                $stmt->execute();

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    foreach ($results as $row) {
                        // Output sanitized with htmlspecialchars() to prevent XSS - 4.3
                        
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
