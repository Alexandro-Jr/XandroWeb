<?php
$host = 'localhost'; // Change if using a remote DB
$dbname = 'ul_financial_app';
$username = 'root'; // Default MySQL user
$password = ''; // Default MySQL password (change for production)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>