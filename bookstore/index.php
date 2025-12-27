<?php
session_start();
require_once "includes/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if ($role === "admin") {

        $stmt = $conn->prepare(
            "SELECT admin_id, admin_password 
             FROM Admins 
             WHERE email = ?"
        );
        $stmt->bind_param("s", $username);

    } else { // customer

        $stmt = $conn->prepare(
            "SELECT customer_id, customer_password 
             FROM Customer 
             WHERE user_name = ?"
        );
        $stmt->bind_param("s", $username);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        $dbPassword = ($role === "admin")
            ? $row['admin_password']
            : $row['customer_password'];

        // NOTE: plain-text for now (as you used in schema)
        if ($password === $dbPassword) {

            $_SESSION['role'] = $role;
            $_SESSION['id']   = ($role === "admin")
                ? $row['admin_id']
                : $row['customer_id'];

            if ($role === "admin") {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: customer/home.php");
            }
            exit();
        }
    }

    $error = "Invalid username or password";
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
    <input type="text" name="username" placeholder="Username" required>
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
