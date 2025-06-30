<?php
session_start();
require_once '../includes/db.php';

// Validate login and role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_GET['id'])) {
        die("Missing product ID.");
    }

    $product_id = intval($_GET['id']);
    $seller_id = $_SESSION['user_id'];

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $category = trim($_POST["category"]);
    $image = trim($_POST["image"]);

    // Validate fields
    if (empty($name) || empty($description) || empty($price) || empty($category)) {
        die("All fields except image are required.");
    }

    // Check if product exists and belongs to current seller
    $check = $conn->prepare("SELECT id FROM products WHERE id = ? AND seller_id = ?");
    $check->bind_param("ii", $product_id, $seller_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows !== 1) {
        die("Product not found or unauthorized access.");
    }
    $check->close();

    // Update product
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ssdssii", $name, $description, $price, $category, $image, $product_id, $seller_id);

    if ($stmt->execute()) {
        echo "Product updated successfully. <a href='../dashboard/seller-home.html'>Back to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
