<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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

            <input type="text" name="name" placeholder="Nombre de usuario" required value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <input type="submit" name="btnLogin" value="Iniciar Sesión">
        </form>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
