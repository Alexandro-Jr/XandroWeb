<?php
require_once('../config/database.php');

$query = "SELECT 
    COALESCE(SUM(CASE WHEN category = 'income' THEN amount ELSE 0 END), 0) AS total_income,
    COALESCE(SUM(CASE WHEN category = 'expense' THEN amount ELSE 0 END), 0) AS total_expenses
    FROM receipts";

$result = $conn->query($query);
$data = $result->fetch_assoc();
$data['balance'] = $data['total_income'] - $data['total_expenses'];

header('Content-Type: application/json');
echo json_encode($data);
?>