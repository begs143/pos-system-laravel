// datepicker.js
new Pikaday({
    field: document.getElementById("pikaday"),
    toString(date) {
        const d = date.getDate().toString().padStart(2, "0");
        const m = (date.getMonth() + 1).toString().padStart(2, "0");
        const y = date.getFullYear();
        return `${m}/${d}/${y}`;
    },
    parse(dateString) {
        const parts = dateString.split("/");
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const year = parseInt(parts[2], 10);
        return new Date(year, month, day);
    },
});

// Auto-dismiss alerts after 3 seconds

document.addEventListener("DOMContentLoaded", function () {
    // 3000ms = 3 seconds
    setTimeout(function () {
        // select all alerts
        document.querySelectorAll(".alert").forEach(function (alert) {
            // start fade out
            alert.classList.remove("show");
            alert.classList.add("fade");

            setTimeout(function () {
                alert.remove();
            }, 500); // wait small time for fade CSS to complete
        });
    }, 3000);
});

// document.addEventListener("DOMContentLoaded", function () {
//     const typeSelect = document.querySelector(".type-select");
//     const supplierField = document.querySelector(".supplier-field");

//     function toggleSupplier() {
//         if (typeSelect.value === "in") {
//             supplierField.classList.remove("d-none");
//         } else {
//             supplierField.classList.add("d-none");
//         }
//     }

//     // Run on change
//     typeSelect.addEventListener("change", toggleSupplier);

//     // Run on page load (important for validation errors)
//     toggleSupplier();
// });

// for pos qty

function proceedPayment() {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");

    fetch(window.storeSaleUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify({ items: cart }),
    })
        .then((res) => res.json())
        .then((res) => {
            if (res.success) {
                localStorage.removeItem("cart");
                alert("Sale stored! Invoice: " + res.invoice_no);
                window.location.href = window.orderDetailsBaseUrl + res.sale_id;
            }
        });
}

function globalClearCart() {
    localStorage.removeItem("cart");
}

function printDesktop() {
    document.body.classList.remove("thermal-print");
    window.print();
}

function printThermal() {
    document.body.classList.add("thermal-print");
    window.print();
}
