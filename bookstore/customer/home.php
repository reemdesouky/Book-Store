<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'customer') {
    die("Access denied");
}

$books = $conn->query("
    SELECT b.ISBN, b.title, b.price, b.quantity, c.category_name
    FROM Book b
    JOIN Category c ON b.category_id = c.category_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Available Books</h2>

<table border="1">
<tr>
    <th>Title</th>
    <th>Category</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Add</th>
</tr>

<?php while ($b = $books->fetch_assoc()): ?>
<tr>
<form method="post" action="cart.php">
    <td><?= $b['title'] ?></td>
    <td><?= $b['category_name'] ?></td>
    <td><?= $b['price'] ?> EGP</td>
    <td><?= $b['quantity'] ?></td>
    <td>
        <input type="hidden" name="isbn" value="<?= $b['ISBN'] ?>">
        <input type="number" name="qty" min="1" value="1">
        <button name="add">Add</button>
    </td>
</form>
</tr>
<?php endwhile; ?>

</table>

<br>
<a href="cart.php">View Cart</a> |
<a href="orders.php">My Orders</a> |
<a href="../logout.php">Logout</a>

</body>
</html>
