<?php
session_start();

// Redirect to login if seller not logged in
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "seller") {
    header("Location: ../login.html");
    exit();
}

$seller_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product | EasyShop</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <a href="../index.html" class="logo">EasyShop</a>

      <ul class="nav-links" id="navMenu">
        <li class="dropdown">
          <a href="#">Account ▾</a>
          <ul class="dropdown-menu">
            <li><a href="/login-check.php">Login</a></li>
            <li><a href="/register.html">Register</a></li>
            <li><a href="/dashboard/my-orders.html">My Orders</a></li>
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
            <li><a href="add.php">Add Product</a></li>
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

        <li><a href="/cart.html">Cart <span id="cart-count">0</span></a></li>
      </ul>

      <button class="nav-toggle" id="navToggle">&#9776;</button>
    </div>
  </nav>

  <!-- Add Product Form -->
  <div class="container">
    <h2>Add New Product</h2>
    <form action="../api/add_product.php" method="POST">
      <input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">

      <label for="name">Product Name</label>
      <input type="text" id="name" name="name" required>

      <label for="description">Description</label>
      <input type="text" id="description" name="description" required>

      <label for="price">Price (ZAR)</label>
      <input type="number" step="0.01" id="price" name="price" required>

      <label for="category">Category:</label>
      <select id="category" name="category" required>
        <option value="">-- Select Category --</option>
        <option value="electronics">Electronics</option>
        <option value="clothing">Clothing</option>
        <option value="home">Home & Garden</option>
        <option value="books">Books</option>
        <option value="sports">Sports</option>
      </select>


      <label for="image">Image URL</label>
      <input type="text" id="image" name="image">

      <input type="submit" value="Add Product" class="btn-primary">
    </form>
  </div>

</body>
</html>
