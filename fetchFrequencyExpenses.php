<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

include 'db.php';  // Include your database connection

$user_id = $_SESSION['user_id'];

// Initialize the SQL query
$sql = "SELECT e.expense_id, e.amount, e.expense_date, e.description, c.name, p.type_name 
        FROM expenses e
        JOIN categories c ON e.category_id = c.category_id
        JOIN payment_types p ON e.payment_type_id = p.payment_type_id
        WHERE e.user_id = ?";

// Check if the start date and end date are set
if (isset($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
    // If dates are set, filter by the selected date range
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $sql .= " AND e.expense_date BETWEEN ? AND ?";
    $params = [$user_id, $start_date, $end_date];
    $types = 'iss';
} else {
    // If no dates are set, just filter by user_id
    $params = [$user_id];
    $types = 'i';
}

$sql .= " ORDER BY e.expense_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_amount = 0;

    // Check if there are any expenses
    if ($result->num_rows > 0) {
        echo "<style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #f1f1f1;
                }
                .total-amount {
                    font-weight: bold;
                    text-align: right;
                    padding: 10px;
                    margin-top: 10px;
                }
                .generate-report {
                    margin-top: 10px;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                }
                .generate-report:hover {
                    background-color: #45a049;
                }
              </style>";
        echo "<table>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Payment Type</th>
                </tr>";
        // Display each expense in a row of the table
        while ($row = $result->fetch_assoc()) {
            $total_amount += $row['amount'];
            echo "<tr>  
                    <td>{$row['amount']}</td>
                    <td>{$row['expense_date']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['type_name']}</td>
                  </tr>";
        }
        echo "</table>";
        echo "<p>Total Amount: {$total_amount}</p>";
        echo "<button onclick=\"generateReport()\">Generate Report</button>";
    } else {
        echo "No expenses found.";  // Message if no expenses are found
    }

    $stmt->close();  // Close the prepared statement
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();  // Close the database connection
?>

<script>
function generateReport() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    window.location.href = 'generateReport.php?start_date=' + startDate + '&end_date=' + endDate;
}
</script>
