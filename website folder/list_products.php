<?php
// Include the database connection file
include 'config.php';

// Initialize variables
$productList = '';

// Get the category ID from the URL parameter
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    // Query to select products by category ID
    $query = "SELECT * FROM Products WHERE category_id = $category_id";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Check if there are rows returned
        if (mysqli_num_rows($result) > 0) {
            // Build the product list
            while ($row = mysqli_fetch_assoc($result)) {
                $productList .= "<li>{$row['product_name']} - $" . number_format($row['price'], 2) . "</li>";
            }
        } else {
            $productList = "<li>No products found for this category.</li>";
        }
    } else {
        $productList = "<li>Error: " . mysqli_error($conn) . "</li>";
    }

    // Free result set
    mysqli_free_result($result);
} else {
    $productList = "<li>No category ID provided.</li>";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .product-list-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .product-list-container h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .product-list-container ul {
            list-style: none;
            padding: 0;
        }
        .product-list-container ul li {
            background-color: #e9e9e9;
            padding: 10px;
            margin: 8px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="product-list-container">
        <h1>Products List</h1>
        <ul>
            <?= $productList; ?>
        </ul>
    </div>
</body>
</html>
