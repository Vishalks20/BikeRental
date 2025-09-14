<?php
// signup.php
include("db_connect.php"); // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullName'];
    $email = $_POST['signupEmail'];
    $password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);
    $aadhar = $_POST['aadharNumber'];

    // File upload handling
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create folder if not exists
    }
    $licenseFile = $targetDir . basename($_FILES["licenseFile"]["name"]);

    if (move_uploaded_file($_FILES["licenseFile"]["tmp_name"], $licenseFile)) {
        // Insert into DB
        $sql = "INSERT INTO users (name, email, password, aadhar, license_file)
                VALUES ('$name', '$email', '$password', '$aadhar', '$licenseFile')";

        if (mysqli_query($conn, $sql)) {
            echo "✅ Signup successful! You can now login.";
        } else {
            echo "❌ Error: " . mysqli_error($conn);
        }
    } else {
        echo "❌ Error uploading license file.";
    }
    $sql = "INSERT INTO users (name, email, password, aadhar, license_file, status)
        VALUES ('$name', '$email', '$password', '$aadhar', '$licenseFile', 'pending')";

}
?>
