let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderCheckout() {
  const checkoutItems = document.getElementById("checkout-items");
  const orderTotalSpan = document.getElementById("order-total");
  let total = 0;

  if (cart.length === 0) {
    checkoutItems.innerHTML = "<p>Your cart is empty.</p>";
    orderTotalSpan.innerText = "0.00";
    return 0;
  }

  cart.forEach(item => {
    const itemDiv = document.createElement("div");
    itemDiv.className = "product-card";
    itemDiv.innerHTML = `
      <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.name}" style="width: 100px; height: 100px; object-fit: cover;">
      <h4>${item.name}</h4>
      <p>Price: R${parseFloat(item.price).toFixed(2)}</p>
    `;
    checkoutItems.appendChild(itemDiv);
    total += parseFloat(item.price);
  });

  orderTotalSpan.innerText = total.toFixed(2);
  return total;
}

function calculateShipping(region) {
  switch (region) {
    case "south-africa": return 100;
    case "africa": return 250;
    case "international": return 400;
    default: return 0;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  let baseTotal = renderCheckout();

  const regionSelect = document.getElementById("region");
  const shippingCostSpan = document.getElementById("shipping-cost");
  const orderTotalSpan = document.getElementById("order-total");

  regionSelect.addEventListener("change", () => {
    const shippingCost = calculateShipping(regionSelect.value);
    shippingCostSpan.innerText = shippingCost;
    const total = baseTotal + shippingCost;
    orderTotalSpan.innerText = total.toFixed(2);
  });

  document.getElementById("checkout-form").addEventListener("submit", async (e) => {
    e.preventDefault();

    if (cart.length === 0) {
      alert("Your cart is empty.");
      return;
    }

    const name = document.getElementById("name").value.trim();
    const address = document.getElementById("address").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const region = document.getElementById("region").value;

    if (!name || !address || !phone || !region) {
      alert("Please fill in all required fields.");
      return;
    }

    const shippingCost = calculateShipping(region);
    const totalAmount = baseTotal + shippingCost;

    // Minimal payload matching backend expectation
    const orderData = {
      total: totalAmount,
      region: region,
      shippingCost: shippingCost,
      items: cart
    };

    try {
      const response = await fetch("api/place_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(orderData)
      });

      const result = await response.json();

      if (result.success) {
        localStorage.setItem("latestOrder", JSON.stringify(cart));
        localStorage.removeItem("cart");

        alert("Order placed successfully! Redirecting to seller contact page.");
        window.location.href = "seller-contact.html";
      } else {
        alert("Error placing order: " + result.message);
      }

    } catch (error) {
      console.error("Error placing order:", error);
      alert("Something went wrong while placing your order.");
    }
  });
});
