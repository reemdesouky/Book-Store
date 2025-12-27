<?php
include "../includes/auth.php";
require_once "../includes/db.php";
require_role('customer');

$results = [];

if (isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";

    $stmt = $conn->prepare("
        SELECT DISTINCT b.ISBN, b.title, b.price, b.quantity
        FROM Book b
        LEFT JOIN Written_By wb ON b.ISBN = wb.ISBN
        LEFT JOIN Author a ON wb.author_id = a.author_id
        LEFT JOIN Publisher p ON b.publisher_id = p.publisher_id
        LEFT JOIN Category c ON b.category_id = c.category_id
        WHERE b.ISBN LIKE ?
           OR b.title LIKE ?
           OR a.author_name LIKE ?
           OR p.publisher_name LIKE ?
           OR c.category_name LIKE ?
    ");
    $stmt->bind_param("sssss", $q, $q, $q, $q, $q);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Search Books</h2>

<form method="get">
    <input name="q" placeholder="ISBN, title, author, category, publisher">
    <button>Search</button>
</form>

<?php if ($results): ?>
<table>
<tr><th>Title</th><th>Price</th><th>Stock</th></tr>
<?php while ($r = $results->fetch_assoc()): ?>
<tr>
    <td><?= $r['title'] ?></td>
    <td><?= $r['price'] ?></td>
    <td><?= $r['quantity'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>

<a href="home.php">Back</a>

</body>
</html>
