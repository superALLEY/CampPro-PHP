
<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CampProDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['username'];

// Handle AJAX requests for cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $cartId = $_POST['cart_id'] ?? null;

    if ($action && $cartId) {
        if ($action === 'increase') {
            $query = "UPDATE Cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $cartId, $userId);
            $stmt->execute();
        } elseif ($action === 'decrease') {
            $query = "UPDATE Cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ? AND quantity > 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $cartId, $userId);
            $stmt->execute();
        } elseif ($action === 'remove') {
            $query = "DELETE FROM Cart WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $cartId, $userId);
            $stmt->execute();
            echo json_encode(['success' => true]);
            exit();
        }

        // Retrieve updated cart details for the given cart ID
        $result = $conn->query("
            SELECT 
                quantity, 
                (quantity * Products.price) AS total_price 
            FROM Cart 
            JOIN Products ON Cart.product_id = Products.id 
            WHERE Cart.id = $cartId
        ");

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Item not found.']);
        }
        exit();
    }

    http_response_code(400);
    exit();
}

// Fetch Cart Items for Display
$cartQuery = "
    SELECT 
        Cart.id AS cart_id,
        Products.name,
        Products.price,
        Cart.quantity,
        Products.image,
        (Products.price * Cart.quantity) AS total_price
    FROM Cart
    JOIN Products ON Cart.product_id = Products.id
    WHERE Cart.user_id = ?
";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Camp Pro</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       /* General Styles */
       body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f4f4f4;
    background: url('cart.jpg') no-repeat center center/cover;
    background-attachment: fixed;
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

/* Heading */
h1 {
    text-align: center;
    margin-top: 80px;
    color: #333;
}

/* Cart List */
.cart-list {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Cart Item */
.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    border-radius: 5px;
    background-color: #f9f9f9;
}

/* Cart Item Details */
.cart-item-details {
    flex: 1;
    margin-left: 20px;
}

.cart-item-details strong {
    font-size: 18px;
    color: #333;
}

.cart-item-details span {
    display: block;
    font-size: 14px;
    margin-top: 5px;
}

/* Cart Item Actions */
.cart-item-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-item-actions button {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: background 0.3s;
}

.cart-item-actions button:hover {
    opacity: 0.9;
}

/* Quantity Buttons */
.cart-item-actions .decrease-quantity,
.cart-item-actions .increase-quantity {
    background-color: #ffc107; /* Yellow */
    color: #000;
}

.cart-item-actions .decrease-quantity:hover,
.cart-item-actions .increase-quantity:hover {
    background-color: #e0a800; /* Darker yellow */
}

/* Remove Button */
.cart-item-actions .remove-item {
    background: #dc3545; /* Red */
    color: #fff;
}

.cart-item-actions .remove-item:hover {
    background: #b02a37; /* Darker red */
}

/* Total Price */
.total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
    color: #333;
}

    </style>
   <script src="https://www.paypal.com/sdk/js?client-id=AZtlxrgy5cimjcpn9JlH5lkzP590VwPvh_hrloYJLHDyDPQsJQP__9bqkDfqm6XCipAgVd8s3SSjnIwU&currency=USD"></script>



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

    <h1>Your Cart</h1>

    <div class="cart-list" id="cart-list">
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php 
            $totalPrice = 0;
            foreach ($cartItems as $item): 
                $totalPrice += $item['total_price'];
            ?>
                <div class="cart-item" data-id="<?php echo $item['cart_id']; ?>">
    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
    <div class="cart-item-details">
        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
        <span>Price: $<?php echo number_format($item['price'], 2); ?></span>
        <span>Total: $<span class="item-total"><?php echo number_format($item['total_price'], 2); ?></span></span>
    </div>
    <div class="cart-item-actions">
        <button class="decrease-quantity">-</button>
        <span class="quantity"><?php echo $item['quantity']; ?></span>
        <button class="increase-quantity">+</button>
        <button class="remove-item">Remove</button>
    </div>
</div>

            <?php endforeach; ?>
           

            <div class="total">
    Total: $<span id="total-price"><?php echo number_format($totalPrice, 2, '.', ''); ?></span>

    <div id="paypal-button-container" style="margin-top: 10px;"></div>
</div>

        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Save the scroll position before refreshing
    function saveScrollPosition() {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
    }

    // Restore the scroll position after refreshing
    function restoreScrollPosition() {
        const scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            $(window).scrollTop(scrollPosition);
            localStorage.removeItem('scrollPosition');
        }
    }

    // Call this when the document is fully loaded
    restoreScrollPosition();

    // Increase Quantity
    $('.increase-quantity').click(function () {
        const parent = $(this).closest('.cart-item');
        const cartId = parent.data('id');

        $.post('cart.php', { action: 'increase', cart_id: cartId }, function () {
            saveScrollPosition(); // Save current scroll position
            window.location.reload(); // Reload the page
        });
    });

    // Decrease Quantity
    $('.decrease-quantity').click(function () {
        const parent = $(this).closest('.cart-item');
        const cartId = parent.data('id');

        $.post('cart.php', { action: 'decrease', cart_id: cartId }, function () {
            saveScrollPosition(); // Save current scroll position
            window.location.reload(); // Reload the page
        });
    });

    // Remove Item
    $('.remove-item').click(function () {
        const parent = $(this).closest('.cart-item');
        const cartId = parent.data('id');

        $.post('cart.php', { action: 'remove', cart_id: cartId }, function () {
            saveScrollPosition(); // Save current scroll position
            window.location.reload(); // Reload the page
        });
    });
});

</script>
<script>
    paypal.Buttons({
    createOrder: function (data, actions) {
        const totalPrice = document.getElementById('total-price').textContent.trim();
        console.log("Total Price: ", totalPrice); // Debug
        if (!totalPrice || isNaN(totalPrice)) {
            alert("Invalid total price. Please refresh and try again.");
            throw new Error("Invalid total price.");
        }
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: totalPrice // Total price from cart
                }
            }]
        });
    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            console.log("Transaction successful: ", details);
            alert('Transaction completed by ' + details.payer.name.given_name);
            window.location.href = "thankyou.php";
        });
    },
    onError: function (err) {
        console.error("PayPal Error: ", err);
        alert("An error occurred: " + (err.message || "Unknown error. Please try again."));
    }
}).render('#paypal-button-container');

</script>

</body>
</html>
