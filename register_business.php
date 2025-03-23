<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = ''; // Variable para almacenar mensajes de error

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "conexion.php"; // Incluir la conexión a la base de datos

    // Obtener los datos del formulario
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $business_name = trim($_POST['business_name']);
    $address = trim($_POST['address']);
    $location = trim($_POST['location']); // Cambiado de 'pueblo' a 'location'
    $phone = trim($_POST['phone']);
    $tipo_negocio = trim($_POST['tipo_negocio']);
    $otro_negocio = isset($_POST['otro_negocio']) ? trim($_POST['otro_negocio']) : '';

    // Si selecciona "Otros", usa el texto ingresado
    if ($tipo_negocio === "Otros" && !empty($otro_negocio)) {
        $tipo_negocio = $otro_negocio;
    }

    $role = 'negocio';

    // Verificar si el nombre de usuario o el email ya existen
    $stmt = $conn->prepare("SELECT id FROM businesses WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "❌ El nombre de usuario o el correo electrónico ya están en uso.";
    } else {
        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo negocio en la base de datos
        $stmt = $conn->prepare("INSERT INTO businesses (username, password, email, business_name, address, location, phone, tipo_negocio, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $hashedPassword, $email, $business_name, $address, $location, $phone, $tipo_negocio, $role);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Cuenta de negocio creada exitosamente.";
            header("Location: loginform.php");
            exit();
        } else {
            $error = "❌ Error al registrar el negocio. Intenta de nuevo.";
        }
    }

    // Cerrar la conexión y liberar recursos
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
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 0px;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.8);
            text-align: center;
        }
        .logo {
            width: 100px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .form-group {
            margin-bottom: 8px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 2px;
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
        .flex-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .flex-item {
            flex: 1;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            box-sizing: border-box;
            background: #fff;
            color: #333;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="%23333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><path d="M6 9l6-6 6 6"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
        }
        .form-group select:focus {
            border-color: #004955;
            outline: none;
        }
        .btn-primary {
            width: 80%;
            padding: 8px;
            background: rgba(0, 74, 85, 0);
            color: #004955;
            border: none;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            transition: width 0.4s, background 0.4s, transform 0.4s, color 0.4s;
        }
        .btn-primary:hover {
            width: 100%;
            background: #004955;
            transform: scale(1.00);
            color: #fff;
        }
        .login-container p {
            margin-top: 10px;
            color: #666;
            font-size: 0.9em;
        }
        .login-container a {
            color: #004955;
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
            <div class="form-group flex-container">
                <div class="flex-item">
                    <label for="location">Pueblo</label>
                    <input type="text" name="location" id="location" placeholder="Indica pueblo">
                </div>
                <div class="flex-item">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" id="address" placeholder="Ingresa la dirección">
                </div>
            </div>
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" id="phone" placeholder="Ingresa el teléfono de contacto">
            </div>
            <div class="form-group">
                <label for="tipo_negocio">Tipo de negocio</label>
                <select name="tipo_negocio" id="tipo_negocio" required onchange="mostrarCampoOtro()">
                    <option value="">Seleccione un tipo</option>
                    <option value="Camping">Camping</option>
                    <option value="Carnicería">Carnicería</option>
                    <option value="Confitería">Confitería</option>
                    <option value="Estanco">Estanco</option>
                    <option value="Farmacia">Farmacia</option>
                    <option value="Floristería">Floristería</option>
                    <option value="Frutería">Frutería</option>
                    <option value="Hotel">Hotel</option>
                    <option value="Lavandería">Lavandería</option>
                    <option value="Librería">Librería</option>
                    <option value="Panadería">Panadería</option>
                    <option value="Peluquería">Peluquería</option>
                    <option value="Restaurante">Restaurante</option>
                    <option value="Taller">Taller</option>
                    <option value="Tienda">Tienda</option>
                    <option value="Veterinario">Veterinario</option>
                    <option value="Otros">Otros</option>
                </select>
            </div>
            <div class="form-group" id="campoOtro" style="display: none;">
                <label for="otro_negocio">Especifique su negocio</label>
                <input type="text" name="otro_negocio" id="otro_negocio" placeholder="Ingrese el tipo de negocio">
            </div>

            <button type="submit" name="btnRegisterBusiness" class="btn-primary">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="loginform.php"><strong>Iniciar Sesión</strong></a></p>
    </div>
</body>
</html> 
<script>
     function mostrarCampoOtro() {
        var select = document.getElementById("tipo_negocio");
        var campoOtro = document.getElementById("campoOtro");
        if (select.value === "Otros") {
            campoOtro.style.display = "block"; // Mostrar campo
            document.getElementById("otro_negocio").setAttribute("required", "required"); // Hacer obligatorio
        } else {
            campoOtro.style.display = "none"; // Ocultar campo
            document.getElementById("otro_negocio").removeAttribute("required"); // Quitar obligatoriedad
        }
    }
</script>