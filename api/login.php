<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        die("Error: Both email and password fields are required.");
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Role-based redirection
            switch ($role) {
                case 'buyer':
                    header("Location: ../dashboard/buyer-home.php");
                    break;
                case 'seller':
                    header("Location: ../dashboard/seller-home.html");
                    break;
                case 'admin':
                    header("Location: ../admin/admin-home.php");
                    break;
                default:
                    echo "Error: Unknown user role.";
                    exit;
            }

            exit;
        } else {
            echo "Error: Incorrect password.";
        }
    } else {
        echo "Error: No account found with that email.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: Invalid request method.";
}
