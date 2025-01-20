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

// Fetch admin data for modification
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];
    $adminQuery = "SELECT * FROM Admins WHERE id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: admin.php");
    exit();
}

// Handle form submission for updating admin data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $admin['password'];

    $updateQuery = "UPDATE Admins SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $username, $email, $password, $admin_id);
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        $error = "Failed to update admin. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Admin - Camp Pro</title>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('contact.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            color: #fff;
        }

        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #fff;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save {
            background: #28a745;
            color: #fff;
        }

        .save:hover {
            background: #218838;
        }

        .cancel {
            background: #dc3545;
            color: #fff;
        }

        .cancel:hover {
            background: #b02a37;
        }

        .error {
            color: #ff5722;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modify Admin</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

            <label for="password">Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password">

            <div class="button-group">
                <button type="submit" class="save">Save</button>
                <button type="button" class="cancel" onclick="window.location.href='admin.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
