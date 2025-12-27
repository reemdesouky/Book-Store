<?php
include "../includes/auth.php";
require_once "../includes/db.php";

$cid = $_SESSION['id'];

$cart = $conn->query("
    SELECT cart_id FROM Shopping_Cart WHERE customer_id = $cid
")->fetch_assoc();

if (!$cart) die("Cart not found");

$cart_id = $cart['cart_id'];

$items = $conn->query("
    SELECT c.ISBN, c.quantity, b.price
    FROM Cart_Content c
    JOIN Book b ON b.ISBN = c.ISBN
    WHERE c.cart_id = $cart_id
");

$total = 0;
foreach ($items as $i) {
    $total += $i['price'] * $i['quantity'];
}

// Process payment only if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['cc_number']) || !isset($_POST['expiry'])) {
        die("Invalid payment data");
    }

    if (!preg_match('/^[0-9]{16}$/', $_POST['cc_number'])) {
        die("Invalid credit card number");
    }

    if (strtotime($_POST['expiry']) < time()) {
        die("Card expired");
    }

    /* Place order */
    $conn->query("
        INSERT INTO Customer_Order (customer_id, total_price)
        VALUES ($cid, $total)
    ");

    $order_id = $conn->insert_id;

    /* Insert order items (TRIGGER deducts stock) */
    $items->data_seek(0);
    while ($i = $items->fetch_assoc()) {
        $conn->query("
            INSERT INTO Order_Item (order_id, ISBN, quantity)
            VALUES ($order_id, '{$i['ISBN']}', {$i['quantity']})
        ");
    }

    /* Clear cart */
    $conn->query("DELETE FROM Cart_Content WHERE cart_id = $cart_id");

    echo "<h2>Checkout Successful</h2>";
    echo "<p>Your order has been placed successfully.</p>";
    echo '<a href="orders.php">View Orders</a> | <a href="home.php">Home</a>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Checkout</h2>
<p>Total Amount: $<?php echo number_format($total, 2); ?></p>

<form method="POST" action="">
    <label>Credit Card Number:</label><br>
    <input type="text" name="cc_number" placeholder="16-digit card number" maxlength="16" required><br><br>

    <label>Expiry Date:</label><br>
    <input type="month" name="expiry" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>
