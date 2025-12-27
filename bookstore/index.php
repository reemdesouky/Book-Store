<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . "/includes/db.php";


$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login    = trim($_POST['username']); // username OR email
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if ($role === "admin") {

        $stmt = $conn->prepare(
            "SELECT admin_id, admin_password
             FROM Admins
             WHERE admin_name = ? OR email = ?"
        );
        $stmt->bind_param("ss", $login, $login);

    } else { // customer

        $stmt = $conn->prepare(
            "SELECT customer_id, customer_password
             FROM Customer
             WHERE user_name = ? OR email = ?"
        );
        $stmt->bind_param("ss", $login, $login);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        $hashedPassword = ($role === "admin")
            ? $row['admin_password']
            : $row['customer_password'];

        if (password_verify($password, $hashedPassword)) {

            $_SESSION['role'] = $role;
            $_SESSION['id']   = ($role === "admin")
                ? $row['admin_id']
                : $row['customer_id'];

            header(
                "Location: " .
                ($role === "admin"
                    ? "admin/dashboard.php"
                    : "customer/home.php")
            );
            exit();
        }
    }

    $error = "Invalid username/email or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Login</h2>

<form method="post">
    <input type="text" name="username" placeholder="Username / email" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" required>
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit">Login</button>
</form>

<p style="color:red"><?= $error ?></p>

<p>
    New customer? <a href="register.php">Register here</a>
</p>

</body>
</html>
