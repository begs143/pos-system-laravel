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
