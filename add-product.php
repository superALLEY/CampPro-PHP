<?php
// Start session
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



// Ensure admin is logged in and retrieve their name
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $adminName = $_SESSION['username'];
} else {
    // Redirect to login if not authenticated as admin
    header("Location: auth.php");
    exit();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth.php");
    exit();
}

// Initialize message variable
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $price = $_POST['price'];
    $size = htmlspecialchars($_POST['size']);
    $description = htmlspecialchars($_POST['description']);
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $type = $_POST['type'];
    $image = htmlspecialchars($_POST['image']);

    $stmt = $conn->prepare("INSERT INTO Products (name, price, size, description, gender, age, type, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssssss", $name, $price, $size, $description, $gender, $age, $type, $image);

    if ($stmt->execute()) {
        $message = "<p class='success'>Product added successfully!</p>";
    } else {
        $message = "<p class='error'>Failed to add product. Please try again.</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Camp Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
        /* Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            background: url('contact.jpg') no-repeat center center/cover;
            background-attachment: fixed;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            background: rgba(255, 223, 0, 0.9);
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
        }

        .welcome-container i {
            font-size: 24px;
            color: #000;
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 25px;
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

        .form-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .form-container h1 {
            text-align: center;
            color: #333;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container input, .form-container select, .form-container textarea {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input:focus, .form-container select:focus, .form-container textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .form-container button {
            background: #007bff;
            color: #fff;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .form-container button:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .return-btn {
            display: block;
            margin: 20px auto;
            text-align: center;
            background: #ffd700;
            color: #000;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .return-btn:hover {
            background: #ffcc00;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
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

    <!-- Form Container -->
    <div class="form-container">
        <h1>Add Product</h1>
        <?php echo $message; ?>
        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="size">Size:</label>
            <input type="text" id="size" name="size" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Unisex">Unisex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="age">Age:</label>
            <select id="age" name="age" required>
                <option value="Kids">Kids</option>
                <option value="Adults">Adults</option>
                <option value="All Ages">All Ages</option>
            </select>

            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="Tent (with stakes and guylines)">Tent (with stakes and guylines)</option>
                <option value="Sleeping Bag (appropriate for the season)">Sleeping Bag (appropriate for the season)</option>
                <option value="Sleeping Pad or Air Mattress">Sleeping Pad or Air Mattress</option>
                <option value="Camping Stove (with fuel) & Cookware (pots, pans, utensils)">Camping Stove (with fuel) & Cookware</option>
                <option value="Water Filter or Water Purification Tablets">Water Filter or Water Purification Tablets</option>
                <option value="Headlamp or Flashlight (with extra batteries)">Headlamp or Flashlight</option>
                <option value="First Aid Kit">First Aid Kit</option>
                <option value="Sturdy Hiking Boots or Shoes">Sturdy Hiking Boots or Shoes</option>
                <option value="Weather-Appropriate Clothing (layers, including a waterproof jacket and warm layers)">Weather-Appropriate Clothing</option>
                <option value="Sunscreen and Insect Repellent">Sunscreen and Insect Repellent</option>
                <option value="Camping Clothes">Camping Clothes</option>
            </select>

            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" required>

            <button type="submit">Add Product</button>
        </form>
        <!-- Return Button -->
        <a href="admin.php" class="return-btn"><i class="fas fa-arrow-left"></i> Return to Admin Page</a>
    </div>
</body>
</html>
