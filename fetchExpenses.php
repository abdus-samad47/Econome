<?php
session_start();  // Start the session to access user data

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

include 'db.php';  // Include your database connection

// SQL query to fetch expenses for the logged-in user
$sql = "SELECT e.amount, e.expense_date, e.description, c.name, p.type_name 
        FROM expenses e
        JOIN categories c ON e.category_id = c.category_id
        JOIN payment_types p ON e.payment_type_id = p.payment_type_id
        WHERE e.user_id = ? ORDER BY e.expense_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any expenses
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Payment Type</th>
                </tr>";
        // Display each expense in a row of the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>  
                    <td>{$row['amount']}</td>
                    <td>{$row['expense_date']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['type_name']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No expenses found.";  // Message if no expenses are found
    }

    $stmt->close();  // Close the prepared statement
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();  // Close the database connection
?>
