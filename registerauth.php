<?php
session_start();

if (isset($_POST['btnRegister'])) {
    include "conexion.php"; 

    $txtUsername = $_POST['name'];  
    $txtPassword = $_POST['password'];  

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $txtUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "❌ El nombre de usuario ya está en uso. <a href='register.php'>Volver</a>";
        exit();
    }

    $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $txtUsername, $hashedPassword);
    
    if ($stmt->execute()) {
        $_SESSION['admin'] = $txtUsername; 
        header("Location: index.php");
        exit();
    } else {
        echo "❌ Error al registrar el usuario.";
    }
}
?>
