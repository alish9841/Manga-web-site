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

$ref_id = $_GET['oid']; // Reference ID
$amount = $_GET['amt']; // Amount
$scd = $_GET['scd'];    // Merchant ID

// Verify Payment with eSewa
$verification_url = "https://uat.esewa.com.np/epay/transrec";
$data = [
    'amt' => $amount,
    'rid' => $ref_id,
    'pid' => $ref_id,
    'scd' => ESEWA_MERCHANT_ID
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($verification_url, false, $context);

if (strpos($response, "<response_code>Success</response_code>") !== false) {
    // Update transaction to success
    $stmt = $conn->prepare("UPDATE transactions SET status='success' WHERE reference_id=?");
    $stmt->bind_param('s', $ref_id);
    $stmt->execute();
    $stmt->close();

    echo "Payment Successful!";
} else {
    echo "Payment Verification Failed!";
}
?>
