<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Processing System</title>
    <link rel="stylesheet" href="/bookstore/css/style.css">
    <script defer src="/bookstore/js/main.js"></script>
</head>
<body>

<header class="topbar">
    <h1>📚 Online Bookstore</h1>

    <nav>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/bookstore/admin/dashboard.php">Dashboard</a>
            <a href="/bookstore/admin/add_book.php">Add Book</a>
            <a href="/bookstore/admin/reports.php">Reports</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
            <a href="/bookstore/customer/home.php">Home</a>
            <a href="/bookstore/customer/cart.php">Cart</a>
            <a href="/bookstore/customer/orders.php">Orders</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['role'])): ?>
            <a class="logout" href="/bookstore/logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">
