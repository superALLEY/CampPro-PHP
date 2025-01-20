<?php
// Initialize message status
$messageStatus = ""; // Ensure $messageStatus is initialized

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $to = "camppro@gmail.com";
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8";

            $fullMessage = "You have received a new message from $name ($email):\n\n";
            $fullMessage .= "Subject: $subject\n\n";
            $fullMessage .= "Message:\n$message\n";

            if (mail($to, $subject, $fullMessage, $headers)) {
                $messageStatus = "<p class='success'>Message sent successfully! We will get back to you soon.</p>";
            } else {
                $messageStatus = "<p class='error'>Failed to send the message. Please try again later.</p>";
            }
        } else {
            $messageStatus = "<p class='error'>Invalid email address. Please enter a valid email.</p>";
        }
    } else {
        $messageStatus = "<p class='error'>All fields are required. Please fill out the form completely.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/jpeg" href="camp-pro-logo.jpg">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Camp Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Icons -->
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('contact.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            color: #333;
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
            justify-content: flex-end;
            position: fixed;
            top: 20px;
            right: 10px;
            background: rgba(255, 223, 0, 0.9); /* Yellow background */
            border-bottom: 2px solid rgba(255, 200, 0, 0.7); /* Slightly darker yellow border */
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
            transform: translateY(-3px);
        }

        .navbar a i {
            font-size: 24px; /* Larger icons */
        }

        /* Page Layout */
        .container {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            max-width: 1400px; /* Increased container width */
            margin: 100px auto;
            padding: 20px;
        }

        /* Text Section */
        .text-section {
            max-width: 40%; /* Adjusted width for the text section */
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .text-section h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #ffd700;
        }

        .text-section p {
            font-size: 18px;
            line-height: 1.8;
        }

        /* Form Section */
        .form-container {
            width:25%; /* Increased form width */
            background: rgba(255, 223, 0, 0.9); /* Matching yellow background */
            padding: 30px; /* Increased padding for better spacing */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: #000; /* Black text for contrast */
        }

        .form-container h1 {
            text-align: center;
            color: #000;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container label {
            font-size: 16px;
            font-weight: bold;
        }

        .form-container input, .form-container textarea, .form-container button {
            font-size: 16px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-container input:focus, .form-container textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .form-container textarea {
            resize: none;
            height: 100px;
        }

        .form-container button {
            background: #ffd700; /* Matching yellow */
            color: #000; /* Black text for contrast */
            font-size: 16px;
            border: none;
            padding: 15px 20px; /* Slightly larger button */
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .form-container button:hover {
            background: #ffcc00; /* Slightly brighter yellow */
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .success, .error {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
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

    <!-- Page Layout -->
    <div class="container">
        <!-- Text Section -->
        <div class="text-section">
            <h1>About Camp Pro</h1>
            <p>
                Camp Pro is your trusted partner for all your outdoor adventures. From durable tents to professional camping suits,
                we provide top-quality gear that ensures your safety, comfort, and success in even the toughest conditions.
            </p>
            <p>
                With a commitment to innovation and customer satisfaction, we currently serve Canada, the USA, and Europe, 
                and are expanding to Africa and Asia soon.
            </p>
        </div>

        <!-- Form Section -->
        <div class="form-container">
            <h1>Contact Us</h1>
            <!-- Display Message Status -->
            <?php echo $messageStatus; ?>

            <!-- Contact Form -->
            <form method="POST" action="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Enter your message" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
    <div class="footer">
        © 2024 Camp Pro. All Rights Reserved.
    </div>
</body>
</html>
