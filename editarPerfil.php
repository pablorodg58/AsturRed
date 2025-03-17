<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['username'])) {
    header("Location: loginform.php");
    exit();
}

$username = $_SESSION['username'];
$msg = "";

// Procesar la subida de imágenes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profile_image = $_FILES['profile_image'];
    $header_image = $_FILES['header_image'];

    // Función para subir archivos
    function uploadImage($file, $folder) {
        $targetDir = "uploads/$folder/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = basename($file["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        }
        return null;
    }

    $profilePath = !empty($profile_image['name']) ? uploadImage($profile_image, "profile_pics") : null;
    $headerPath = !empty($header_image['name']) ? uploadImage($header_image, "headers") : null;

    // Actualizar en la base de datos
    if ($profilePath || $headerPath) {
        $updateQuery = "UPDATE users SET ";
        $updateParts = [];
        if ($profilePath) {
            $updateParts[] = "profile_image='$profilePath'";
        }
        if ($headerPath) {
            $updateParts[] = "header_image='$headerPath'";
        }
        $updateQuery .= implode(", ", $updateParts) . " WHERE name='$username'";
        mysqli_query($conn, $updateQuery);
        $msg = "✅ Perfil actualizado correctamente.";
    } else {
        $msg = "❌ No se subió ninguna imagen.";
    }
}

// Obtener datos actuales del usuario
$query = "SELECT * FROM users WHERE name='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Perfil</h2>
        <p><?= $msg ?></p>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Imagen de Perfil</label><br>
                <input type="file" name="profile_image" class="form-control">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?= $user['profile_image'] ?>" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen de Encabezado</label><br>
                <input type="file" name="header_image" class="form-control">
                <?php if (!empty($user['header_image'])): ?>
                    <img src="<?= $user['header_image'] ?>" width="200" class="mt-2">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
        <a href="profile.php" class="btn btn-secondary mt-3">Volver al perfil</a>
    </div>
</body>
</html>
