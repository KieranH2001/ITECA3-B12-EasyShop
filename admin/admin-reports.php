<?php
session_start();
include '../includes/db.php'; // Adjust if your DB path is different

// Block users who are not admins
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Access Denied</title>
        <link rel="stylesheet" href="../css/style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8d7da;
                color: #721c24;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .access-denied {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
            }
            .access-denied a {
                display: inline-block;
                margin-top: 15px;
                color: #721c24;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="access-denied">
            <h2>Access Denied</h2>
            <p>You do not have permission to access this page.</p>
            <a href="../index.html">Return to Home</a>
        </div>
    </body>
    </html>
    HTML;
    exit;
}

// Queries
$user_query = "SELECT role, COUNT(*) AS count FROM users GROUP BY role";
$user_result = $conn->query($user_query);

$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = $conn->query($product_query);
$product_data = $product_result->fetch_assoc();

$order_query = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports | EasyShop Admin</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .report-card {
        background-color: #f5f5f5;
        border-left: 6px solid #007BFF;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .report-card h3 {
        margin-bottom: 10px;
    }

    .report-card ul {
        list-style: none;
        padding-left: 0;
    }

    .report-card li {
        padding: 5px 0;
    }

    h2 {
        margin-bottom: 30px;
        text-align: center;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="../index.html" class="logo">EasyShop</a>
      <ul class="nav-links" id="navMenu">
        <li class="dropdown">
          <a href="#">Account ▾</a>
          <ul class="dropdown-menu">
            <li><a href="../login-check.php">Login</a></li>
            <li><a href="../register.html">Register</a></li>
            <li><a href="../dashboard/my-orders.html">My Orders</a></li>
            <li><a href="../api/logout.php">Logout</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Buyer ▾</a>
          <ul class="dropdown-menu">
            <li><a href="../dashboard/buyer-home.php">Dashboard</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Seller ▾</a>
          <ul class="dropdown-menu">
            <li><a href="../dashboard/seller-home.html">Dashboard</a></li>
            <li><a href="../products/add.php">Add Product</a></li>
            <li><a href="../dashboard/my-listings.php">My Listings</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Admin ▾</a>
          <ul class="dropdown-menu">
            <li><a href="admin-home.php">Dashboard</a></li>
            <li><a href="manage-users.php">Manage Users</a></li>
            <li><a href="manage-products.php">Manage Products</a></li>
            <li><a href="admin-reports.php">Reports</a></li>
          </ul>
        </li>
        <li><a href="../cart.html">Cart <span id="cart-count">0</span></a></li>
      </ul>
      <button class="nav-toggle" id="navToggle">&#9776;</button>
    </div>
  </nav>

  <div class="container">
    <h2>Admin Reports Overview</h2>

    <div class="report-card">
      <h3>Total Users</h3>
      <ul>
        <?php while($row = $user_result->fetch_assoc()): ?>
          <li><?php echo ucfirst($row['role']); ?>: <?php echo $row['count']; ?></li>
        <?php endwhile; ?>
      </ul>
    </div>

    <div class="report-card">
      <h3>Total Products</h3>
      <p><?php echo $product_data['total_products']; ?></p>
    </div>

    <div class="report-card">
      <h3>Orders by Status</h3>
      <ul>
        <?php while($row = $order_result->fetch_assoc()): ?>
          <li><?php echo ucfirst($row['status']); ?>: <?php echo $row['count']; ?></li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>

</body>
</html>
