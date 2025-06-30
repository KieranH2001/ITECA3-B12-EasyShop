<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Ensure user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get and validate input
$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['order_id'] ?? null;
$buyerId = $_SESSION['user_id'];

if (!$orderId || !is_numeric($orderId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID.']);
    exit;
}

// Optional: check current status to prevent re-cancelling
$statusCheck = $conn->prepare("SELECT status FROM orders WHERE id = ? AND buyer_id = ?");
$statusCheck->bind_param('ii', $orderId, $buyerId);
$statusCheck->execute();
$statusCheck->bind_result($currentStatus);
if (!$statusCheck->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Order not found.']);
    exit;
}
$statusCheck->close();

if ($currentStatus === 'Canceled') {
    echo json_encode(['success' => false, 'message' => 'Order is already canceled.']);
    exit;
}

// Update status
$update = $conn->prepare("UPDATE orders SET status = 'Canceled' WHERE id = ? AND buyer_id = ?");
$update->bind_param('ii', $orderId, $buyerId);

if ($update->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel order.']);
}

$update->close();
$conn->close();
