<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]); 
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email already exists.");
    }
    $check->close();

    // Insert new user with phone
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $phone, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='../login-check.php'>Login here</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
