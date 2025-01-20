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

// Check if user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: auth.php");
    exit();
}
$userName = $_SESSION['username']; // User's username

// Handle Add to Cart Action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Check if product is already in the cart
    $checkQuery = "SELECT * FROM Cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if product already in cart
        $updateQuery = "UPDATE Cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Add product to cart
        $insertQuery = "INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
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
    <title>User - Camp Pro</title>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
       /* General Styles */
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
    color: #ff5722; /* Orange on hover */
}

/* Heading Style */
h1 {
    text-align: center;
    margin: 100px 0 10px;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
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

/* Product Type */
.product-type {
    margin-top: 20px;
    padding: 10px 15px;
    background: rgba(255, 223, 0, 0.9); /* Semi-transparent yellow */
    font-weight: bold;
    font-size: 18px;
    border-radius: 5px;
    color: #000;
}

/* Product Container */
.product-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 15px;
}

/* Individual Product */
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

/* Product Buttons */
.product-buttons {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-top: 1px solid #ddd;
}

/* Add to Cart Button */
.product-buttons .add-to-cart {
    padding: 10px 15px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background: #ffc107; /* Yellow button */
    color: #000;
    transition: background 0.3s;
}

.product-buttons .add-to-cart:hover {
    background: #e0a800; /* Darker yellow on hover */
}
/* Quantity Controls */
.quantity-form {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-top: 10px;
}

.quantity {
    width: 50px;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
    background: #f9f9f9;
}

.quantity:focus {
    outline: none;
    border-color: #ffc107;
    box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
}

/* Buttons */
.increase-quantity, .decrease-quantity {
    width: 30px;
    height: 30px;
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    background: #ffc107;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease-in-out;
}

.increase-quantity:hover, .decrease-quantity:hover {
    background: #e0a800; /* Slightly darker yellow */
}

/* Add to Cart Button */
.add-to-cart {
    margin-top: 10px;
    padding: 10px 15px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background: #ffc107; /* Yellow button */
    color: #000;
    transition: background 0.3s;
}

.add-to-cart:hover {
    background: #e0a800; /* Darker yellow on hover */
}


    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <div class="welcome-container">
            <i class="fas fa-user-circle"></i>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($userName); ?></span>
        </div>
        <div class="navbar">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="user.php"><i class="fas fa-box"></i> Products</a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
        </div>
    </div>

    <h1>User Panel - Products</h1>

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
                            <form class="quantity-form">
                                <button type="button" class="decrease-quantity" data-id="<?php echo $product['id']; ?>">-</button>
                                <input type="text" class="quantity" data-id="<?php echo $product['id']; ?>" value="1" readonly>
                                <button type="button" class="increase-quantity" data-id="<?php echo $product['id']; ?>">+</button>
                            </form>
                            <button type="button" class="add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Increase quantity
            $('.increase-quantity').click(function () {
                const productId = $(this).data('id');
                const quantityField = $(`.quantity[data-id="${productId}"]`);
                let currentQuantity = parseInt(quantityField.val());
                quantityField.val(currentQuantity + 1);
            });

            // Decrease quantity
            $('.decrease-quantity').click(function () {
                const productId = $(this).data('id');
                const quantityField = $(`.quantity[data-id="${productId}"]`);
                let currentQuantity = parseInt(quantityField.val());
                if (currentQuantity > 1) {
                    quantityField.val(currentQuantity - 1);
                }
            });

            // Add to Cart
            $('.add-to-cart').click(function () {
                const productId = $(this).data('id');
                const quantity = parseInt($(`.quantity[data-id="${productId}"]`).val());

                // AJAX request to add to cart
                $.ajax({
                    url: '', // Current PHP file
                    type: 'POST',
                    data: {
                        add_to_cart: true,
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function (response) {
                        alert('Product added to cart with quantity: ' + quantity);
                    },
                    error: function () {
                        alert('Error adding product to cart!');
                    }
                });
            });
        });
    </script>
</body>


</html>
