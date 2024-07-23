<?php
// Include the database connection file
include 'config.php';

// Query to select all customers
$query = "SELECT * FROM Customer";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
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
        .container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .customer {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .customer:last-child {
            border-bottom: none;
        }
        .name {
            font-weight: bold;
            color: #555;
        }
        .email {
            color: #888;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer List</h2>
    <?php
    // Check if the query was successful
    if ($result) {
        // Loop through the result set and print each user's name and email
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='customer'>";
            echo "<div class='name'>" . htmlspecialchars($row['name']) . "</div>";
            echo "<div class='email'>" . htmlspecialchars($row['email']) . "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='error'>Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</div>

</body>
</html>
