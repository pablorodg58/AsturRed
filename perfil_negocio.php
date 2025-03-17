<?php
// perfil_negocio.php

// Incluir la conexión a la base de datos
include "conexion.php";

// Obtener el ID del negocio desde la URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Obtener los datos del negocio
    $query = "SELECT * FROM businesses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $negocio = $result->fetch_assoc();
    } else {
        die("Negocio no encontrado.");
    }
} else {
    die("ID de negocio no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($negocio['business_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($negocio['business_name']) ?></h1>
        <img src="uploads/<?= htmlspecialchars($negocio['profile_pic']) ?>" alt="Foto de perfil" style="width: 100px; height: 100px; border-radius: 50%;">
        <p><strong>Dirección:</strong> <?= htmlspecialchars($negocio['address']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($negocio['phone']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($negocio['description']) ?></p>
    </div>
</body>
</html>