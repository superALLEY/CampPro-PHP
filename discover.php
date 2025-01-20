<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CampProDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Products
$productQuery = "SELECT * FROM Products ORDER BY type, name";
$result = $conn->query($productQuery);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['type']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - Camp Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('discover.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            color: #fff; /* Default text color */
            position: relative;
        }

        /* Dark overlay for background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            z-index: -1;
        }

        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        /* Navbar Styling */
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Align navbar to the right */
            position: fixed;
            top: 30px; /* Adjust navbar position */
            right: 10px; /* Add right margin */
            background: rgba(255, 223, 0, 0.9); /* Yellow with slight transparency */
            border-bottom: 2px solid rgba(255, 200, 0, 0.7); /* Slightly darker yellow border */
            padding: 15px 30px; /* Adjust height */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10; /* Ensure it appears above other elements */
            width: auto; /* Auto width */
            border-radius: 25px; /* Rounded corners */
            backdrop-filter: blur(10px); /* Frosted glass effect */
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 25px; /* Horizontal spacing between items */
        }

        .navbar a {
            text-decoration: none;
            color: #000; /* Black text for contrast */
            font-size: 20px; /* Larger text size */
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px; /* Space between icons and text */
            transition: color 0.3s ease-in-out, transform 0.2s;
        }

        .navbar a:hover {
            color: #ff5722; /* Bright orange on hover */
            transform: translateY(-3px); /* Subtle animation */
        }

        .navbar a i {
            font-size: 24px; /* Larger icons */
        }

    

        .main {
            text-align: center;
            margin-top: 150px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Text shadow for readability */
        }

        h1 {
            font-size: 48px;
            font-weight: 700;
        }

        p {
            font-size: 18px;
            font-weight: 500;
        }

        .product-list {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            color: #000; /* Black text for contrast */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .product-type {
            margin-top: 20px;
            padding: 10px 15px;
            background: rgba(255, 223, 0, 0.9);
            font-weight: bold;
            font-size: 18px;
            border-radius: 5px;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 15px;
        }

        .product {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            text-align: center;
            color: #000;
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: contain;
        }

        .product-details {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <div class="navbar">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
            <a href="auth.php"><i class="fas fa-user-circle"></i> Login</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Discover Our Products</h1>
        <p>Explore the best in outdoor adventure gear.</p>
    </div>

    <!-- Product List -->
    <div class="product-list">
        <?php foreach ($products as $type => $items): ?>
            <div class="product-type"><?php echo htmlspecialchars($type); ?></div>
            <div class="product-container">
                <?php foreach ($items as $product): ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-details">
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            <p>Description: <?php echo htmlspecialchars($product['description']); ?></p>
                            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                            <p>Size: <?php echo htmlspecialchars($product['size']); ?></p>
                            <p>Gender: <?php echo htmlspecialchars($product['gender']); ?></p>
                            <p>Age: <?php echo htmlspecialchars($product['age']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        © 2024 Camp Pro. All Rights Reserved.
    </div>
</body>
</html>
