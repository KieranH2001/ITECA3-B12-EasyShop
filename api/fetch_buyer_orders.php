<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Only allow logged-in buyers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

$buyerId = $_SESSION['user_id'];

// Fetch orders and related items
$sql = "
    SELECT 
        o.id AS order_id,
        o.created_at AS order_date,
        o.total_amount,
        o.shipping_cost,
        o.region,
        o.status,
        p.name AS product_name,
        p.image AS product_image,
        oi.quantity,
        oi.price
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.buyer_id = ?
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $buyerId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];

    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'order_id' => $orderId,
            'order_date' => $row['order_date'],
            'total_amount' => $row['total_amount'],
            'shipping_cost' => $row['shipping_cost'],
            'region' => $row['region'],
            'status' => $row['status'],
            'items' => []
        ];
    }

    $orders[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'product_image' => $row['product_image'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}

echo json_encode(array_values($orders));

$stmt->close();
$conn->close();
