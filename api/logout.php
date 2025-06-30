<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Optional: Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

// Redirect to login-check.php (ensure this path is correct based on your project structure)
header("Location: ../login-check.php");
exit();
