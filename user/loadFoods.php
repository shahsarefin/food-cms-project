<?php
include 'db_connect.php'; 

// 4.2: Check if 'category_id' is set and is numeric 
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    // 4.2: Sanitize 'category_id' to ensure it's an integer
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // 4.1: Prepare SQL statement 
    $sql = "SELECT * FROM tbl_food WHERE category_id = :category_id AND active = 'Yes'";
    $stmt = $pdo->prepare($sql);

    // 4.2: Bind 'category_id' as an integer to the statement
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iterate over each food item
    foreach ($foods as $food) {
        
        echo '<div class="food-menu-box">';
        echo '<div class="food-menu-img">';
        // Check if image exists
        if ($food['image_name']) {
            // 4.3: Sanitize output to prevent XSS attacks
            echo '<img src="../images/food/' . htmlspecialchars($food['image_name']) . '" alt="' . htmlspecialchars($food['title']) . '" class="img-responsive img-curve">';
        } else {
            echo '<div class="error">Image not available.</div>';
        }
        echo '</div>';

        echo '<div class="food-menu-desc">';
        // 4.3: Sanitize output to prevent XSS attacks
        echo '<h4>' . htmlspecialchars($food['title']) . '</h4>';
        echo '<p class="food-price">$' . htmlspecialchars($food['price']) . '</p>';
        echo '<p class="food-detail">' . htmlspecialchars($food['description']) . '</p>';
        echo '<br />';
        echo '<a href="#" class="btn btn-primary">Order Now</a>';
        echo '</div>';
        echo '</div>';
    }

    // Check if no foods found for the category
    if (empty($foods)) {
        echo "No foods found for this category.";
    }
} else {
    // 4.1: Handle case where no category is selected
    echo "No category selected";
}
?>
