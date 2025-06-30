<?php
session_start();
require_once '../includes/db.php';

// Only sellers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    die("Unauthorized access.");
}

// Ensure product ID is provided
if (!isset($_GET['id'])) {
    die("No product ID specified.");
}

$product_id = $_GET['id'];
$seller_id = $_SESSION['user_id'];

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Product not found or you do not have permission to edit it.");
}

$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | EasyShop</title>
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

  <!-- Edit Product Form -->
  <div class="container">
    <h2>Edit Product</h2>
    <form action="../api/edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
      <label for="name">Product Name</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

      <label for="description">Description</label>
      <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($product['description']); ?>" required>

      <label for="price">Price (ZAR)</label>
      <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required>

      <label for="category">Category</label>
      <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>

      <label for="image">Image URL</label>
      <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">

      <input type="submit" value="Update Product" class="btn-primary">
    </form>
  </div>

</body>
</html>
