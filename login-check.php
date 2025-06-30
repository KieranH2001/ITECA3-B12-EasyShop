<?php
session_start();

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: ../admin/admin-home.php");
            break;
        case 'seller':
            header("Location: ../dashboard/seller-home.html");
            break;
        case 'buyer':
            header("Location: ../dashboard/buyer-home.php");
            break;
        default:
            header("Location: ../index.html");
            break;
    }
    exit();
} else {
    // If no session, allow access to login page
    header("Location: login.html");
    exit();
}
?>
