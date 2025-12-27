<?php
session_start();

/* User must be logged in */
if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: /bookstore/index.php");
    exit();
}

/* Optional role check helper */
function require_role($role) {
    if ($_SESSION['role'] !== $role) {
        die("Access denied");
    }
}
?>
