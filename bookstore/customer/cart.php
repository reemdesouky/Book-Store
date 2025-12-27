<?php
include "../includes/auth.php";
require_once "../includes/db.php";

$cid = $_SESSION['id'];

/* Ensure cart exists */
$conn->query("
    INSERT IGNORE INTO Shopping_Cart (customer_id)
    VALUES ($cid)
");

$cart_id = $conn->query("
    SELECT cart_id FROM Shopping_Cart WHERE customer_id = $cid
")->fetch_assoc()['cart_id'];

/* Add item */
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("
        INSERT INTO Cart_Content (cart_id, ISBN, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
    ");
    $stmt->bind_param("isi", $cart_id, $_POST['isbn'], $_POST['qty']);
    $stmt->execute();
}

/* Remove item */
if (isset($_POST['remove'])) {
    $stmt = $conn->prepare("
        DELETE FROM Cart_Content WHERE cart_id=? AND ISBN=?
    ");
    $stmt->bind_param("is", $cart_id, $_POST['isbn']);
    $stmt->execute();
}

$items = $conn->query("
    SELECT b.title, b.price, c.quantity, c.ISBN
    FROM Cart_Content c
    JOIN Book b ON b.ISBN = c.ISBN
    WHERE c.cart_id = $cart_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Your Cart</h2>

<table border="1">
<tr>
    <th>Book</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Remove</th>
</tr>

<?php $total = 0; ?>
<?php while ($i = $items->fetch_assoc()): ?>
<tr>
    <td><?= $i['title'] ?></td>
    <td><?= $i['price'] ?></td>
    <td><?= $i['quantity'] ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="isbn" value="<?= $i['ISBN'] ?>">
            <button name="remove">Remove</button>
        </form>
    </td>
</tr>
<?php $total += $i['price'] * $i['quantity']; ?>
<?php endwhile; ?>

</table>

<p><strong>Total:</strong> <?= $total ?> EGP</p>

<a href="checkout.php">Checkout</a> |
<a href="home.php">Continue Shopping</a>

</body>
</html>
