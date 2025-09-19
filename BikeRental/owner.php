<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bikeName = $_POST['bikeName'];
    $bikePrice = $_POST['bikePrice'];
    $bikeLocation = $_POST['bikeLocation'];
    
    // You could also add bikeType here, but the bikes table doesn't have a column for it.
    // If you want to store bike type, you would need to alter your bikes table first.
    
    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bikes (name, price_per_day, location) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $bikeName, $bikePrice, $bikeLocation);

    if ($stmt->execute()) {
        // Redirect on success with a success message
        header("Location: ../owner.html?success=true");
        exit();
    } else {
        // Redirect on error with an error message
        header("Location: ../owner.html?error=true");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>