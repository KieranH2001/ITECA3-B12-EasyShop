<?php
session_start();
require_once '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$buyerId = $_SESSION['user_id'];
$total = $data['total'];
$region = $data['region'];
$shippingCost = $data['shippingCost'];
$items = $data['items'];

if (empty($items) || $total <= 0 || empty($region)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order data.']);
    exit;
}

$conn->begin_transaction();

try {
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (buyer_id, total_amount, region, shipping_cost) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $buyerId, $total, $region, $shippingCost);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    // Insert each item into order_items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $productId = (int)$item['id'];
        $quantity = 1;
        $price = (float)$item['price'];
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
        $stmt->execute();
    }
    $stmt->close();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
}
?>
