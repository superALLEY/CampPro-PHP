# Camp Pro PHP Project

## Overview
Camp Pro is an e-commerce platform designed for outdoor enthusiasts. This PHP-based application allows users to browse, add, and modify products, manage user accounts, and handle cart functionalities. The platform uses a MySQL database to store and manage data.

## Features
- **User Management**: Secure login and registration system for both admins and users.
- **Product Management**: Admins can add, modify, and delete products.
- **Cart Management**: Users can add products to their cart and adjust quantities.
- **Responsive Design**: Optimized for various devices.

## Project Structure

### PHP Files
- **add-user.php**: Handles user registration by adding a new user to the database.
- **admin.php**: Admin dashboard for managing products and users.
- **auth.php**: Authentication logic for login and session handling.
- **modify-admin.php**: Admin-specific file for editing admin details.
- **modify-product.php**: Allows admins to update product details.
- **user.php**: Handles individual user operations like profile management.
- **users.php**: Lists all registered users for admin management.

### Database Schema
The database `CampProDB` contains the following tables:

- **Products**
  - `id`: Primary key, auto-incremented.
  - `name`: Name of the product.
  - `price`: Price of the product.
  - `size`: Size specifications.
  - `description`: Detailed product description.
  - `gender`: Product gender category (Unisex, Male, Female).
  - `age`: Product age category (Kids, Adults, All Ages).
  - `type`: Product type (e.g., Tents, Sleeping Bags).
  - `image`: Path to the product image.

- **Admins**
  - `id`: Primary key, auto-incremented.
  - `username`: Unique admin username.
  - `password`: Securely hashed password.
  - `email`: Admin email address.

- **Users**
  - `id`: Primary key, auto-incremented.
  - `username`: Unique user username.
  - `password`: Securely hashed password.
  - `email`: User email address.

- **Cart**
  - `id`: Primary key, auto-incremented.
  - `user_id`: Foreign key referencing the `Users` table.
  - `product_id`: Foreign key referencing the `Products` table.
  - `quantity`: Quantity of the product in the cart.

### Database Initialization
To initialize the database, use the `db.txt` file which contains the SQL schema and sample data.

### Authentication
- **Login**: Secure authentication system using sessions.
- **Registration**: Validates and registers new users into the database.
- **Admin Access**: Admin-only routes for managing users and products.

## Technologies Used
- **PHP**: Backend logic and server-side scripting.
- **MySQL**: Database management.
- **HTML/CSS**: Frontend structure and design.
- **JavaScript**: Dynamic frontend interactions.

## How to Run
1. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/camp-pro-php.git
   ```
2. Set up a local server using XAMPP, WAMP, or similar.
3. Import the `db.txt` file into your MySQL database.
4. Place the project files in the server's root directory.
5. Open `auth.php` in the browser to test the login system.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

---

Feel free to contribute to this project by submitting pull requests or issues.
