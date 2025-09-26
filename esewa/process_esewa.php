<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Replace with your DB password
$database = 'shop_db';// Replace with your DB name

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// eSewa Test Credentials
define('ESEWA_MERCHANT_ID', 'EPAYTEST');
define('ESEWA_TEST_URL', 'https://uat.esewa.com.np/epay/main');
define('SUCCESS_URL', 'http://localhost/esewa/success.php');
define('FAIL_URL', 'http://localhost/esewa/failed.php');

// Handle payment initiation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $reference_id = uniqid('REF');

    // Save the transaction to the database
    $stmt = $conn->prepare("INSERT INTO transactions (reference_id, amount) VALUES (?, ?)");
    $stmt->bind_param('sd', $reference_id, $amount);
    $stmt->execute();
    $stmt->close();

    // Redirect to eSewa payment page
    $payment_url = ESEWA_TEST_URL . "?amt=$amount&psc=0&pdc=0&txAmt=0&tAmt=$amount&pid=$reference_id&scd=" . ESEWA_MERCHANT_ID . "&su=" . SUCCESS_URL . "&fu=" . FAIL_URL;
    header("Location: $payment_url");
    exit();
}
?>


