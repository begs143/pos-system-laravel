document.addEventListener("click", function (e) {
    if (!e.target.classList.contains("add-to-cart")) return;

    e.preventDefault();

    const btn = e.target;
    const id = btn.dataset.id;
    const name = btn.dataset.name;
    const price = parseFloat(btn.dataset.price); // keep as number
    const unit = btn.dataset.unit;
    const image = btn.dataset.image;
    const stock = parseInt(btn.dataset.stock || 0); // ✅ stock quantity

    const cartBody = document.getElementById("cart-body");

    // Remove empty message
    const emptyRow = document.getElementById("empty-cart");
    if (emptyRow) emptyRow.remove();

    // Check if item already exists
    let existingRow = cartBody.querySelector(`tr[data-id="${id}"]`);

    if (existingRow) {
        const input = existingRow.querySelector("input");
        const rowStock = parseInt(existingRow.dataset.stock || 0);
        const rowUnit = existingRow.dataset.unit || "items";
        let val = parseInt(input.value || 1);

        if (val < rowStock) {
            input.value = val + 1;
            updateCartTotals();
        } else {
            alert(`Cannot add more than ${rowStock} ${rowUnit}`);
        }
        return;
    }
    // Create new row
    const row = document.createElement("tr");
    row.setAttribute("data-id", id);
    row.setAttribute("data-unit", unit);
    row.setAttribute("data-stock", stock);

    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${image}" class="avatar avatar-md rounded">
                <span class="ms-3 fw-semibold">${name}</span>
            </div>
        </td>

        <td class="py-3 fw-semibold">
    ₱${price.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
</td>


        <td class="py-3">
            <div class="d-inline-flex align-items-center border rounded">
                <button type="button" class="btn btn-sm btn-light px-2 rounded-0"
                    onclick="decreaseQty(this)">−</button>

                <input type="text"
                       inputmode="numeric"
                       maxlength="3"
                       class="form-control form-control-sm text-center border-0"
                       value="1"
                       style="width:40px;"
                       oninput="this.value = this.value.replace(/\\D/g, ''); checkStock(this, ${stock}); updateCartTotals();">

                <button type="button" class="btn btn-sm btn-light px-2 ms-1 rounded-0"
                    onclick="increaseQty(this, ${stock})">+</button>
            </div>
        </td>

        <td class="py-3">
            <button class="btn btn-sm btn-secondary"
                onclick="removeRow(this)">Remove</button>
        </td>
    `;

    cartBody.appendChild(row);

    updateCartTotals();
});

// Qty logic with stock check
function increaseQty(btn) {
    const row = btn.closest("tr");
    const stock = parseInt(row.dataset.stock || 0); // ✅ get stock from row
    const unit = row.dataset.unit || "items";
    const input = btn.previousElementSibling;
    let val = parseInt(input.value || 0);

    if (val < stock) {
        input.value = val + 1;
        updateCartTotals();
    } else {
        alert(`Cannot add more than ${stock} ${unit}`);
    }
}

function decreaseQty(btn) {
    const input = btn.nextElementSibling;
    let val = parseInt(input.value || 1);
    if (val > 1) input.value = val - 1;

    updateCartTotals();
}

// Check stock when manually typing
function checkStock(input, stock) {
    const row = input.closest("tr");
    const unit = row.dataset.unit || "items";
    let val = parseInt(input.value || 0);

    if (val > stock) {
        input.value = stock;
        alert(`Only ${stock} ${unit} in stock`);
    } else if (val < 1) {
        input.value = 1;
    }
}

// Remove row
function removeRow(btn) {
    const row = btn.closest("tr");
    row.remove();

    const cartBody = document.getElementById("cart-body");
    if (!cartBody.children.length) {
        cartBody.innerHTML = `
            <tr class="text-muted text-center" id="empty-cart">
                <td colspan="5">No items in cart</td>
            </tr>
        `;
    }

    updateCartTotals(); // ✅ Update totals
}

// Function to calculate totals
function updateCartTotals() {
    const cartBody = document.getElementById("cart-body");
    const rows = cartBody.querySelectorAll("tr[data-id]");
    let totalItems = 0;
    let subtotal = 0;

    rows.forEach((row) => {
        const price =
            parseFloat(
                row
                    .querySelector("td:nth-child(2)")
                    .textContent.replace("₱", ""),
            ) || 0;
        const qty = parseInt(row.querySelector("input").value || 0);
        totalItems += qty;
        subtotal += price * qty;
    });

    // Update DOM
    document.getElementById("total-items").textContent = totalItems;
    document.getElementById("total-items1").textContent = totalItems;
    document.getElementById("subtotal").textContent = subtotal.toLocaleString(
        "en-PH",
        { minimumFractionDigits: 2, maximumFractionDigits: 2 },
    );

    // Assuming discount is 0 for now
    const discount = 0;
    document.getElementById("discount").textContent = discount.toFixed(2);

    const totalAmount = subtotal - discount;
    document.getElementById("total-amount").textContent =
        subtotal.toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
}

// Save cart to localStorage
function saveCart() {
    const cartBody = document.getElementById("cart-body");
    const rows = cartBody.querySelectorAll("tr[data-id]");
    const cart = [];

    rows.forEach((row) => {
        const id = row.dataset.id;
        const name = row.querySelector("span").textContent;
        const price =
            parseFloat(
                row
                    .querySelector("td:nth-child(2)")
                    .textContent.replace("₱", "")
                    .replace(/,/g, ""),
            ) || 0;
        const qty = parseInt(row.querySelector("input").value || 1);
        const image = row.querySelector("img").src;
        const stock = parseInt(row.dataset.stock || 0); // ✅ use dataset
        const unit = row.dataset.unit || "items"; // ✅ save unit too

        cart.push({ id, name, price, qty, image, stock, unit });
    });

    localStorage.setItem("cart", JSON.stringify(cart));
}

// Call loadCart on page load
document.addEventListener("DOMContentLoaded", function () {
    loadCart();
});

// Load cart from localStorage and rebuild cart table
function loadCart() {
    const cartBody = document.getElementById("cart-body");
    const savedCart = JSON.parse(localStorage.getItem("cart") || "[]");

    // Clear current cart rows
    cartBody.innerHTML = "";

    if (savedCart.length === 0) {
        // Show empty cart message
        cartBody.innerHTML = `
            <tr class="text-muted text-center" id="empty-cart">
                <td colspan="5">No items in cart</td>
            </tr>
        `;
    } else {
        // Rebuild cart from saved data
        savedCart.forEach((item) => {
            const row = document.createElement("tr");
            row.setAttribute("data-id", item.id);
            row.setAttribute("data-stock", item.stock); // ✅ important
            row.setAttribute("data-unit", item.unit || "items"); // ✅ important

            row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${item.image}" class="avatar avatar-md rounded">
                <span class="ms-3 fw-semibold">${item.name}</span>
            </div>
        </td>
        <td class="py-3 fw-semibold">
            ₱${parseFloat(item.price).toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
        </td>
        <td class="py-3">
            <div class="d-inline-flex align-items-center border rounded">
                <button type="button" class="btn btn-sm btn-light px-2 rounded-0" onclick="decreaseQty(this)">−</button>
                <input type="text" inputmode="numeric" maxlength="3" class="form-control form-control-sm text-center border-0" value="${item.qty}" style="width:40px;" oninput="this.value=this.value.replace(/\\D/g,''); checkStock(this, ${item.stock}); updateCartTotals();">
                <button type="button" class="btn btn-sm btn-light px-2 ms-1 rounded-0" onclick="increaseQty(this, ${item.stock})">+</button>
            </div>
        </td>
        <td class="py-3">
            <button class="btn btn-sm btn-secondary" onclick="removeRow(this)">Remove</button>
        </td>
    `;
            cartBody.appendChild(row);
        });
    }

    updateCartTotals();
}

