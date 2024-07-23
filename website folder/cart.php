<?php
include 'config.php';

// Start session to store cart data
session_start();

// Initialize shopping cart if it doesn't exist in session
if (!isset($_SESSION['shopping_cart'])) {
    $_SESSION['shopping_cart'] = array();
}

// Fetch customers and products for form dropdowns
$customers = mysqli_query($conn, "SELECT customer_id, name FROM Customer");
$products = mysqli_query($conn, "SELECT product_id, product_name, price FROM Products");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;

    // Unique key for each customer-product pair
    $key = "$customer_id:$product_id";

    switch ($action) {
        case 'add_to_cart':
            // Check if the product already exists in the cart
            $query = "SELECT * FROM ShoppingCart WHERE customer_id='$customer_id' AND product_id='$product_id'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Update quantity if product already exists
                $query = "UPDATE ShoppingCart SET quantity = quantity + '$quantity' WHERE customer_id='$customer_id' AND product_id='$product_id'";
            } else {
                // Add new item to the cart
                $query = "INSERT INTO ShoppingCart (customer_id, product_id, quantity) VALUES ('$customer_id', '$product_id', '$quantity')";
            }

            if (mysqli_query($conn, $query)) {
                $message = "Product added to cart!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            break;

        case 'update_cart':
            // Update quantity and amount if the product exists in the cart
            $query = "UPDATE ShoppingCart 
                      SET quantity = '$quantity', amount = '$quantity' * '$price'
                      WHERE customer_id='$customer_id' AND product_id='$product_id'";
            if (mysqli_query($conn, $query)) {
                $message = "Cart updated!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            break;

        case 'remove_from_cart':
            // Remove item from cart
            $query = "DELETE FROM ShoppingCart WHERE customer_id='$customer_id' AND product_id='$product_id'";
            if (mysqli_query($conn, $query)) {
                $message = "Product removed from cart!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            break;

        default:
            $error = "Invalid action.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
            margin-bottom: 20px;
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
        .form-container input[type="number"],
        .form-container input[type="text"],
        .form-container input[type="submit"],
        .form-container select {
            width: calc(100% - 22px); /* Adjust for padding */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
    <h2>Shopping Cart</h2>
    <?php if (isset($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="cart.php">
        <label for="action">Choose Action:</label>
        <select id="action" name="action" required>
            <option value="">Select...</option>
            <option value="add_to_cart">Add to Cart</option>
            <option value="update_cart">Update Cart</option>
            <option value="remove_from_cart">Remove from Cart</option>
        </select><br>

        <label for="customer_id">Customer Name:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">Select Customer...</option>
            <?php while ($row = mysqli_fetch_assoc($customers)) : ?>
                <option value="<?= $row['customer_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="product_id">Product Name:</label>
        <select id="product_id" name="product_id" required>
            <option value="">Select Product...</option>
            <?php while ($row = mysqli_fetch_assoc($products)) : ?>
                <option value="<?= $row['product_id'] ?>" data-price="<?= $row['price'] ?>"><?= htmlspecialchars($row['product_name']) ?></option>
            <?php endwhile; ?>
        </select><br>

        <div id="quantity_field" style="display: none;">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1"><br>
        </div>

        <div id="price_field" style="display: none;">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" readonly><br>
        </div>

        <input type="submit" value="Submit">
    </form>
</div>

<script>
    // Function to show/hide quantity and price fields based on selected action
    document.getElementById('action').addEventListener('change', function() {
        var action = this.value;
        var quantityField = document.getElementById('quantity_field');
        var priceField = document.getElementById('price_field');
        var quantityInput = document.getElementById('quantity');
        var priceInput = document.getElementById('price');

        if (action === 'add_to_cart' || action === 'update_cart') {
            quantityField.style.display = 'block';
            quantityInput.required = true;

            if (action === 'update_cart') {
                priceField.style.display = 'block';
                priceInput.required = true;
            } else {
                priceField.style.display = 'none';
                priceInput.required = false;
            }
        } else {
            quantityField.style.display = 'none';
            priceField.style.display = 'none';
            quantityInput.required = false;
            priceInput.required = false;
        }
    });

    // Function to set price based on selected product
    document.getElementById('product_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        document.getElementById('price').value = price;
    });
</script>

</body>
</html>
