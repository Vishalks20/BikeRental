<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Start a session and store user info (for future use)
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;

            // Redirect to a dashboard or homepage
            header("Location: ../index.html");
        } else {
            // Password incorrect
            header("Location: ../login.html?error=password");
        }
    } else {
        // Email not found
        header("Location: ../login.html?error=email");
    }

    $stmt->close();
}

$conn->close();
?>