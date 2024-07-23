<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die('You are not logged in.');
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "econome";
$user_id = $_SESSION['user_id']; // Get user_id from session

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category_id = $_POST['category_id'];
$amount = $_POST['amount'];
$expense_date = $_POST['expense_date'];
$payment_type_id = $_POST['payment_type_id'];
$description = $_POST['description'];

$stmt = $conn->prepare("INSERT INTO expenses (user_id, category_id, amount, expense_date, payment_type_id, description) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iidsis", $user_id, $category_id, $amount, $expense_date, $payment_type_id, $description);
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
