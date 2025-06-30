<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Join with users to fetch seller email
    $query = "
        SELECT 
            products.id, 
            products.name, 
            products.description, 
            products.price, 
            products.category, 
            products.image,
            users.email AS seller_email
        FROM products
        JOIN users ON products.seller_id = users.id
        ORDER BY products.created_at DESC
    ";

    $result = $conn->query($query);
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => (float)$row['price'],
            'category' => $row['category'],
            'image' => $row['image'] ?: 'https://via.placeholder.com/150',
            'seller_email' => $row['seller_email']
        ];
    }

    echo json_encode($products);

} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch products.']);
}

$conn->close();
?>
