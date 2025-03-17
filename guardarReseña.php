<?php
include 'conexion.php'; // Archivo que conecta a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $negocio_id = $_POST['negocio_id'];
    $usuario_id = 1; // ID del usuario autenticado (esto debe venir de la sesión)
    $comentario = $_POST['comentario'];
    $puntuacion = $_POST['puntuacion'];

    $sql = "INSERT INTO reseñas (negocio_id, usuario_id, comentario, puntuacion) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $negocio_id, $usuario_id, $comentario, $puntuacion);

    if ($stmt->execute()) {
        echo "Reseña guardada con éxito.";
    } else {
        echo "Error al guardar la reseña.";
    }
}
?>
