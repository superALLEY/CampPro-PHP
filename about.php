<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Camp Pro</title>
    <link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar-container {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    position: fixed;
    top: 30px;
    right: 10px;
    background: rgba(255, 223, 0, 0.9); /* Yellow background */
    border-bottom: 2px solid rgba(255, 200, 0, 0.7); /* Slightly darker border */
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
    color: #000; /* Black text for contrast */
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: color 0.3s ease-in-out, transform 0.2s;
}

.navbar a:hover {
    color: #ff5722; /* Bright orange on hover */
    transform: translateY(-3px); /* Subtle upward movement */
}

.navbar a i {
    font-size: 24px; /* Larger icons */
}


        /* Background Section with Two Images */
        .background {
            position: relative;
            height: 100vh;
            display: flex;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)),
                url('about1.jpg') no-repeat center center/cover;
        }

        .background::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 50%; /* Cover half the screen */
            background: url('about2.jpg') no-repeat center center/cover;
        }

        /* Text Content */
        .text-container {
            z-index: 2;
            color: white;
            padding: 60px;
            max-width: 40%;
            margin: auto 0;
        }

        .text-container h1 {
            font-size: 50px;
            margin-bottom: 20px;
            line-height: 1.3;
            font-weight: bold;
        }

        .text-container p {
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #d1d1d1;
        }

        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .cta-buttons a {
            text-decoration: none;
            background: #ffd700;
            color: #000;
            font-weight: bold;
            padding: 15px 30px;
            border-radius: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cta-buttons a:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Product Section */
        .products {
            padding: 60px 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .products h2 {
            font-size: 36px;
            margin-bottom: 30px;
            color: #333;
        }

        .product-list {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .product-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-item img {
            width: 100%;
            display: block;
            height: 200px;
            object-fit: cover;
        }

        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .product-description {
            padding: 15px;
            font-size: 16px;
        }

        /* Footer Section */
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
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

    <!-- Background Section -->
    <div class="background">
        <div class="text-container">
            <h1>Welcome to Camp Pro</h1>
            <p>
                At Camp Pro, we are passionate about providing top-quality camping gear that meets the needs of both enthusiasts and professionals. 
                We currently serve customers in Canada, the USA, and Europe, with plans to expand into Africa and Asia soon.
            </p>
            <p>
                From durable tents to high-performance camping suits, our products are designed to withstand the toughest conditions, 
                ensuring that your outdoor adventures are safe, comfortable, and unforgettable.
            </p>
            <div class="cta-buttons">
                <a href="discover.php">Shop Our Gear</a>
                <a href="contact.php">Get in Touch</a>
            </div>
        </div>
    </div>

    <!-- Product Section -->
    <section id="products" class="products">
        <h2>Our Featured Tents</h2>
        <div class="product-list">
            <div class="product-item">
                <img src="tente.png" alt="High-Quality Tent">
                <div class="product-description">High-Quality Tent</div>
            </div>
            <div class="product-item">
                <img src="tente2.png" alt="Durable Tent">
                <div class="product-description">Durable Tent</div>
            </div>
            <div class="product-item">
                <img src="tente3.jpg" alt="Professional Tent">
                <div class="product-description">Professional Tent</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        © 2024 Camp Pro. All Rights Reserved.
    </div>
</body></html>