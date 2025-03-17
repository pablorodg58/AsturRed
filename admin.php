<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "❌ No hay sesión iniciada. Redirigiendo a Iniciar Sesion...";
    header("Refresh: 2; URL=loginform.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> 🎉</h1>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
