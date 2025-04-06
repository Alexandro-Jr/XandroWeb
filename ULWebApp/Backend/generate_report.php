<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
    
    if (!$start_date || !$end_date) {
        die("Please provide both start and end dates.");
    }
    
    try {
        $stmt = $pdo->prepare("SELECT category, SUM(amount) AS total FROM receipts WHERE date BETWEEN ? AND ? GROUP BY category");
        $stmt->execute([$start_date, $end_date]);
        $report_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($report_data);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Failed to generate report: " . $e->getMessage()]);
    }
} else {
    echo "Invalid request method.";
}
?>
<?php
require_once('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    $query = "SELECT 
        COALESCE(SUM(CASE WHEN category = 'income' THEN amount ELSE 0 END), 0) AS total_income,
        COALESCE(SUM(CASE WHEN category = 'expense' THEN amount ELSE 0 END), 0) AS total_expenses
        FROM receipts WHERE date BETWEEN ? AND ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $balance = $data["total_income"] - $data["total_expenses"];

    $insertQuery = "INSERT INTO reports (start_date, end_date, total_income, total_expenses, balance) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssddd", $start_date, $end_date, $data["total_income"], $data["total_expenses"], $balance);
    
    if ($stmt->execute()) {
        echo "Report generated successfully!";
    } else {
        echo "Error generating report.";
    }
}
?>
