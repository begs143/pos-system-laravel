document.addEventListener("click", function (e) {
    if (!e.target.classList.contains("add-to-po")) return;

    e.preventDefault();

    const btn = e.target;
    const poId = btn.dataset.id;
    const poName = btn.dataset.name;
    const poPrice = parseFloat(btn.dataset.price);
    const poUnit = btn.dataset.unit;
    const poImage = btn.dataset.image;
    const poStock = parseInt(btn.dataset.stock || 0);

    const poCartBody = document.getElementById("po-cart-body");

    // Remove empty message
    const emptyRow = document.getElementById("po-empty-cart");
    if (emptyRow) emptyRow.remove();

    // Check if item already exists
    let existingRow = poCartBody.querySelector(`tr[data-id="${poId}"]`);

    if (existingRow) {
        const input = existingRow.querySelector(
            "input[type='text'][maxlength]",
        );
        let val = parseInt(input.value || 1);
        input.value = val + 1;
        updatePoCartTotals();
        return;
    }

    // Create new row
    const row = document.createElement("tr");
    row.setAttribute("data-id", poId);
    row.setAttribute("data-unit", poUnit);
    row.setAttribute("data-stock", poStock);

    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${poImage}" class="avatar avatar-md rounded">
                <span class="ms-3 fw-semibold">${poName}</span>
            </div>
        </td>
        <td class="py-3">
            <input 
                type="text" 
                inputmode="decimal"
                class="form-control form-control-sm text-center fw-semibold po-price-input"
                value="${poPrice.toFixed(2)}" 
                style="width:80px;"
                oninput="
        this.value = this.value.replace(/[^0-9.]/g,''); 
        if (parseFloat(this.value) > 1000000) this.value = '1000000';
        // Limit to min 0.01
        updatePoCartTotals();"
            >
        </td>
        <td class="py-3">
            <div class="d-inline-flex align-items-center border rounded">
                <button type="button" class="btn btn-sm btn-light px-2 rounded-0" onclick="decreasePoQty(this)">−</button>
                <input type="text" inputmode="numeric" maxlength="3" class="form-control form-control-sm text-center border-0" value="1" style="width:40px;" oninput="this.value = this.value.replace(/\\D/g, ''); checkPoStock(this, ${poStock}); updatePoCartTotals();">
                <button type="button" class="btn btn-sm btn-light px-2 ms-1 rounded-0" onclick="increasePoQty(this, ${poStock})">+</button>
            </div>
        </td>
        <td class="py-3">
            <button class="btn btn-sm btn-secondary" onclick="removePoRow(this)">Remove</button>
        </td>
    `;

    poCartBody.appendChild(row);
    updatePoCartTotals();
});

// Qty logic with stock check
function increasePoQty(btn, stock) {
    const input = btn.previousElementSibling;
    let val = parseInt(input.value || 0);
    input.value = val + 1;
    updatePoCartTotals();
}

function decreasePoQty(btn) {
    const input = btn.nextElementSibling;
    let val = parseInt(input.value || 1);
    if (val > 1) input.value = val - 1;
    updatePoCartTotals();
}

function checkPoStock(input, stock) {
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

function removePoRow(btn) {
    const row = btn.closest("tr");
    row.remove();

    const poCartBody = document.getElementById("po-cart-body");
    if (!poCartBody.children.length) {
        poCartBody.innerHTML = `
            <tr class="text-muted text-center" id="po-empty-cart">
                <td colspan="5">No items found</td>
            </tr>
        `;
    }

    updatePoCartTotals();
}

// Totals
function updatePoCartTotals() {
    const poCartBody = document.getElementById("po-cart-body");
    const rows = poCartBody.querySelectorAll("tr[data-id]");
    let totalItems = 0;
    let subtotal = 0;

    rows.forEach((row) => {
        const priceInput = row.querySelector(
            "td:nth-child(2) input.po-price-input",
        );
        const price = parseFloat(priceInput.value) || 0;
        const qtyInput = row.querySelector("input[type='text'][maxlength]");
        const qty = parseInt(qtyInput.value || 0);
        totalItems += qty;
        subtotal += price * qty;
    });

    document.getElementById("po-total-items").textContent = totalItems;
    document.getElementById("po-total-items-display").textContent = totalItems;
    document.getElementById("po-subtotal").textContent =
        subtotal.toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    document.getElementById("po-discount").textContent = (0).toFixed(2);
    document.getElementById("po-total-amount").textContent =
        subtotal.toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

    savePoCart();
}

// Save to localStorage
function savePoCart() {
    const poCartBody = document.getElementById("po-cart-body");
    const rows = poCartBody.querySelectorAll("tr[data-id]");
    const poCart = [];

    rows.forEach((row) => {
        const poId = row.dataset.id;
        const poName = row.querySelector("span").textContent;
        const poPrice =
            parseFloat(
                row.querySelector("td:nth-child(2) input.po-price-input").value,
            ) || 0;
        const poQty = parseInt(
            row.querySelector("input[type='text'][maxlength]").value || 1,
        );
        const poImage = row.querySelector("img").src;
        const poStock = parseInt(row.dataset.stock || 0);
        const poUnit = row.dataset.unit || "items";

        poCart.push({
            id: poId,
            name: poName,
            price: poPrice,
            qty: poQty,
            image: poImage,
            stock: poStock,
            unit: poUnit,
        });
    });

    localStorage.setItem("poCart", JSON.stringify(poCart));
}

// Load cart
document.addEventListener("DOMContentLoaded", loadPoCart);

function loadPoCart() {
    const poCartBody = document.getElementById("po-cart-body");
    const savedPoCart = JSON.parse(localStorage.getItem("poCart") || "[]");

    poCartBody.innerHTML = "";

    if (savedPoCart.length === 0) {
        poCartBody.innerHTML = `
            <tr class="text-muted text-center" id="po-empty-cart">
                <td colspan="5">No items found</td>
            </tr>
        `;
    } else {
        savedPoCart.forEach((item) => {
            const row = document.createElement("tr");
            row.setAttribute("data-id", item.id);
            row.setAttribute("data-stock", item.stock);
            row.setAttribute("data-unit", item.unit);

            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" class="avatar avatar-md rounded">
                        <span class="ms-3 fw-semibold">${item.name}</span>
                    </div>
                </td>
                <td class="py-3">
                    <input 
                        type="text" 
                        inputmode="decimal"
                        class="form-control form-control-sm text-center fw-semibold po-price-input"
                        value="${parseFloat(item.price).toFixed(2)}"
                        style="width:80px;"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,''); updatePoCartTotals();"
                    >
                </td>
                <td class="py-3">
                    <div class="d-inline-flex align-items-center border rounded">
                        <button type="button" class="btn btn-sm btn-light px-2 rounded-0" onclick="decreasePoQty(this)">−</button>
                        <input type="text" inputmode="numeric" maxlength="3" class="form-control form-control-sm text-center border-0" value="${item.qty}" style="width:40px;" oninput="this.value=this.value.replace(/\\D/g,''); checkPoStock(this, ${item.stock}); updatePoCartTotals();">
                        <button type="button" class="btn btn-sm btn-light px-2 ms-1 rounded-0" onclick="increasePoQty(this, ${item.stock})">+</button>
                    </div>
                </td>
                <td class="py-3">
                    <button class="btn btn-sm btn-secondary" onclick="removePoRow(this)">Remove</button>
                </td>
            `;
            poCartBody.appendChild(row);
        });
    }

    updatePoCartTotals();
}

// Clear cart
function clearPoCart() {
    const poCartBody = document.getElementById("po-cart-body");

    poCartBody.innerHTML = `
        <tr class="text-muted text-center" id="po-empty-cart">
            <td colspan="5">No items in list</td>
        </tr>
    `;
    document.getElementById("po-total-items").textContent = `0`;
    document.getElementById("po-total-items-display").textContent = `0`;
    document.getElementById("po-subtotal").textContent = "0.00";
    document.getElementById("po-discount").textContent = "0.00";
    document.getElementById("po-total-amount").textContent = "0.00";

    localStorage.removeItem("poCart");
}

document.getElementById("poForm").addEventListener("submit", function (e) {
    const cart = JSON.parse(localStorage.getItem("poCart") || "[]");

    // Stop form if cart is empty
    if (cart.length === 0) {
        e.preventDefault();
        alert("Please add at least one item.");
        return;
    }

    // Prepare items for backend
    const itemsForBackend = cart.map((item) => ({
        product_id: item.id,
        price: item.price,
        qty: item.qty,
    }));

    // Set hidden input value
    document.getElementById("poCartInput").value =
        JSON.stringify(itemsForBackend);
});

function clearPoItem() {
    localStorage.removeItem("poCart");
}
