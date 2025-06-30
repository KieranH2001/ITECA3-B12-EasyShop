<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Only allow admins to access this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$sql = "SELECT id, username, email, phone, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

$conn->close();
?>
