<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.html?error=invalid_email_format");
            exit();
        }

        $sql = "SELECT user_id, email, password FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['user_id'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        header("Location: index.html?error=invalid_credentials");
                        exit();
                    }
                } else {
                    header("Location: index.html?error=invalid_credentials");
                    exit();
                }
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
        $conn->close();
    } else {
        header("Location: index.html?error=all_fields_required");
        exit();
    }
} else {
    echo "Invalid request method.";
}
?>

