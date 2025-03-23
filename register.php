<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AsturRed</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.8);
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #004955;
        }
        .type-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }
        .type-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }
        .type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0, 111, 128, 0.1), rgba(0, 111, 128, 0.3));
            transition: left 0.5s;
        }
        .type-card:hover::before {
            left: 100%;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 111, 128, 0.3);
            background: linear-gradient(135deg, #006F80, #004955);
            color: #004955;
        }
        .type-card:hover h3 {
            color: #fff;
        }
        .type-card:hover p {
            color: rgba(255, 255, 255, 0.8);
        }
        .type-card h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #004955;
            transition: color 0.3s;
            position: relative;
            z-index: 1;
        }
        .type-card p {
            color: #666;
            font-size: 0.9em;
            transition: color 0.3s;
            position: relative;
            z-index: 1;
        }
        .login-container p {
            margin-top: 15px;
            color: #666;
        }
        .login-container a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo">
        <h2>Crear Cuenta</h2>
        <p>Selecciona el tipo de cuenta que deseas crear:</p>

        <div class="type-container">
            <a href="register_tourist.php" class="type-card">
                <h3>Turista</h3>
                <p>Regístrate para explorar y descubrir nuevos lugares.</p>
            </a>
            <a href="register_business.php" class="type-card">
                <h3>Negocio Local</h3>
                <p>Regístrate para promocionar tu negocio y llegar a más clientes.</p>
            </a>
        </div>

        <p>¿Ya tienes cuenta? <a href="loginform.php" style="color: #004955; text-decoration:none"><strong>Iniciar sesión</strong></a></p>
    </div>
</body>
</html>