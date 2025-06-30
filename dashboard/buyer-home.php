<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EasyShop | Buyer Dashboard</title>
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

  <header>
    <div class="container">
      <h2>EasyShop Buyer Dashboard <span id="cart-count">(Cart: 0)</span></h2>
    </div>
  </header>

  <main class="container">
    <div class="filter-section">
      <form id="product-filters">
        <input type="text" id="searchInput" placeholder="Search by name...">

        <select id="categoryFilter">
          <option value="">All Categories</option>
          <option value="electronics">Electronics</option>
          <option value="clothing">Clothing</option>
          <option value="home">Home & Garden</option>
          <option value="books">Books</option>
          <option value="sports">Sports</option>
        </select>

        <input type="number" id="minPrice" placeholder="Min price (R)">
        <input type="number" id="maxPrice" placeholder="Max price (R)">

        <button type="submit" class="btn-primary">Apply Filters</button>
      </form>
    </div>

    <h3>Available Products</h3>
    <section class="product-grid" id="product-list">
      <?php
      $sql = "SELECT name, description, price, image FROM products ORDER BY created_at DESC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='product-card'>";
          echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Product Image'>";
          echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
          echo "<p>" . htmlspecialchars($row['description']) . "</p>";
          echo "<p><strong>R" . number_format($row['price'], 2) . "</strong></p>";
          echo "<button class='btn-primary'>Add to Cart</button>";
          echo "</div>";
        }
      } else {
        echo "<p>No products available.</p>";
      }
      ?>
    </section>
  </main>

  <script src="../js/script.js"></script>

</body>
</html>
