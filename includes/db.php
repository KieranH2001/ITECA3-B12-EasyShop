<?php
$servername = "sql109.infinityfree.com";
$username = "if0_39165285";
$password = "Stormblack2001";
$database = "if0_39165285_easyshop";

$conn = new mysqli('sql109.infinityfree.com', 'if0_39165285', 'Stormblack2001', 'if0_39165285_easyshop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
