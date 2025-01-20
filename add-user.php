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
$adminName = $_SESSION['username'];

// Initialize message
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'];
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!empty($role) && !empty($username) && !empty($_POST['password']) && !empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Determine table based on role
            $table = ($role === 'admin') ? 'Admins' : 'Users';

            // Insert user or admin into the appropriate table
            $stmt = $conn->prepare("INSERT INTO $table (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                $message = "<p class='success'>" . ucfirst($role) . " added successfully!</p>";
            } else {
                $message = "<p class='error'>Failed to add " . htmlspecialchars($role) . ". Please try again.</p>";
            }
            $stmt->close();
        } else {
            $message = "<p class='error'>Invalid email format.</p>";
        }
    } else {
        $message = "<p class='error'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <title>Add User/Admin - Camp Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
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
            background: rgba(255, 223, 0, 0.9); /* Yellow background */
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
            display: flex;
            align-items: center;
            gap: 5px;
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

        .form-container select, .form-container input {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container select:focus, .form-container input:focus {
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
        <h1>Add User or Admin</h1>
        <?php echo $message; ?>
        <form method="POST" action="">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
