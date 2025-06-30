<?php
session_start();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users | EasyShop Admin</title>
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
            <li><a href="../admin/admin-home.php">Dashboard</a></li>
            <li><a href="../admin/manage-users.php">Manage Users</a></li>
            <li><a href="../admin/manage-products.php">Manage Products</a></li>
            <li><a href="../admin/admin-reports.php">Reports</a></li>
          </ul>
        </li>

        <li><a href="../cart.html">Cart <span id="cart-count">0</span></a></li>
      </ul>

      <button class="nav-toggle" id="navToggle">&#9776;</button>
    </div>
  </nav>

  <div class="container">
    <h2>Manage Users</h2>
    <table>
      <thead>
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="users-body">
        <tr>
          <td colspan="6">Loading users...</td>
        </tr>
      </tbody>
    </table>
  </div>

  <script src="../js/manage-users.js"></script>

</body>
</html>
