<?php
require_once "includes/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Hash the password
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO Customer
        (Fname, Lname, user_name, email, customer_password, customer_address, phone_number)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssssss",
        $_POST['fname'],
        $_POST['lname'],
        $_POST['username'],
        $_POST['email'],
        $hashedPassword,          
        $_POST['address'],
        $_POST['phone']
    );

    if ($stmt->execute()) {
        $success = "Account created successfully. You can now log in.";
    } else {
        $error = "Registration failed (username or email may already exist)";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Customer Registration</h2>

<form method="post">
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="lname" placeholder="Last Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="address" placeholder="Address">
    <input type="text" name="phone" placeholder="Phone Number">
    <button type="submit">Register</button>
</form>

<p style="color:green"><?= $success ?></p>
<p style="color:red"><?= $error ?></p>

<p>
    Already have an account? <a href="index.php">Login</a>
</p>

</body>
</html>