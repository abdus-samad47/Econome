<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <header>
        <h1>Expense Tracker - Dashboard</h1>
        <button id="menu" onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <div class="container">
        <div class="button-list">
            <button id="addButton" onclick="showForm('add')">Add</button>
            <button id="viewButton" onclick="showForm('view')">View</button>
            <button id="deleteButton" onclick="showForm('delete')">Delete</button>
        </div>
        <div id="formContainer" class="form-container">
            <!-- Add Form -->
            <div id="addForm" class="form-content" style="display: none;">
                <h2>Add Expense</h2>
                <form method="post" action="submitTransaction.php">
                    <label for="category">Category</label>
                    <select id="category" name="category_id">
                        <?php include 'fetchCategories.php'; ?>
                    </select>
                    <br>
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" placeholder="Amount">
                    <br>
                    <label for="date">Date</label>
                    <input type="date" id="date" name="expense_date" required>
                    <br>
                    <label for="payment">Payment</label>
                    <select id="payment" name="payment_type_id">
                        <?php include 'fetchPayment.php'; ?>
                    </select>
                    <br>
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" placeholder="Description">
                    <br>
                    <button type="submit">Add</button>
                </form>
            </div>
            <!-- View Form -->
            <div id="viewForm" class="form-content" style="display: none;">
                <h2>View Expenses</h2>
                <form id="frequencyForm" onsubmit="fetchExpenses(event)">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="start_date">
                    <br>
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="end_date">
                    <br>
                    <button type="submit">Fetch</button>
                </form>
                <div id="expensesTable">
                    <?php include 'fetchFrequencyExpenses.php'; ?>
                </div>
            </div>
            <!-- Delete Form -->
            <div id="deleteForm" class="form-content" style="display: none;">
                <?php include 'fetchExpensesForDelete.php'; ?>
            </div>
        </div>
    </div>

    <script>
        function showForm(formType) {
            var i, forms, buttonList;

            forms = document.getElementsByClassName("form-content");
            for (i = 0; i < forms.length; i++) {
                forms[i].style.display = "none";
            }

            buttonList = document.getElementsByClassName("button");
            for (i = 0; i < buttonList.length; i++) {
                buttonList[i].className = buttonList[i].className.replace(" active", "");
            }

            document.getElementById(formType + "Form").style.display = "block";
            document.getElementById(formType + "Button").className += " active";
        }

        function fetchExpenses(event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('frequencyForm'));

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetchFrequencyExpenses.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('expensesTable').innerHTML = xhr.responseText;
                } else {
                    alert('Error fetching expenses');
                }
            };
            xhr.send(formData);
        }

        function logout() {
            console.log("Logged out successfully");
        }

        function editProfile() {
            console.log("Editing profile");
        }

        function deleteExpense(expenseId) {
            if (confirm('Are you sure you want to delete this expense?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'deleteExpense.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert('Expense deleted successfully');
                        window.location.reload();
                    } else {
                        alert('Error deleting expense');
                    }
                };
                xhr.send('expense_id=' + expenseId);
            }
        }
    </script>
</body>

</html>
