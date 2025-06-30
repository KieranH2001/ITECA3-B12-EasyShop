// Global cart array from localStorage
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Format category name
function formatCategory(category) {
  return category.replace("-", " ").replace(/\b\w/g, l => l.toUpperCase());
}

// Fetch products from server
async function fetchProducts() {
  try {
    const response = await fetch("../api/fetch_products.php");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching products:", error);
    return [];
  }
}

// Render product cards
function renderProducts(productList) {
  const container = document.getElementById("product-list");
  container.innerHTML = "";

  if (productList.length === 0) {
    container.innerHTML = "<p>No products found.</p>";
    return;
  }

  productList.forEach((product, index) => {
    const card = document.createElement("div");
    card.className = "product-card";

    card.innerHTML = `
      <img src="${product.image}" alt="${product.name}" style="max-width: 100%;">
      <h4>${product.name}</h4>
      <p>R${parseFloat(product.price).toFixed(2)}</p>
      <p><small>${formatCategory(product.category)}</small></p>
      <button class="btn-primary" onclick="addToCart(${index})">Add to Cart</button>
    `;

    container.appendChild(card);
  });
}

// Handle adding item to cart
function addToCart(index) {
  const product = window.filteredProducts[index];
  const productToCart = {
    id: product.id,
    name: product.name,
    price: product.price,
    image: product.image 
  };
  cart.push(productToCart);
  localStorage.setItem("cart", JSON.stringify(cart));
  alert(`${product.name} added to cart.`);
  updateCartCount();
}

// Update cart item count in navbar
function updateCartCount() {
  const cartCountElement = document.getElementById("cart-count");
  if (cartCountElement) {
    cartCountElement.innerText = `(Cart: ${cart.length})`;
  }
}

// Apply filters
function applyFilters(event) {
  event.preventDefault();

  const searchText = document.getElementById("searchInput").value.toLowerCase();
  const selectedCategory = document.getElementById("categoryFilter").value;
  const minPrice = parseFloat(document.getElementById("minPrice").value) || 0;
  const maxPrice = parseFloat(document.getElementById("maxPrice").value) || Infinity;

  const filtered = window.allProducts.filter(product => {
    const matchesName = product.name.toLowerCase().includes(searchText);
    const matchesCategory = selectedCategory === "" || product.category === selectedCategory;
    const matchesPrice = parseFloat(product.price) >= minPrice && parseFloat(product.price) <= maxPrice;
    return matchesName && matchesCategory && matchesPrice;
  });

  window.filteredProducts = filtered;
  renderProducts(filtered);
}

// Setup on page load
document.addEventListener("DOMContentLoaded", async () => {
  window.allProducts = await fetchProducts();
  window.filteredProducts = [...window.allProducts];

  renderProducts(window.filteredProducts);
  document.getElementById("product-filters")?.addEventListener("submit", applyFilters);
  updateCartCount();

  // Responsive nav
  const navToggle = document.getElementById("navToggle");
  const navMenu = document.getElementById("navMenu");

  navToggle?.addEventListener("click", () => {
    navMenu.classList.toggle("show");
  });

  document.querySelectorAll(".dropdown > a").forEach(toggle => {
    toggle.addEventListener("click", e => {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        toggle.parentElement.classList.toggle("show");
      }
    });
  });
});

// Logout function
function logout() {
  localStorage.clear();
  alert("You have been logged out.");
  window.location.href = "../login.html";
}
