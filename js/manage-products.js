document.addEventListener("DOMContentLoaded", async () => {
  const tbody = document.querySelector("tbody");

  try {
    const response = await fetch("../api/fetch_products.php");
    const products = await response.json();

    tbody.innerHTML = ""; 

    if (!Array.isArray(products) || products.length === 0) {
      tbody.innerHTML = "<tr><td colspan='6'>No products found.</td></tr>";
      return;
    }

    products.forEach(product => {
      const row = document.createElement("tr");
      row.setAttribute("data-id", product.id);

      row.innerHTML = `
        <td>${product.id}</td>
        <td>${product.name}</td>
        <td>${product.seller_email || "Unknown"}</td>
        <td>R${parseFloat(product.price).toFixed(2)}</td>
        <td>Active</td>
        <td>
          <a href="#" onclick="editProduct(${product.id})">Edit</a> |
          <a href="#" onclick="deleteProduct(${product.id})">Remove</a>
        </td>
      `;

      tbody.appendChild(row);
    });
  } catch (error) {
    console.error("Error loading products:", error);
    tbody.innerHTML = "<tr><td colspan='6'>Failed to load products.</td></tr>";
  }
});

function editProduct(productId) {
  alert("Edit functionality coming soon for product ID: " + productId);
}

function deleteProduct(productId) {
  if (!confirm("Are you sure you want to delete this product?")) return;

  fetch("../api/delete_product.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    credentials: "include", 
    body: JSON.stringify({ id: productId }) 
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("Product deleted successfully.");
      document.querySelector(`tr[data-id="${productId}"]`).remove(); 
    } else {
      alert("Error: " + data.message);
    }
  })
  .catch(err => {
    console.error("Delete error:", err);
    alert("Something went wrong: " + err.message);
  });
}
