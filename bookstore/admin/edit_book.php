<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'admin') die("Access denied");

if (isset($_POST['update'])) {

    $stmt = $conn->prepare("
        UPDATE Book
        SET quantity = ?
        WHERE ISBN = ?
    ");

    $stmt->bind_param("is", $_POST['quantity'], $_POST['isbn']);
    $stmt->execute();
}

$books = $conn->query("SELECT ISBN, title, quantity, Threshold FROM Book");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Books</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Edit Books</h2>

<table border="1">
<tr>
    <th>Title</th>
    <th>Quantity</th>
    <th>Threshold</th>
    <th>Update</th>
</tr>

<?php while ($b = $books->fetch_assoc()): ?>
<tr>
<form method="post">
    <td><?= $b['title'] ?></td>
    <td>
        <input type="number" name="quantity" value="<?= $b['quantity'] ?>">
    </td>
    <td><?= $b['Threshold'] ?></td>
    <td>
        <input type="hidden" name="isbn" value="<?= $b['ISBN'] ?>">
        <button name="update">Update</button>
    </td>
</form>
</tr>
<?php endwhile; ?>

</table>

<a href="dashboard.php">Back</a>

</body>
</html>
