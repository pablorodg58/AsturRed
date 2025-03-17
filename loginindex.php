<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: loginform.php");
    exit();
}

header("Location: index.php");
exit();
?>
