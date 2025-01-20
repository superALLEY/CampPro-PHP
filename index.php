<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Camp Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <link rel="icon" type="image/jpeg" href="public/images/camp-pro-logo.jpg">

    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif; /* Modern font */
            background-color: #f4f4f4;
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

        /* Logo Styling */
        .logo-container {
            position: fixed;
            top: 20px; /* Align logo with navbar */
            left: 10px;
            z-index: 15; /* Ensure it appears above other elements */
        }

        .logo {
            width: 150px; /* Larger width */
            height: 90px; /* Larger height */
            border-radius: 50% / 70%; /* Rugby ball shape */
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logo:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Main Section */
        .main {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
            background: url('background.jpg') no-repeat center center/cover;
            color: #fff;
            text-align: center;
            padding: 20px;
            background-blend-mode: overlay;
        }

        h1 {
            font-size: 64px; /* Increased size for better clarity */
            font-weight: 800; /* Thicker and bolder font */
            margin: 20px 0;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5); /* Stronger shadow for contrast */
        }

        p {
            font-size: 24px; /* Increased size for better readability */
            font-weight: 600; /* Slightly bold text */
            margin: 10px 0 30px;
            line-height: 1.7; /* Improved spacing between lines */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Subtle shadow for better visibility */
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 30px; /* Increased space between buttons */
            margin-top: 20px;
        }

        /* Updated Button Styles */
        .btn {
            display: inline-block;
            background: rgba(255, 223, 0, 0.9); /* Matching yellow background */
            color: #000; /* Black text */
            padding: 20px 50px; /* Larger padding for bigger buttons */
            border: 2px solid rgba(255, 200, 0, 0.7); /* Matching border */
            border-radius: 30px; /* Increased border radius for a modern look */
            font-size: 22px; /* Larger text size */
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s, background-color 0.3s;
            text-align: center;
        }

        .btn:hover {
            background: #ffcc00; /* Slightly brighter yellow */
            color: #000;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
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

    <!-- Main Section -->
    <div class="main">
        <h1>Welcome to Campingy Pro</h1>
        <p>Where outdoor adventures meet professional-grade geart</p>
        <p>Explore our range and elevate your next adventure.</p>
        <div class="button-container">
            <button class="btn" onclick="window.location.href='discover.php'">Discover</button>
        </div>
    </div>
    <div class="footer">
        © 2024 Camp Pro. All Rights Reserved.
    </div>
</body>
</html>
