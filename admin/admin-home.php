<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Access Denied</title>
        <link rel="stylesheet" href="../css/style.css"> <!-- Optional -->
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 100px;
                background-color: #f8d7da;
                color: #721c24;
            }
            .access-denied {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 20px;
                display: inline-block;
                border-radius: 8px;
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
    <?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EasyShop | Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
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
  <header>
    <div class="container">
      <h2>EasyShop Admin Panel</h2>
    </div>
  </header>

  <main class="container">
    <nav class="admin-nav">
      <ul>
        <li><a href="manage-users.php">Manage Users</a></li>
        <li><a href="manage-products.php">Manage Products</a></li>
        <li><a href="admin-reports.php">Generate Reports</a></li>
      </ul>
    </nav>
  </main>

</body>
</html>
