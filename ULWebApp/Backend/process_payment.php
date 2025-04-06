<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ul_financial_app";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $receipt_path = "";

    // Handle File Upload
    if (!empty($_FILES["receipt_file"]["name"])) {
        $target_dir = "uploads/";
        $receipt_path = $target_dir . basename($_FILES["receipt_file"]["name"]);
        move_uploaded_file($_FILES["receipt_file"]["tmp_name"], $receipt_path);
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO payments (student_id, student_name, amount, payment_method, date, description, receipt_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $student_id, $student_name, $amount, $payment_method, $date, $description, $receipt_path);
    $stmt->execute();


if ($stmt->affected_rows > 0) {
    echo "Payment recorded successfully!";
} else {
    echo "Error recording payment: " . $stmt->error;
}
$stmt->close();

    echo "Payment recorded successfully!";
}

// Fetch Payments for Table
$result = $conn->query("SELECT * FROM receipts ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['student_id']}</td>
            <td>{$row['student_name']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['payment_method']}</td>
            <td>{$row['date']}</td>
            <td>{$row['description']}</td>
            <td>";
    if (!empty($row['receipt_path'])) {
        echo "<a href='{$row['receipt_path']}' target='_blank'>View</a>";
    } else {
        echo "No Receipt";
    }
    echo "</td></tr>";
}

$conn->close();
?>