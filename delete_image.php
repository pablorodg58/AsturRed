<?php
session_start();

if (!isset($_SESSION['business_username'])) {
    header("Location: loginform.php");
    exit();
}

include "conexion.php";

if (isset($_POST['image_id'])) {
    $image_id = $_POST['image_id'];

    // Obtener la ruta de la imagen
    $stmt = $conn->prepare("SELECT image_path FROM business_gallery WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $image_path = $row['image_path'];

        // Eliminar la imagen de la base de datos
        $stmt = $conn->prepare("DELETE FROM business_gallery WHERE id = ?");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();

        // Eliminar la imagen del servidor
        if (file_exists("uploads/$image_path")) {
            unlink("uploads/$image_path");
        }
    }
}

header("Location: MiNegocio.php");
exit();
?>