// Call loadCart on page load
document.addEventListener("DOMContentLoaded", function () {
    loadCart();
});

// Call saveCart whenever cart changes
function updateCartTotals() {
    const cartBody = document.getElementById("cart-body");
    const rows = cartBody.querySelectorAll("tr[data-id]");
    let totalItems = 0;
    let subtotal = 0;

    rows.forEach((row) => {
        const price =
            parseFloat(
                row
                    .querySelector("td:nth-child(2)")
                    .textContent.replace("₱", "")
                    .replace(/,/g, ""),
            ) || 0;
        const qty = parseInt(row.querySelector("input").value || 0);
        totalItems += qty;
        subtotal += price * qty;
    });

    document.getElementById("total-items").textContent = totalItems;
    document.getElementById("total-items1").textContent = totalItems;
    document.getElementById("subtotal").textContent = subtotal.toLocaleString(
        "en-PH",
        { minimumFractionDigits: 2, maximumFractionDigits: 2 },
    );
    document.getElementById("discount").textContent = (0).toFixed(2);
    document.getElementById("total-amount").textContent =
        subtotal.toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

    saveCart(); // Save cart every time totals are updated
}

function clearCart() {
    const cartBody = document.getElementById("cart-body");

    // Remove all rows
    cartBody.innerHTML = `
        <tr class="text-muted text-center" id="empty-cart">
            <td colspan="5">No items in cart</td>
        </tr>
    `;
    // Reset totals
    document.getElementById("total-items").textContent = `0`;
    document.getElementById("total-items1").textContent = `0`;
    document.getElementById("subtotal").textContent = "0.00";
    document.getElementById("discount").textContent = "0.00";
    document.getElementById("total-amount").textContent = "0.00";

    // Clear localStorage
    localStorage.removeItem("cart");
}

function checkout(btn) {
    const checkoutUrl = btn.dataset.checkoutUrl;
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const amountInput = document.getElementById("cash_amount");
    const totalAmount = document.getElementById("total-amount");

    // Convert total amount text to number
    const total = parseFloat(totalAmount.textContent.replace(/,/g, ""));

    if (cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    // Check if amount has a value
    if (!amountInput.value || Number(amountInput.value) <= 0) {
        alert("Please enter a valid cash amount!");
        amountInput.focus();
        return;
    }

    if (Number(amountInput.value) < total) {
        alert(`Cash amount must be at least ₱${total.toFixed(2)}`);
        amountInput.focus();
        return;
    }

    // Only pass ID and quantity
    const items = {};
    cart.forEach((item) => {
        items[item.id] = item.qty;
    });

    items.ca = items.ca = btoa(Number(amountInput.value));

    const params = new URLSearchParams(items);

    window.location.href = checkoutUrl + "?" + params.toString();
}
