<?php
include 'config.php';

// Start session to store wishlist data
session_start();

// Fetch customers and products for form dropdowns
$customers = mysqli_query($conn, "SELECT customer_id, name FROM Customer");
$products = mysqli_query($conn, "SELECT product_id, product_name FROM Products");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];

    switch ($action) {
        case 'add_to_wishlist':
            // Add new item to the wishlist
            $query = "INSERT INTO Wishlist (customer_id, product_id) VALUES ('$customer_id', '$product_id')";
            if (mysqli_query($conn, $query)) {
                $message = "Product added to wishlist!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            break;

        case 'remove_from_wishlist':
            // Remove item from wishlist
            $query = "DELETE FROM Wishlist WHERE customer_id='$customer_id' AND product_id='$product_id'";
            if (mysqli_query($conn, $query)) {
                $message = "Product removed from wishlist!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            break;

        case 'view_wishlist':
            // View wishlist items
            $query = "SELECT * FROM Wishlist WHERE customer_id='$customer_id'";
            $result = mysqli_query($conn, $query);
            if ($result) {
                $wishlist_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
    <title>Wishlist</title>
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
        .wishlist-container ul {
            list-style-type: none;
            padding: 0;
        }
        .wishlist-container li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Wishlist</h2>
    <?php if (isset($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="wishlist.php">
        <label for="action">Action:</label>
        <select id="action" name="action" required>
            <option value="add_to_wishlist">Add to Wishlist</option>
            <option value="remove_from_wishlist">Remove from Wishlist</option>
            <option value="view_wishlist">View Wishlist</option>
        </select>

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
                <option value="<?= $row['product_id'] ?>"><?= htmlspecialchars($row['product_name']) ?></option>
            <?php endwhile; ?>
        </select><br>

        <input type="submit" value="Submit">
    </form>
</div>

<?php if (isset($wishlist_items)): ?>
    <div class="wishlist-container">
        <h2>Your Wishlist</h2>
        <ul>
            <?php foreach ($wishlist_items as $item): ?>
                <li>Product ID: <?= htmlspecialchars($item['product_id']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</body>
</html>
