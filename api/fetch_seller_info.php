<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $productIds = $data['productIds'] ?? [];

    if (empty($productIds)) {
        echo json_encode(["error" => "No product IDs provided."]);
        exit;
    }

    // Sanitize IDs for SQL
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $types = str_repeat('i', count($productIds));

    $sql = "SELECT p.id AS product_id, u.username, u.email, u.phone 
            FROM products p
            JOIN users u ON p.seller_id = u.id
            WHERE p.id IN ($placeholders)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$productIds);
    $stmt->execute();

    $result = $stmt->get_result();
    $sellers = [];

    while ($row = $result->fetch_assoc()) {
        $sellers[] = $row;
    }

    echo json_encode(["sellers" => $sellers]);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
