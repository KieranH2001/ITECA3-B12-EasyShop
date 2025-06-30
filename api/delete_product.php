<?php
session_start();
require_once '../includes/db.php';
header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['seller', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Input validation
$data = json_decode(file_get_contents("php://input"), true);
$productId = isset($data['id']) ? intval($data['id']) : 0;

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit;
}

// Delete logic
if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
} else {
    $sellerId = $_SESSION['user_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $sellerId);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
}

$stmt->close();
$conn->close();
