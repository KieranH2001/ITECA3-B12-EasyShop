document.addEventListener("DOMContentLoaded", async () => {
  const order = JSON.parse(localStorage.getItem("latestOrder"));
  if (!order || order.length === 0) {
    document.getElementById("order-summary").innerHTML = "<p>No order found.</p>";
    return;
  }

  const summaryDiv = document.getElementById("order-summary");
  summaryDiv.innerHTML = "<h3>Order Summary</h3>";

  order.forEach(product => {
    const div = document.createElement("div");
    div.className = "product-card";
    div.innerHTML = `
      <h4>${product.name}</h4>
      <p>Price: R${product.price}</p>
    `;
    summaryDiv.appendChild(div);
  });

  // Get product IDs from order
  const productIds = order.map(p => p.id);

  // Load seller info
  const sellerInfoDiv = document.getElementById("seller-info");
  sellerInfoDiv.innerHTML = "<h3>Seller Information</h3>";

  try {
    const response = await fetch("api/fetch_seller_info.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ productIds })
    });

    const data = await response.json();

    if (data.sellers && data.sellers.length > 0) {
      data.sellers.forEach(seller => {
        sellerInfoDiv.innerHTML += `
          <p><strong>Name:</strong> ${seller.username}</p>
          <p><strong>Email:</strong> ${seller.email}</p>
          <p><strong>Phone:</strong> ${seller.phone || 'Not available'}</p>
          <hr>
        `;
      });
    } else {
      sellerInfoDiv.innerHTML += "<p>Seller details not found.</p>";
    }
  } catch (err) {
    sellerInfoDiv.innerHTML += "<p>Error fetching seller details.</p>";
    console.error("Fetch error:", err);
  }
});
