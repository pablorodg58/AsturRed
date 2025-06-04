<?php
session_start();
include "conexion.php";

if (isset($_POST['btnRegister'])) {
    $txtUsername = trim($_POST['name']);
    $txtPassword = $_POST['password'];
    $txtEmail = $_POST['email'] ?? null;

    // Validación
    if (empty($txtUsername) || empty($txtPassword)) {
        $_SESSION['error_message'] = "❌ Nombre de usuario y contraseña son obligatorios.";
        header("Location: register.php");
        exit();
    }

    // Verificar usuario existente
    $checkUser = $conn->prepare("SELECT id FROM users WHERE name = ?");
    $checkUser->bind_param("s", $txtUsername);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        $_SESSION['error_message'] = "❌ El nombre de usuario ya está en uso.";
        header("Location: register.php");
        exit();
    }
    $checkUser->close();

    // Registrar nuevo usuario
    $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);
    $insertUser = $conn->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
    $insertUser->bind_param("sss", $txtUsername, $hashedPassword, $txtEmail);
    
    if ($insertUser->execute()) {
        $_SESSION['new_user'] = true;
        $_SESSION['message'] = "✅ Registro exitoso. Por favor inicia sesión.";
        header("Location: loginform.php");
        exit();
    } else {
        $_SESSION['error_message'] = "❌ Error al registrar: " . $conn->error;
        header("Location: register.php");
        exit();
    }
    
    $insertUser->close();
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
?>