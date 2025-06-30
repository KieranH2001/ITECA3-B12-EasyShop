let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderCart() {
  const cartItemsDiv = document.getElementById("cart-items");
  const cartTotalSpan = document.getElementById("cart-total");
  cartItemsDiv.innerHTML = "";

  if (cart.length === 0) {
    cartItemsDiv.innerHTML = "<p>Your cart is empty.</p>";
    cartTotalSpan.innerText = "0.00";
    return;
  }

  let total = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price;
    total += itemTotal;

    const itemDiv = document.createElement("div");
    itemDiv.className = "product-card";
    itemDiv.innerHTML = `
      <h4>${item.name}</h4>
      <p>Price: R${item.price.toFixed(2)}</p>
      <button onclick="removeItem(${index})" class="btn-primary">Remove</button>
    `;

    cartItemsDiv.appendChild(itemDiv);
  });

  cartTotalSpan.innerText = total.toFixed(2);
}

function removeItem(index) {
  cart.splice(index, 1);
  saveCart();
  renderCart();
}

function clearCart() {
  if (confirm("Clear all items from cart?")) {
    cart = [];
    saveCart();
    renderCart();
  }
}

function saveCart() {
  localStorage.setItem("cart", JSON.stringify(cart));
}

document.addEventListener("DOMContentLoaded", renderCart);
