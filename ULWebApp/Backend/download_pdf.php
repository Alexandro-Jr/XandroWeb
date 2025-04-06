<?php
require_once('../tcpdf/tcpdf.php');
require_once('../config/database.php');

if (isset($_GET['id'])) {
    $receipt_id = intval($_GET['id']);

    // Fetch receipt data from database
    $stmt = $conn->prepare("SELECT * FROM receipts WHERE id = ?");
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $receipt = $result->fetch_assoc();

    if (!$receipt) {
        die("Receipt not found.");
    }

    // Create new PDF document
    $pdf = new TCPDF();
    $pdf->SetCreator('Financial Web App');
    $pdf->SetTitle('Receipt');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    // Define HTML receipt template
    $html = '
    <h2 style="text-align:center;">Financial Receipt</h2>
    <hr>
    <p><strong>Name:</strong> ' . htmlspecialchars($receipt['name']) . '</p>
    <p><strong>ID Number:</strong> ' . htmlspecialchars($receipt['id_number']) . '</p>
    <p><strong>Amount:</strong> $' . number_format($receipt['amount'], 2) . '</p>
    <p><strong>Date:</strong> ' . htmlspecialchars($receipt['date']) . '</p>
    <p><strong>Bank:</strong> ' . htmlspecialchars($receipt['bank']) . '</p>
    <p><strong>Slip Number:</strong> ' . htmlspecialchars($receipt['slip_number']) . '</p>
    <p><strong>Category:</strong> ' . htmlspecialchars($receipt['category']) . '</p>
    <p><strong>Payment Method:</strong> ' . htmlspecialchars($receipt['payment_method']) . '</p>
    <br>
    <p>_________________________</p>
    <p><strong>Account Supervisor</strong></p>
    <br>
    <p>_________________________</p>
    <p><strong>Chief Accountant</strong></p>
    <br>
    <p>_________________________</p>
    <p><strong>VP Finance</strong></p>';

    // Output PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('receipt.pdf', 'D'); // Forces download
} else {
    die("Invalid request.");
}
?>
