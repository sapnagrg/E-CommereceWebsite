<?php
include 'config.php';

// Fetch categories for the dropdown
$categoryQuery = "SELECT category_id, category_name FROM Categories";
$categoryResult = mysqli_query($conn, $categoryQuery);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $query = "INSERT INTO Products (product_name, category_id, price, description) 
              VALUES ('$product_name', '$category_id', '$price', '$description')";
    if (mysqli_query($conn, $query)) {
        $message = "Product added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-container .message {
            text-align: center;
            margin-bottom: 15px;
            color: #28a745;
        }
        .form-container .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Product</h2>
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
        
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php while ($category = mysqli_fetch_assoc($categoryResult)): ?>
                <option value="<?php echo $category['category_id']; ?>">
                    <?php echo $category['category_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        
        <input type="submit" value="Add Product">
    </form>
</div>

</body>
</html>
