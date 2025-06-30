<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

// Validate method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Read and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['userId']) || !is_numeric($data['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
    exit;
}

$userId = (int)$data['userId'];

// Prevent deleting the currently logged-in admin
if ($_SESSION['user_id'] == $userId) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own admin account.']);
    exit;
}

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}

$stmt->close();
$conn->close();
