<?php
// Include the database connection file
include 'config.php';

// Query to select all categories
$query = "SELECT * FROM Categories";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Start buffering HTML output
    ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Categories</title>
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
        .category-list-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .category-list-container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .category-list-container ul {
            list-style: none;
            padding: 0;
        }
        .category-list-container ul li {
            background-color: #e9e9e9;
            padding: 10px;
            margin: 8px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="category-list-container">
        <h2>List of Categories</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li><?php echo htmlspecialchars($row['category_name']); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
    // Flush buffered HTML output
    ob_end_flush();
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
