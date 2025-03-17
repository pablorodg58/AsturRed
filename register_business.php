<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "conexion.php";

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $business_name = trim($_POST['business_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role']; // Nuevo campo para el rol (negocio o ayuntamiento)

    // Verificar si el nombre de usuario o el email ya existen
    $stmt = $conn->prepare("SELECT id FROM businesses WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "❌ El nombre de usuario o el correo electrónico ya están en uso.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO businesses (username, password, email, business_name, address, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $hashedPassword, $email, $business_name, $address, $phone, $role);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Cuenta de negocio creada exitosamente.";
            header("Location: loginform.php");
            exit();
        } else {
            $error = "❌ Error al registrar el negocio. Intenta de nuevo.";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Negocio - AsturRed</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        body {
            background-color: #f5f8fa;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            font-size: 0.9em;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
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
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background: #ffebee;
            color: #c62828;
            font-size: 0.9em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo">
        <h2>Registro de Negocio</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" name="username" id="username" placeholder="Ingresa tu nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" placeholder="Ingresa tu correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="business_name">Nombre del negocio</label>
                <input type="text" name="business_name" id="business_name" placeholder="Ingresa el nombre de tu negocio" required>
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" name="address" id="address" placeholder="Ingresa la dirección de tu negocio">
            </div>
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" id="phone" placeholder="Ingresa el teléfono de contacto">
            </div>
            <div class="form-group">
                <label for="role">Rol</label>
                <select name="role" id="role" required>
                    <option value="negocio">Negocio</option>
                    <option value="ayuntamiento">Ayuntamiento</option>
                </select>
            </div>
            <button type="submit" name="btnRegisterBusiness" class="btn-primary">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="loginform.php">Iniciar sesión</a></p>
    </div>
</body>
</html>