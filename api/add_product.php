<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in and is a seller
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    die("Unauthorized access. Please log in as a seller.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $seller_id = $_SESSION['user_id'];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $category = trim($_POST["category"]);
    $image = trim($_POST["image"]);

    // Validate required fields
    if (empty($name) || empty($description) || empty($price) || empty($category)) {
        die("All fields except image are required.");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $seller_id, $name, $description, $price, $category, $image);

    // Execute and respond
    if ($stmt->execute()) {
        echo "Product added successfully. <a href='../products/add.php'>Add another</a> or <a href='../dashboard/seller-home.html'>go to Dashboard</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
