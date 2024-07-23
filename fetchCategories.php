<?php
include 'db.php';  // Include your DB connection


// Query to fetch categories
$sql = "SELECT category_id, name FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["category_id"] . '">' . $row["name"] . '</option>';
    }
} else {
    echo '<option value="">No categories found</option>';
}
$conn->close();
?>
