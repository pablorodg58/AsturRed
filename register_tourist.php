<?php
session_start();

if (isset($_POST['btnRegisterTourist'])) {
    include "conexion.php";

    $txtUsername = trim($_POST['name']);  
    $txtPassword = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $txtUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "❌ Este nombre de usuario ya está registrado.";
    } else {
        $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);  
        $stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $txtUsername, $hashedPassword);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Cuenta creada exitosamente.";
            header("Location: loginform.php"); 
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Error al registrar el usuario. Intenta de nuevo.";
        }
    }
    $stmt->close();
    $conn->close();
    header("Location: register_tourist.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Turista - AsturRed</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }
        h2 {
            margin-bottom: 15px;
            font-size: 1.3em;
            color: #333;
        }
        .form-group {
            margin-bottom: 10px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 3px;
            font-weight: bold;
            color: #333;
            font-size: 0.9em;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%;
            padding: 8px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .login-container p {
            margin-top: 10px;
            color: #666;
            font-size: 0.9em;
        }
        .login-container a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9em;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error, .success {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            font-size: 0.85em;
            text-align: center;
        }
        .error {
            background: #ffebee;
            color: #c62828;
        }
        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo">
        <h2>Registro de Turista</h2>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }

        if (isset($_SESSION['success_message'])) {
            echo "<p class='success'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="register_tourist.php" method="post">
            <div class="form-group">
                <label for="name">Nombre de usuario</label>
                <input type="text" name="name" id="name" placeholder="Ingresa tu nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" name="btnRegisterTourist" class="btn-primary">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="loginform.php">Iniciar sesión</a></p>
    </div>
</body>
</html>