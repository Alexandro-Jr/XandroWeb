<?php
require '../config/database.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name, amount, date, category, payment_method, file_path FROM receipts ORDER BY date DESC");
    $receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($receipts);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch receipts: " . $e->getMessage()]);
}
?>