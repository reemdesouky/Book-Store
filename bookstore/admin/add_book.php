<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'admin') die("Access denied");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $conn->prepare("
        INSERT INTO Book
        (ISBN, title, price, Threshold, quantity, publish_year, publisher_id, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssdiiiii",
        $_POST['isbn'],
        $_POST['title'],
        $_POST['price'],
        $_POST['threshold'],
        $_POST['quantity'],
        $_POST['year'],
        $_POST['publisher'],
        $_POST['category']
    );

    if ($stmt->execute()) {
        $message = "Book added successfully";
    } else {
        $message = "Error adding book";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Add New Book</h2>

<form method="post">
    <input name="isbn" placeholder="ISBN" required>
    <input name="title" placeholder="Title" required>
    <input name="price" placeholder="Price" required>
    <input name="threshold" placeholder="Threshold" required>
    <input name="quantity" placeholder="Initial Quantity" required>
    <input name="year" placeholder="Publish Year">
    <input name="publisher" placeholder="Publisher ID" required>
    <input name="category" placeholder="Category ID" required>

    <button type="submit">Add Book</button>
</form>

<p><?= $message ?></p>

<a href="dashboard.php">Back</a>

</body>
</html>
