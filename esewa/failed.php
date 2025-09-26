<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Replace with your DB password
$database = 'shop_db';// Replace with your DB name


$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ref_id = $_GET['oid']; // Reference ID

// Update transaction to failed
$stmt = $conn->prepare("UPDATE transactions SET status='failed' WHERE reference_id=?");
$stmt->bind_param('s', $ref_id);
$stmt->execute();
$stmt->close();

echo "Payment Failed!";
?>
