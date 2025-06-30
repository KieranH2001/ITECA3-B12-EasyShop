document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await fetch("../api/fetch_buyer_orders.php");
    const orders = await response.json();

    const ordersBody = document.getElementById("orders-body");

    if (!Array.isArray(orders) || orders.length === 0) {
      ordersBody.innerHTML = "<tr><td colspan='6'>No orders found.</td></tr>";
      return;
    }

    ordersBody.innerHTML = "";

    orders.forEach(order => {
      order.items.forEach(item => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>${order.order_id}</td>
          <td>${item.product_name}</td>
          <td>${order.order_date}</td>
          <td>R${parseFloat(item.price).toFixed(2)}</td>
          <td>${order.status || "Pending"}</td>
          <td><button class="btn-danger" onclick="cancelOrder(${order.order_id}, this)">Cancel</button></td>
        `;

        ordersBody.appendChild(row);
      });
    });
  } catch (error) {
    console.error("Error loading orders:", error);
    document.getElementById("orders-body").innerHTML = "<tr><td colspan='6'>Error loading orders.</td></tr>";
  }
});

async function cancelOrder(orderId, buttonElement) {
  if (!confirm("Are you sure you want to cancel this order?")) return;

  try {
    const response = await fetch("../api/cancel_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ order_id: orderId })
    });

    const result = await response.json();
    if (result.success) {
      alert("Order canceled successfully.");
      buttonElement.closest("tr").remove();
    } else {
      alert("Error: " + result.message);
    }
  } catch (error) {
    console.error("Cancellation error:", error);
    alert("An error occurred while trying to cancel the order.");
  }
}
