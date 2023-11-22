<html>
    <head>
        <title>Manage Food Page</title>
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

<!-- Main content Section: Manage Food -->
    <div class="main-content">
        <div class="wrapper">
            <h1>Add Food </h1>

            <br><br>

            <form action="" method="post" enctype="multipart/form-data">

                <table>
                    <tr>
                        <td>Title: </td>
                        <td>
                            <input type="text" name="title" placeholder="Name of the Food">
                        </td>
                    
                        <tr>
                            <td>Description: </td>
                            <td>
                                <textarea name="description" cols="30" rows="5" placeholder="Description of the Food"></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td>Price: </td>
                            <td>
                                <input type="number" name="price">
                            </td>
                        </tr>

                        <tr>
                            <td>Select Image: </td>
                            <td>
                                <input type="file" name="image">
                            </td>
                        </tr>

                        <tr>
                            <td>Category: </td>
                            <td>
                                <select name="category">

                                    <option value="1">Pizza</option>
                                    <option value="2">Burger</option>
                                    <option value="3">Momo</option>
                                    <option value="4">Chicken</option>
                                    <option value="5">Dessert</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Featured: </td>
                            <td>
                                <input type="radio" name="featured" value="Yes"> Yes
                                <input type="radio" name="featured" value="No"> No
                            </td>
                        </tr>

                        <tr>
                            <td>Active: </td>
                            <td>
                                <input type="radio" name="active" value="Yes"> Yes
                                <input type="radio" name="active" value="No"> No
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                            </td>
                        </tr>
                    
                </table>

            </form>
        </div>
    </div>
    <!-- Footer Section -->
  <div class="footer">
        <div class="wrapper">
                <p class="text-center"> 2023 All rights reserved, Food Manitoba, Developed by <a href="#">Shah Sultanul Arefin</a></p>
            </div>
        </div>
    </body>
   
</html>