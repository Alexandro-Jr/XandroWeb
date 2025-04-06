document.addEventListener("DOMContentLoaded", function () {
    loadReceipts();

    document.getElementById("uploadForm").addEventListener("submit", function (event) {
        event.preventDefault();
        uploadReceipt();
    });
});

// Fetch Receipts from Backend
function loadReceipts() {
    fetch("../backend/get_receipts.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("receiptTableBody");
            tableBody.innerHTML = "";

            data.forEach(receipt => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>$${receipt.amount}</td>
                    <td>${receipt.date}</td>
                    <td>${receipt.category}</td>
                    <td>${receipt.payment_method}</td>
                    <td>
                        <a href="../uploads/${receipt.file_path}" target="_blank">View</a>
                    </td>
                    <td>
                        <button onclick="deleteReceipt(${receipt.id})">Delete</button>
                        <a href="../backend/download_pdf.php?id=${receipt.id}" class="btn">Download PDF</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error("Error loading receipts:", error));
}

// Upload Receipt
function uploadReceipt() {
    const formData = new FormData(document.getElementById("uploadForm"));

    fetch("../backend/upload_receipt.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(response => {
        alert(response);
        loadReceipts();
    })
    .catch(error => console.error("Error uploading receipt:", error));
}

// Delete Receipt
function deleteReceipt(id) {
    if (confirm("Are you sure you want to delete this receipt?")) {
        fetch(`../backend/delete_receipt.php?id=${id}`, { method: "GET" })
            .then(response => response.text())
            .then(response => {
                alert(response);
                loadReceipts();
            })
            .catch(error => console.error("Error deleting receipt:", error));
    }
}
