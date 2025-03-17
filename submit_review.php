<?php
// submit_review.php

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Debes iniciar sesión para dejar una reseña.'
    ]);
    exit();
}

// Incluir la conexión a la base de datos
include "conexion.php";

// Obtener datos del formulario
$business_name = $_POST['business_name'] ?? '';
$rating = $_POST['rating'] ?? '';
$review_text = $_POST['review_text'] ?? '';
$username = $_SESSION['username'];  // Obtener el nombre de usuario de la sesión

// Validar los datos
if (empty($business_name) || empty($rating) || empty($review_text)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Todos los campos son obligatorios.'
    ]);
    exit();
}

// Verificar si el usuario ya ha dejado una reseña para este negocio
$sql_check = "SELECT id FROM reviews WHERE username = ? AND business_name = ?";
$stmt_check = $conn->prepare($sql_check);

if ($stmt_check) {
    $stmt_check->bind_param("ss", $username, $business_name);
    $stmt_check->execute();
    $stmt_check->store_result();

    // Si ya existe una reseña, no permitir duplicados
    if ($stmt_check->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ya has dejado una reseña para este negocio.'
        ]);
        $stmt_check->close();
        exit();
    }

    $stmt_check->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al verificar reseñas existentes.'
    ]);
    exit();
}

// Procesar la imagen de la reseña
$image_path = null;
if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (in_array($_FILES['review_image']['type'], $allowed_types)) {
        if ($_FILES['review_image']['size'] <= $max_size) {
            $extension = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
            $filename = 'review_' . $username . '_' . time() . '.' . $extension;
            $target = 'uploads/' . $filename;

            if (move_uploaded_file($_FILES['review_image']['tmp_name'], $target)) {
                $image_path = $filename;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al subir la imagen.'
                ]);
                exit();
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'La imagen supera el tamaño máximo permitido (5MB).'
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Formato de imagen no permitido (solo JPG, PNG, GIF).'
        ]);
        exit();
    }
}

// Insertar la reseña en la base de datos
$sql = "INSERT INTO reviews (username, business_name, review_text, rating, image_path) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssis", $username, $business_name, $review_text, $rating, $image_path);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Reseña enviada correctamente.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al enviar la reseña.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al preparar la consulta.'
    ]);
}

// Cerrar la conexión
$conn->close();
?>