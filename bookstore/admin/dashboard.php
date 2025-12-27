<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'admin') {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Admin Dashboard</h2>

<ul>
    <li><a href="add_book.php">Add New Book</a></li>
    <li><a href="edit_book.php">Edit Books / Update Stock</a></li>
    <li><a href="confirm_orders.php">Confirm Publisher Orders</a></li>
    <li><a href="reports.php">Reports</a></li>
</ul>

<a href="../logout.php">Logout</a>

</body>
</html>
