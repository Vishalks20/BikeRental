<?php
session_start();
include 'db_connect.php'; // already contains connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // check account status (pending/approved)
        if ($row['status'] !== 'approved') {
            echo "<p style='color:red'>Account pending approval by Admin.</p>";
        } else {
            // verify hashed password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['full_name']; // match your users table column
                header("Location: index.html"); 
                exit;
            } else {
                echo "<p style='color:red'>Invalid password.</p>";
            }
        }
    } else {
        echo "<p style='color:red'>No account found with this email.</p>";
    }
}
?>
