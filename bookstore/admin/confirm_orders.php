<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'admin') die("Access denied");

if (isset($_POST['confirm'])) {

    $stmt = $conn->prepare("
        UPDATE Publisher_Order
        SET order_status = 'Confirmed'
        WHERE order_id = ?
    ");

    $stmt->bind_param("i", $_POST['order_id']);
    $stmt->execute();
}

$orders = $conn->query("
    SELECT order_id, order_date, order_status
    FROM Publisher_Order
    WHERE order_status = 'Pending'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Publisher Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Pending Publisher Orders</h2>

<table border="1">
<tr>
    <th>Order ID</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while ($o = $orders->fetch_assoc()): ?>
<tr>
<form method="post">
    <td><?= $o['order_id'] ?></td>
    <td><?= $o['order_date'] ?></td>
    <td>
        <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
        <button name="confirm">Confirm</button>
    </td>
</form>
</tr>
<?php endwhile; ?>

</table>

<a href="dashboard.php">Back</a>

</body>
</html>
