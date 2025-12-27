<?php
include "../includes/auth.php";
require_once "../includes/db.php";

$cid = $_SESSION['id'];

$orders = $conn->query("
    SELECT order_id, order_date, total_price
    FROM Customer_Order
    WHERE customer_id = $cid
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>My Orders</h2>

<?php while ($o = $orders->fetch_assoc()): ?>

<h3>Order #<?= $o['order_id'] ?> | <?= $o['order_date'] ?></h3>
<p>Total: <?= $o['total_price'] ?> EGP</p>

<ul>
<?php
$items = $conn->query("
    SELECT b.title, oi.quantity
    FROM Order_Item oi
    JOIN Book b ON b.ISBN = oi.ISBN
    WHERE oi.order_id = {$o['order_id']}
");
while ($i = $items->fetch_assoc()):
?>
<li><?= $i['title'] ?> (<?= $i['quantity'] ?>)</li>
<?php endwhile; ?>
</ul>

<hr>

<?php endwhile; ?>

<a href="home.php">Back</a>

</body>
</html>
