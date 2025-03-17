<?php
session_start();

if (!isset($_SESSION['business_username']) || $_SESSION['role'] !== 'ayuntamiento') {
    header("Location: loginform.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "conexion.php";

    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $created_by = $_SESSION['business_username'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, date, location, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $date, $location, $created_by);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "✅ Evento publicado con éxito.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "❌ Error al publicar el evento.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - AsturRed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .event-form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .event-form-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #004955;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: #004955;
            box-shadow: 0 0 8px rgba(0, 73, 85, 0.3);
            outline: none;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #004955;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #00303a;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .success-message {
            color: #28a745;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="event-form-container">
        <h2>Crear Evento</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?= $_SESSION['error_message'] ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message"><?= $_SESSION['success_message'] ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="title">Título del Evento</label>
                <input type="text" id="title" name="title" placeholder="Ingresa el título del evento" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="5" placeholder="Describe el evento" required></textarea>
            </div>
            <div class="form-group">
                <label for="date">Fecha y Hora</label>
                <input type="datetime-local" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="location">Ubicación</label>
                <input type="text" id="location" name="location" placeholder="Ingresa la ubicación del evento" required>
            </div>
            <button type="submit" class="btn btn-primary">Publicar Evento</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>