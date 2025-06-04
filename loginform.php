<?php
session_start();

// Si ya está logueado, redirigir al index
if (isset($_SESSION['username']) || isset($_SESSION['business_username']) || isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// Manejo especial para usuarios recién registrados
if (isset($_SESSION['new_user'])) {
    $redirect_url = 'index.php';
    unset($_SESSION['new_user']);
} else {
    // Redirección normal
    $redirect_url = $_GET['redirect_to'] ?? 'index.php';
    
    // Validar URL para evitar bucles
    $excluded_pages = ['loginform.php', 'loginauth.php', 'register.php', 'registerauth.php'];
    $path = parse_url($redirect_url, PHP_URL_PATH);
    $filename = basename($path);
    
    if (in_array($filename, $excluded_pages)) {
        $redirect_url = 'index.php';
    }
}

// Limpiar la URL
$redirect_url = htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AsturRed</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="login-container">
        <form action="loginauth.php" method="post" class="form-container">
            <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo"> 
            <h2>Iniciar Sesión</h2>

            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['message'])) {
                echo "<p class='success'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>

            <input type="text" name="name" placeholder="Nombre de usuario" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="hidden" name="redirect_to" value="<?php echo $redirect_url; ?>">
            <input type="submit" name="btnLogin" value="Iniciar Sesión">
        </form>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</body>
</html>