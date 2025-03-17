<?php
session_start();

if (!isset($_SESSION['business_username']) || $_SESSION['role'] !== 'ayuntamiento') {
    header("Location: loginform.php");
    exit();
}

include "conexion.php";

$event_id = $_GET['id'];

// Obtener los datos del evento
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND created_by = ?");
$stmt->bind_param("is", $event_id, $_SESSION['business_username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$event = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $description, $date, $location, $event_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "✅ Evento actualizado con éxito.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "❌ Error al actualizar el evento.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - AsturRed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Evento</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title">Título</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description">Descripción</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($event['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="date">Fecha</label>
                <input type="datetime-local" class="form-control" id="date" name="date" value="<?= htmlspecialchars($event['date']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="location">Ubicación</label>
                <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>