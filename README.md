🏍️ Apex Bike Rental

A web application for browsing, booking, and managing bike rentals. This project features a user authentication system, an admin panel for user approval, and a booking system.

✨ Features

    User Authentication: Secure user registration and login (signup.php, login.php).

    Admin Panel: An exclusive admin interface to approve or reject new user registrations (admin_panel.php).

    Bike Catalog: Browse and search for available bikes in different regions.

    Booking System: Users can book bikes for a specified duration, with automatic price calculation.

    Bootstrap Integration: A modern, responsive user interface built with Bootstrap.

🚀 Getting Started

To get this project up and running, you need a local server environment with PHP and a MySQL database.

Prerequisites

    PHP: Version 7.4 or higher.

    MySQL: Database server.

    Local Server: A software package like XAMPP, WAMP, or MAMP.

Installation

    Place Files: Copy all the project files (.html, .php, css, assets) into the root directory of your local server (e.g., C:\xampp\htdocs\).

    Create a php folder in your project root and place all the .php files inside it.

Database Setup

    Open your web browser and navigate to phpMyAdmin (http://localhost/phpmymyadmin).

    Create a new database named apex_bike_rental.

    Execute the following SQL commands to create the required tables:

SQL

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user' NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' NOT NULL
);

CREATE TABLE `bikes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price_per_day` DECIMAL(10, 2) NOT NULL,
  `location` VARCHAR(255) NOT NULL
);

CREATE TABLE `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `bike_id` INT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_days` INT NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`bike_id`) REFERENCES `bikes`(`id`)
);

    Configure db_connect.php: Open the db_connect.php file and update the $username and $password if your database credentials are not the default root with no password.

📁 Project Structure

    index.html: The home page with a bike search feature.

    bikes.html: Displays the catalog of available bikes.

    booking.html: The booking form for selecting rental dates.

    login.html: User login page.

    signup.html: User registration page.

    owner.html: Page for bike owners to list their bikes.

    billing.html: Displays a summary of the booking and total cost.

    css/style.css: Custom CSS for general styling.

    assets/: Contains project images.

    php/:

        db_connect.php: Manages the database connection.

        signup.php: Handles new user registration.

        login.php: Authenticates user logins.

        booking.php: Processes bike booking requests.

        admin_login.php: Admin login handler.

        admin_panel.php: Admin dashboard for managing user approvals.

        auth_check.php: A script to ensure a user is logged in before accessing certain pages.

🗺️ Usage

User Flow

    Sign Up: Create a new account on signup.html. Your account will be in a "pending" state.

    Admin Approval: An administrator must log in and approve your account.

    Log In: Once approved, log in on login.html.

    Book a Bike: Browse bikes on bikes.html, click "Book Now," and fill out the booking form to complete your reservation.

Admin Flow

    Admin Account: Create a manual admin user in your database by running the generate_hash.php file to get a hashed password and updating the user's role to admin in the users table.

    Log In: Go to admin_login.php to log in with your admin credentials.

    Approve Users: On admin_panel.php, you can see pending user accounts and approve or reject them.
