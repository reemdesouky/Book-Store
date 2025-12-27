<?php
include "../includes/auth.php";
require_once "../includes/db.php";
require_role('admin');

$orders = $conn->query("
    SELECT po.order_id, po.order_date, po.order_status,
           b.title, poc.quantity
    FROM Publisher_Order po
    JOIN Pub_Order_Contains poc ON po.order_id = poc.order_id
    JOIN Book b ON b.ISBN = poc.ISBN
    ORDER BY po.order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Publisher Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Publisher Orders History</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Book</th>
    <th>Quantity</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php while ($o = $orders->fetch_assoc()): ?>
<tr>
    <td><?= $o['order_id'] ?></td>
    <td><?= $o['title'] ?></td>
    <td><?= $o['quantity'] ?></td>
    <td><?= $o['order_date'] ?></td>
    <td><?= $o['order_status'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<a href="dashboard.php">Back</a>

</body>
</html>
