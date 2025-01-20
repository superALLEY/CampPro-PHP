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

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth.php");
    exit();
}
$adminName = $_SESSION['username']; // Admin's username

// Handle Delete Action
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $deleteQuery = "DELETE FROM Products WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Camp Pro</title>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">
<link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('contact.jpg') no-repeat center center/cover;
            background-attachment: fixed;
        }

        /* Overlay for darkening the background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
            z-index: -1; /* Keep it behind the content */
        }

        /* Navbar Styling */
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            background: rgba(255, 223, 0, 0.9); /* Semi-transparent yellow */
            border-bottom: 2px solid rgba(255, 200, 0, 0.7);
            padding: 15px 30px;
            z-index: 10;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .welcome-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .welcome-container .welcome {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .navbar a {
            text-decoration: none;
            color: #000;
            font-size: 20px;
            font-weight: 600;
            transition: color 0.3s ease-in-out;
        }

        .navbar a:hover {
            color: #ff5722;
        }
/* General Styles */
h1 {
    text-align: center;
    margin: 100px 0 10px;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.add-product {
    text-align: center;
    margin: 10px 0;
}

.add-product a {
    display: inline-block;
    background: #28a745;
    color: #fff;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s;
}

.add-product a:hover {
    background: #218838;
}

/* Product List */
.product-list {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.product-type {
    margin-top: 20px;
    padding: 10px 15px;
    background: rgba(255, 223, 0, 0.9); /* Semi-transparent yellow */
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
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    text-align: center;
}

.product img {
    width: 100%;
    height: 200px;
    object-fit: contain;
    background-color: #f9f9f9;
}

.product-details {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 16px;
}

.product-buttons {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-top: 1px solid #ddd;
}

.product-buttons button {
    padding: 10px 15px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.product-buttons .modify {
    background: #007bff;
    color: #fff;
}

.product-buttons .modify:hover {
    background: #0056b3;
}

.product-buttons .delete {
    background: #dc3545;
    color: #fff;
}

.product-buttons .delete:hover {
    background: #b02a37;
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <div class="welcome-container">
            <i class="fas fa-user-circle"></i>
            <span class="welcome">Welcome, Admin: <?php echo htmlspecialchars($adminName); ?></span>
        </div>
        <div class="navbar">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="admin.php"><i class="fas fa-box"></i> Products</a>
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
        </div>
    </div>

    <h1>Admin Panel - Products</h1>

    <div class="add-product">
        <a href="add-product.php">Add New Product</a>
    </div>

    <div class="product-list">
        <?php foreach ($products as $type => $items): ?>
            <div class="product-type"><?php echo htmlspecialchars($type); ?></div>
            <div class="product-container">
                <?php foreach ($items as $product): ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-details">
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            <span>Description: <?php echo htmlspecialchars($product['description']); ?></span>
                            <span>Price: $<?php echo number_format($product['price'], 2); ?></span>
                            <span>Size: <?php echo htmlspecialchars($product['size']); ?></span>
                            <span>Gender: <?php echo htmlspecialchars($product['gender']); ?></span>
                            <span>Age: <?php echo htmlspecialchars($product['age']); ?></span>
                        </div>
                        <div class="product-buttons">
                            <button class="modify" onclick="window.location.href='modify-product.php?id=<?php echo $product['id']; ?>'">Modify</button>
                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="delete" name="delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
