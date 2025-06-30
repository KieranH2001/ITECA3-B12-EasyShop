<?php
session_start();
require_once '../includes/db.php';

// Check if the user is a logged-in seller
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    die("Access denied. Only sellers can view this page.");
}

$seller_id = $_SESSION['user_id'];

// Fetch products for the logged-in seller
$query = $conn->prepare("SELECT id, name, description, price, category, created_at FROM products WHERE seller_id = ?");
$query->bind_param("i", $seller_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Listings | EasyShop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- Navigation Bar -->
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
            <li><a href="seller-home.html">Dashboard</a></li>
            <li><a href="../products/add.php">Add Product</a></li>
            <li><a href="my-listings.php">My Listings</a></li>
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

  <!-- Product Listings -->
  <main class="container">
    <h2>My Product Listings</h2>

    <table class="orders-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Price (R)</th>
          <th>Category</th>
          <th>Date Added</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= number_format($row['price'], 2) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="../products/edit.php?id=<?= $row['id'] ?>">View/Edit</a> |
            <a href="../api/delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>

  <script src="../js/script.js"></script>

</body>
</html>
