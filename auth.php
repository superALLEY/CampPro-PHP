<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "campprodb"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($role) && !empty($username) && !empty($password)) {
        // Determine the table based on the role
        $table = ($role === 'admin') ? 'admins' : 'users';

        // Query to check user existence
        $query = "SELECT * FROM $table WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) { // Use password_verify for hashed passwords
                // Store role, username, and user ID in session
                $_SESSION['role'] = $role;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id']; // Set user ID in session

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: user.php");
                }
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "No user found with username: $username.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - Camp Pro</title>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('auth.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            color: #333;
        }
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            position: fixed;
            top: 20px;
            right: 10px;
            background: rgba(255, 223, 0, 0.9);
            border-bottom: 2px solid rgba(255, 200, 0, 0.7);
            padding: 15px 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10;
            width: auto;
            border-radius: 25px;
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
            transition: color 0.3s ease-in-out, transform 0.2s;
        }
        .navbar a:hover {
            color: #ff5722;
            transform: translateY(-3px);
        }
        .auth-container {
            max-width: 400px;
            margin: 150px auto;
            background: rgba(255, 223, 0, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: #000;
        }
        .auth-container h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .auth-container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .auth-container label {
            font-size: 16px;
            font-weight: bold;
        }
        .auth-container select, .auth-container input, .auth-container button {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .auth-container select:focus, .auth-container input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }
        .auth-container button {
            background: #ffd700;
            color: #000;
            font-size: 16px;
            font-weight: bold;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        }
        .auth-container button:hover {
            background: #ffcc00;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar-container">
        <div class="navbar">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
            <a href="auth.php"><i class="fas fa-user-circle"></i> Login</a>
        </div>
    </div>

    <div class="auth-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <div class="footer">
        © 2024 Camp Pro. All Rights Reserved.
    </div>
</body>
</html>
