<?php
session_start();
require 'db_connect.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bike_id = $_POST['bike_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'];

    // Calculate total days
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $total_days = $interval->days;

    if ($total_days <= 0) {
        die("Invalid booking duration!");
    }

    // Get bike price per day
    $stmt = $conn->prepare("SELECT name, price_per_day FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $bike_id);
    $stmt->execute();
    $bike = $stmt->get_result()->fetch_assoc();

    if (!$bike) {
        die("Bike not found!");
    }

    $price_per_day = $bike['price_per_day'];
    $total_amount = $price_per_day * $total_days;

    // Save booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, bike_id, start_date, end_date, total_days, total_amount) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissid", $user_id, $bike_id, $start_date, $end_date, $total_days, $total_amount);
    
    if ($stmt->execute()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Booking Confirmation</title>
            <link rel="stylesheet" href="../css/style.css">
        </head>
        <body style="background-image: url('../assets/BgimgBike.jpeg'); color: black;">
            <div class="form-container" style="background: white; padding: 20px; border-radius: 10px;">
                <h2>Booking Confirmed ✅</h2>
                <p><strong>Bike:</strong> <?php echo htmlspecialchars($bike['name']); ?></p>
                <p><strong>From:</strong> <?php echo $start_date; ?></p>
                <p><strong>To:</strong> <?php echo $end_date; ?></p>
                <p><strong>Total Days:</strong> <?php echo $total_days; ?></p>
                <p><strong>Price/Day:</strong> ₹<?php echo $price_per_day; ?></p>
                <p><strong>Total Amount:</strong> ₹<?php echo $total_amount; ?></p>
                <a href="../index.html"><button>Back to Home</button></a>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Error while booking: " . $stmt->error;
    }
}
?>
