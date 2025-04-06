<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        die("Invalid receipt ID.");
    }

    try {
        // Fetch the file path before deleting the record
        $stmt = $pdo->prepare("SELECT file_path FROM receipts WHERE id = ?");
        $stmt->execute([$id]);
        $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($receipt && file_exists($receipt['file_path'])) {
            unlink($receipt['file_path']); // Delete the file
        }

        // Delete the receipt from the database
        $stmt = $pdo->prepare("DELETE FROM receipts WHERE id = ?");
        $stmt->execute([$id]);

        echo "Receipt deleted successfully.";
    } catch (PDOException $e) {
        echo "Error deleting receipt: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>