<?php
include "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['role'] !== 'customer') {
    die("Access denied");
}

$customer_id = $_SESSION['id'];

// Fetch current customer info
$stmt = $conn->prepare("
    SELECT Fname, Lname, user_name, email, customer_password, customer_address, phone_number
    FROM Customer
    WHERE customer_id = ?
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    die("Customer not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Fname = trim($_POST['Fname']);
    $Lname = trim($_POST['Lname']);
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password = $_POST['customer_password'];
    $address = trim($_POST['customer_address']);
    $phone = trim($_POST['phone_number']);

    if (empty($Fname) || empty($Lname) || empty($user_name) || empty($email)) {
        $error = "First name, Last name, Username, and Email are required";
    } else {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("
                UPDATE Customer SET Fname=?, Lname=?, user_name=?, email=?, customer_password=?, customer_address=?, phone_number=?
                WHERE customer_id=?
            ");
            $stmt->bind_param("sssssssi", $Fname, $Lname, $user_name, $email, $hashed, $address, $phone, $customer_id);
        } else {
            $stmt = $conn->prepare("
                UPDATE Customer SET Fname=?, Lname=?, user_name=?, email=?, customer_address=?, phone_number=?
                WHERE customer_id=?
            ");
            $stmt->bind_param("ssssssi", $Fname, $Lname, $user_name, $email, $address, $phone, $customer_id);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully";
            // Refresh customer data
            $customer = [
                'Fname' => $Fname,
                'Lname' => $Lname,
                'user_name' => $user_name,
                'email' => $email,
                'customer_address' => $address,
                'phone_number' => $phone
            ];
        } else {
            $error = "Failed to update profile";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Edit My Info</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="post">
    <label>First Name:</label><br>
    <input type="text" name="Fname" value="<?= htmlspecialchars($customer['Fname']) ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="Lname" value="<?= htmlspecialchars($customer['Lname']) ?>" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="user_name" value="<?= htmlspecialchars($customer['user_name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required><br><br>

    <label>New Password (leave blank to keep current):</label><br>
    <input type="password" name="customer_password"><br><br>

    <label>Address:</label><br>
    <input type="text" name="customer_address" value="<?= htmlspecialchars($customer['customer_address']) ?>"><br><br>

    <label>Phone Number:</label><br>
    <input type="text" name="phone_number" value="<?= htmlspecialchars($customer['phone_number']) ?>"><br><br>

    <button type="submit">Update Info</button>
</form>

<br>
<a href="home.php">Back to Home</a>

</body>
</html>
