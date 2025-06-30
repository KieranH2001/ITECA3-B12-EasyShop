<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !is_numeric($data['id']) || !isset($data['role'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$userId = (int)$data['id'];
$role = $data['role'];

if (!in_array($role, ['buyer', 'seller', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role.']);
    exit;
}

// Update user role
$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $role, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User role updated.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update role.']);
}

$stmt->close();
$conn->close();
