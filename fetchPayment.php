<?php
include 'db.php';  // Include your DB connection

$sql = "SELECT payment_type_id, type_name FROM payment_types";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["payment_type_id"] . '">' . $row["type_name"] . '</option>';
    }
} else {
    echo '<option value="">No Payment Option found</option>';
}
$conn->close();
?>
