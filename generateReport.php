<?php
require('C:/xampp/htdocs/CCP/fpdf/fpdf.php');  // Adjust the path as necessary

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';  // Include your database connection

$user_id = $_SESSION['user_id'];
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Expense Report', 0, 1, 'C');
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Table header
    function ExpenseTableHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 10, 'Amount', 1);
        $this->Cell(30, 10, 'Date', 1);
        $this->Cell(60, 10, 'Description', 1);
        $this->Cell(30, 10, 'Category', 1);
        $this->Cell(40, 10, 'Payment Type', 1);
        $this->Ln();
    }

    // Table row
    function ExpenseTableRow($amount, $date, $description, $category, $paymentType)
    {
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 10, $amount, 1);
        $this->Cell(30, 10, $date, 1);
        $this->Cell(60, 10, $description, 1);
        $this->Cell(30, 10, $category, 1);
        $this->Cell(40, 10, $paymentType, 1);
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->ExpenseTableHeader();

$total_amount = 0;

$sql = "SELECT e.amount, e.expense_date, e.description, c.name, p.type_name 
        FROM expenses e
        JOIN categories c ON e.category_id = c.category_id
        JOIN payment_types p ON e.payment_type_id = p.payment_type_id
        WHERE e.user_id = ?";

$params = [$user_id];
$types = 'i';

if ($start_date && $end_date) {
    $sql .= " AND e.expense_date BETWEEN ? AND ?";
    $params = [$user_id, $start_date, $end_date];
    $types = 'iss';
}

$sql .= " ORDER BY e.expense_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['amount'];
        $pdf->ExpenseTableRow($row['amount'], $row['expense_date'], $row['description'], $row['name'], $row['type_name']);
    }

    $stmt->close();
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total Amount: ' . $total_amount, 0, 1, 'R');

$pdf->Output('D', 'Expense_Report.pdf');  // Download the PDF

$conn->close();
?>
