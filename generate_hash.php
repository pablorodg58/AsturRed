<?php
$password = "123"; // Contraseña a hashear
echo password_hash($password, PASSWORD_DEFAULT);
?>