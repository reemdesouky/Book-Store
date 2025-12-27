<?php
include "../includes/auth.php";
require_once "../includes/db.php";
require_role('admin');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>System Reports</h2>

<!-- Total sales last month -->
<h3>Total Sales – Previous Month</h3>
<?php
$r = $conn->query("
    SELECT SUM(total_price) AS total
    FROM Customer_Order
    WHERE order_date <= CURRENT_DATE - INTERVAL 1 MONTH;
");
echo ($r->fetch_assoc()['total'] ?? 0) . " EGP";
?>

<hr>


<!-- Sales on specific day -->
<h3>Total Sales on Specific Day</h3>
<form method="get">
    <input type="date" name="day" required>
    <button>Show</button>
</form>

<?php
if (isset($_GET['day'])) {
    $d = $_GET['day'];
    $stmt = $conn->prepare("
        SELECT SUM(total_price) AS total
        FROM Customer_Order
        WHERE DATE(order_date) = ?
    ");
    $stmt->bind_param("s", $d);
    $stmt->execute();
    echo "Total: " . ($stmt->get_result()->fetch_assoc()['total'] ?? 0) . " EGP";
}
?>

<hr>

<!-- Top 5 customers (last 3 months) -->
<h3>Top 5 Customers (Last 3 Months)</h3>
<?php
$r = $conn->query("
    SELECT c.user_name, SUM(o.total_price) AS spent
    FROM Customer_Order o
    JOIN Customer c ON c.customer_id = o.customer_id
    WHERE o.order_date >= CURRENT_DATE - INTERVAL 3 MONTH
    GROUP BY c.user_name
    ORDER BY spent DESC
    LIMIT 5
");

while ($row = $r->fetch_assoc()) {
    echo "<p>{$row['user_name']} – {$row['spent']} EGP</p>";
}
?>

<hr>

<!-- Top 10 selling books -->

<h3>Top 10 Selling Books (Last 3 Months)</h3>
<?php
$r = $conn->query("
    SELECT b.title, SUM(oi.quantity) AS sold
    FROM Order_Item oi
    JOIN Book b ON b.ISBN = oi.ISBN
    JOIN Customer_Order co ON co.order_id = oi.order_id
    WHERE co.order_date >= CURRENT_DATE - INTERVAL 3 MONTH
    GROUP BY b.title
    ORDER BY sold DESC
    LIMIT 10
");

while ($row = $r->fetch_assoc()) {
    echo "<p>{$row['title']} – {$row['sold']} copies</p>";
}
?>

<hr>

<!-- Number of times a book was reordered -->
<h3>Book Reorder Count</h3>

<form method="get">
    <input name="isbn" placeholder="Enter ISBN" required>
    <button>Check</button>
</form>

<?php
if (isset($_GET['isbn'])) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS times
        FROM Pub_Order_Contains
        WHERE ISBN = ?
    ");
    $stmt->bind_param("s", $_GET['isbn']);
    $stmt->execute();
    echo "Reordered " . $stmt->get_result()->fetch_assoc()['times'] . " times";
}
?>

<br><br>
<a href="dashboard.php">Back</a>

</body>
</html>
