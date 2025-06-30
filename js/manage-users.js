// manage-users.js

document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await fetch("../api/fetch_users.php");
    const users = await response.json();

    const usersBody = document.getElementById("users-body");
    usersBody.innerHTML = "";

    if (!Array.isArray(users) || users.length === 0) {
      usersBody.innerHTML = "<tr><td colspan='6'>No users found.</td></tr>";
      return;
    }

    const currentAdminId = parseInt(sessionStorage.getItem("admin_id"));

    users.forEach(user => {
      const row = document.createElement("tr");

      // Prevent admin from editing their own role
      let roleDropdown = '';
      if (user.id === currentAdminId) {
        roleDropdown = `<span>${user.role}</span>`;
      } else {
        roleDropdown = `
          <select data-user-id="${user.id}" class="role-dropdown">
            <option value="buyer" ${user.role === "buyer" ? "selected" : ""}>Buyer</option>
            <option value="seller" ${user.role === "seller" ? "selected" : ""}>Seller</option>
            <option value="admin" ${user.role === "admin" ? "selected" : ""}>Admin</option>
          </select>
        `;
      }

      row.innerHTML = `
        <td>${user.id}</td>
        <td>${user.username}</td>
        <td>${user.email}</td>
        <td>${user.phone || '-'}</td>
        <td>${roleDropdown}</td>
        <td>
          <button class="delete-btn" data-user-id="${user.id}">Delete</button>
        </td>
      `;

      usersBody.appendChild(row);
    });
  } catch (error) {
    console.error("Error loading users:", error);
    document.getElementById("users-body").innerHTML = "<tr><td colspan='6'>Error loading users.</td></tr>";
  }
});

// Role change handler

document.addEventListener("change", async (e) => {
  if (e.target.classList.contains("role-dropdown")) {
    const userId = e.target.getAttribute("data-user-id");
    const newRole = e.target.value;

    try {
      const res = await fetch("../api/edit_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ userId, role: newRole })
      });
      const result = await res.json();
      alert(result.message);
    } catch (err) {
      console.error("Error updating user role:", err);
      alert("Failed to update role.");
    }
  }
});

// Delete handler

document.addEventListener("click", async (e) => {
  if (e.target.classList.contains("delete-btn")) {
    const userId = parseInt(e.target.getAttribute("data-user-id"));

    if (!confirm("Are you sure you want to delete this user?")) return;

    try {
      const res = await fetch("../api/delete_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ userId }) // 
      });
      const result = await res.json();
      alert(result.message);
      location.reload();
    } catch (err) {
      console.error("Error deleting user:", err);
      alert("Failed to delete user.");
    }
  }
});

