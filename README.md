Expense Tracker

Overview:
The Expense Tracker is a web application designed to help users efficiently manage and monitor their expenses. It categorizes expenses, tracks different payment methods, and provides insightful reports. This project uses HTML, CSS, JavaScript, and AJAX for the frontend, PHP for the backend, and SQL for the database, emphasizing secure user authentication and smooth user experience.

Features:
User Authentication: Secure signup and login.
Expense Management: Add, view, and delete expenses.
Categorization: Predefined categories (food, housing, transportation, etc.).
Payment Methods: Track expenses made with cash, credit/debit cards.
Reporting: Generate detailed PDF reports summarizing expenses.
Technologies Used
Frontend: HTML, CSS, JavaScript, AJAX
Backend: PHP
Database: SQL
PDF Generation: FPDF library
Getting Started
Prerequisites
Local server setup (e.g., XAMPP, WAMP)
PHP 7.4+
MySQL
Installation

Clone the Repository:
sh
Copy code
git clone https://github.com/username/expense-tracker.git

Setup Database:
Create a database named econome.
Import the provided SQL file to set up the necessary tables (users, expenses, categories, payment_types).

Configure Database Connection:
Update db.php with your database credentials.

Run the Application:
Host the project on a local server using tools like XAMPP or WAMP.
Access the application via localhost.

File Structure:
index.html: Entry point with login and signup forms.
dashboard.php: Main dashboard for managing expenses.
db.php: Database connection file.
login.php: Handles user login.
signup.php: Handles user signup.
logout.php: Logs out the user.
submitTransaction.php: Processes adding a new expense.
fetchExpenses.php: Fetches expenses for viewing.
fetchFrequencyExpenses.php: Fetches expenses based on date range.
fetchCategories.php: Retrieves available categories.
fetchPayment.php: Retrieves available payment methods.
deleteExpenses.php: Deletes specified expenses.
generateReport.php: Generates PDF reports of expenses.
style.css: Styling for the application.
dashboard.css: Styling for the dashboard.

Contribution:
Feel free to fork this repository, submit issues, and make pull requests to improve the application. Contributions are highly appreciated.

License:
This project is licensed under the MIT License - see the LICENSE file for details.
