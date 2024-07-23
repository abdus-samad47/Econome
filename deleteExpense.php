<?php
session_start();  // Ensure the user is logged in
include 'db.php';  // Include database connection

if (isset($_POST['expense_id']) && isset($_SESSION['user_id'])) {
    $expenseId = $_POST['expense_id'];
    $userId = $_SESSION['user_id'];  // Ensure that the expense belongs to the logged-in user

    // Prepare the delete statement
    $sql = "DELETE FROM expenses WHERE expense_id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $expenseId, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Expense deleted successfully";
        } else {
            echo "Error deleting expense";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Invalid request";
}
?>
