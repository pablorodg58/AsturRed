<?php
session_start();
include "conexion.php";

// Validar URL de redirección
$redirect_url = 'index.php';
if (isset($_POST['redirect_to'])) {
    $temp_url = filter_var($_POST['redirect_to'], FILTER_SANITIZE_URL);
    
    // Validar que no sea una página sensible
    $excluded_pages = ['loginform.php', 'loginauth.php', 'register.php', 'registerauth.php'];
    $path = parse_url($temp_url, PHP_URL_PATH);
    $filename = basename($path);
    
    if (!in_array($filename, $excluded_pages) && filter_var($temp_url, FILTER_VALIDATE_URL)) {
        $redirect_url = $temp_url;
    }
}

if (isset($_POST['btnLogin'])) {
    $txtUsername = trim($_POST['name']);
    $txtPassword = $_POST['password'];

    // Verificar admin
    if ($txtUsername === "admin" && $txtPassword === "admin") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    }

    // Buscar en usuarios turistas
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $txtUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($txtPassword, $row['password'])) {
            $_SESSION['username'] = $txtUsername;
            $_SESSION['user_id'] = $row['id'];
            header("Location: " . $redirect_url);
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Contraseña incorrecta.";
        }
    } else {
        // Buscar en negocios/ayuntamientos
        $stmt = $conn->prepare("SELECT * FROM businesses WHERE username = ?");
        $stmt->bind_param("s", $txtUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($txtPassword, $row['password'])) {
                $_SESSION['business_username'] = $txtUsername;
                $_SESSION['business_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                header("Location: " . $redirect_url);
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
    header("Location: loginform.php");
    exit();
}

// Si alguien accede directamente al script
header("Location: loginform.php");
exit();
?>