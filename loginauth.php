<?php
session_start();
include "conexion.php";  // Asegúrate de que la conexión se cargue correctamente

if (isset($_POST['btnLogin'])) {
    $txtUsername = trim($_POST['name']);
    $txtPassword = $_POST['password'];

    // Verificar si el usuario es "admin"
    if ($txtUsername === "admin" && $txtPassword === "admin") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");  // Redirigir al panel de administración
        exit();
    }

    // Consultar en la base de datos para usuarios turistas
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $txtUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($txtPassword, $row['password'])) {
            $_SESSION['username'] = $txtUsername;
            header("Location: index.php");  // Redirigir al index después del login
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Contraseña incorrecta.";
        }
    } else {
        // Si no es un usuario turista, verificar si es un negocio o ayuntamiento
        $stmt = $conn->prepare("SELECT * FROM businesses WHERE username = ?");
        $stmt->bind_param("s", $txtUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($txtPassword, $row['password'])) {
                $_SESSION['business_username'] = $txtUsername;
                $_SESSION['role'] = $row['role']; // Guardar el rol (negocio o ayuntamiento)
                header("Location: index.php");  // Redirigir al index después del login
                exit();
            } else {
                $_SESSION['error_message'] = "❌ Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error_message'] = "❌ El usuario no existe.";
        }
    }

    $stmt->close();
    $conn->close();

    // Redirigir de vuelta al formulario de inicio de sesión en caso de error
    header("Location: loginform.php");
    exit();
}
?>