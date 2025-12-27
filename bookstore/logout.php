<?php
session_start();
require_once "includes/db.php";

// If the user is a customer, delete their cart
if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer' && isset($_SESSION['id'])) {
    $cid = $_SESSION['id'];

    $cart = $conn->query("SELECT cart_id FROM Shopping_Cart WHERE customer_id = $cid")->fetch_assoc();

    if ($cart) {
        $cart_id = $cart['cart_id'];

        $conn->query("DELETE FROM Cart_Content WHERE cart_id = $cart_id");

        $conn->query("DELETE FROM Shopping_Cart WHERE cart_id = $cart_id");
    }
}

// Destroy session
session_unset();
session_destroy();

header("Location: index.php");
exit();
