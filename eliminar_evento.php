<?php
session_start();

if (!isset($_SESSION['business_username']) || $_SESSION['role'] !== 'ayuntamiento') {
    header("Location: loginform.php");
    exit();
}

include "conexion.php";

$event_id = $_GET['id'];

// Verificar que el evento fue creado por el ayuntamiento actual
$stmt = $conn->prepare("SELECT id FROM events WHERE id = ? AND created_by = ?");
$stmt->bind_param("is", $event_id, $_SESSION['business_username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "✅ Evento eliminado con éxito.";
    } else {
        $_SESSION['error_message'] = "❌ Error al eliminar el evento.";
    }
} else {
    $_SESSION['error_message'] = "❌ No tienes permiso para eliminar este evento.";
}

header("Location: index.php");
exit();
?>