<?php
session_start(); // Iniciar sesión solo una vez

if (!isset($_SESSION['username'])) {
    header("Location: loginform.php");
    exit();
}

include "conexion.php"; // Incluir conexión a la base de datos

$username = $_SESSION['username'];
$profile_pic = 'default-profile.jpg';
$banner_pic = 'default-banner.jpg';
$description = '';
$location = '';

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT profile_pic, banner_pic, description, location FROM users WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $profile_pic = $row['profile_pic'] ?? $profile_pic;
    $banner_pic = $row['banner_pic'] ?? $banner_pic;
    $description = $row['description'] ?? '';
    $location = $row['location'] ?? '';
}

// Si no hay banner cargado, usar una imagen predeterminada
if (empty($banner_pic)) {
    $banner_pic = 'default-banner.jpg'; // Imagen predeterminada
}

// Procesar subida de imágenes y actualización de descripción y ubicación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_errors = [];
    
    // Subir foto de perfil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES['profile_pic']['type'], $allowed_types)) {
            if ($_FILES['profile_pic']['size'] <= $max_size) {
                $extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $username . '_' . time() . '.' . $extension;
                $target = 'uploads/' . $filename;
                
                // Crear la carpeta 'uploads' si no existe
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                // Eliminar la imagen anterior si existe
                if ($profile_pic !== 'default-profile.jpg' && file_exists('uploads/' . $profile_pic)) {
                    unlink('uploads/' . $profile_pic);
                }

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    // Actualizar base de datos
                    $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE name = ?");
                    $stmt->bind_param("ss", $filename, $username);
                    $stmt->execute();
                    $profile_pic = $filename;
                } else {
                    $upload_errors[] = "Error al subir la imagen de perfil. Verifica los permisos de la carpeta 'uploads'.";
                }
            } else {
                $upload_errors[] = "La imagen de perfil supera el tamaño máximo (2MB)";
            }
        } else {
            $upload_errors[] = "Formato de imagen no permitido (solo JPG, PNG, GIF)";
        }
    }

    // Subir foto de banner
    if (isset($_FILES['banner_pic']) && $_FILES['banner_pic']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($_FILES['banner_pic']['type'], $allowed_types)) {
            if ($_FILES['banner_pic']['size'] <= $max_size) {
                $extension = pathinfo($_FILES['banner_pic']['name'], PATHINFO_EXTENSION);
                $filename = 'banner_' . $username . '_' . time() . '.' . $extension;
                $target = 'uploads/' . $filename;
                
                // Eliminar la imagen anterior si existe
                if ($banner_pic !== 'default-banner.jpg' && file_exists('uploads/' . $banner_pic)) {
                    unlink('uploads/' . $banner_pic);
                }

                if (move_uploaded_file($_FILES['banner_pic']['tmp_name'], $target)) {
                    // Actualizar base de datos
                    $stmt = $conn->prepare("UPDATE users SET banner_pic = ? WHERE name = ?");
                    $stmt->bind_param("ss", $filename, $username);
                    $stmt->execute();
                    $banner_pic = $filename;
                } else {
                    $upload_errors[] = "Error al subir el banner. Verifica los permisos de la carpeta 'uploads'.";
                }
            } else {
                $upload_errors[] = "El banner supera el tamaño máximo (5MB)";
            }
        } else {
            $upload_errors[] = "Formato de banner no permitido (solo JPG, PNG)";
        }
    }

    // Actualizar descripción y ubicación
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $stmt = $conn->prepare("UPDATE users SET description = ? WHERE name = ?");
        $stmt->bind_param("ss", $description, $username);
        $stmt->execute();
    }

    if (isset($_POST['location'])) {
        $location = $_POST['location'];
        $stmt = $conn->prepare("UPDATE users SET location = ? WHERE name = ?");
        $stmt->bind_param("ss", $location, $username);
        $stmt->execute();
    }
}

// Obtener reseñas del usuario (incluyendo la imagen si existe)
$reviews = [];
$stmt = $conn->prepare("SELECT business_name, review_text, rating, created_at, image_path FROM reviews WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - AsturRed</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f8fa;
        }
        .profile-container {
            max-width: 800px;
            margin: 20px auto 20px; /* Margen superior reducido a 20px */
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .profile-header {
            position: relative;
            background-color: #e1e8ed;
        }
        .cover-photo {
            width: 100%;
            height: 200px; /* Altura fija para el banner */
            object-fit: cover;
            background-color: #e1e8ed; /* Color de fondo si no hay imagen */
            background-image: url('uploads/default-banner.jpg'); /* Imagen predeterminada */
            background-size: cover;
            background-position: center;
        }
        .profile-pic-container {
            position: absolute;
            bottom: -50px; /* Posicionar la foto de perfil sobre el banner */
            left: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover; /* Asegurar que la imagen cubra el espacio */
        }
        .profile-info {
            padding: 80px 20px 20px; /* Añadir más padding arriba para evitar superposición */
            text-align: left;
        }
        .profile-info h1 {
            font-size: 1.5em;
            margin-bottom: 5px;
        }
        .profile-info p {
            color: gray;
        }
        .edit-button {
            margin-left: 10px;
            font-size: 0.9em;
            color: #007bff;
            cursor: pointer;
        }
        .edit-button:hover {
            text-decoration: underline;
        }
        .review-container {
            padding: 20px;
        }
        .review {
            border-top: 1px solid #ddd;
            padding: 10px 0;
            margin-top: 10px;
        }
        .review h3 {
            margin: 0;
            font-size: 1em;
            font-weight: bold;
            color: black; /* Color negro para el nombre del negocio */
        }
        .review p {
            margin: 5px 0;
            font-size: 0.9em;
            color: black; /* Color negro para el texto de la reseña */
        }
        .review small {
            color: gray; /* Color gris para la fecha */
        }
        .review img {
            max-width: 100%; /* Asegurar que la imagen no exceda el ancho del contenedor */
            border-radius: 8px; /* Bordes redondeados */
            margin-top: 10px; /* Espacio entre el texto y la imagen */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
            display: block; /* Asegurar que la imagen sea un bloque */
            margin-bottom: 10px; /* Espacio debajo de la imagen */
        }
        .stars {
            color: gold;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

    <!-- NavBar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="pueblos.php">Pueblos</a></li>
          <li class="nav-item"><a class="nav-link" href="eventos.php">Eventos</a></li>
          <?php if (isset($_SESSION['username']) || isset($_SESSION['admin_logged_in']) || isset($_SESSION['business_username'])): ?>
            <?php if (isset($_SESSION['business_username']) && $_SESSION['role'] === 'negocio'): ?>
              <!-- Si es un negocio, mostrar "Mi Negocio" -->
              <li class="nav-item"><a class="nav-link" href="MiNegocio.php">Mi Negocio</a></li>
            <?php elseif (isset($_SESSION['business_username']) && $_SESSION['role'] === 'ayuntamiento'): ?>
              <!-- Si es un ayuntamiento, mostrar "Crear Evento" -->
              <li class="nav-item"><a class="nav-link" href="crear_evento.php">Crear Evento</a></li>
            <?php elseif (isset($_SESSION['username'])): ?>
              <!-- Si es un turista, mostrar "Mi Perfil" -->
              <li class="nav-item"><a class="nav-link" href="miPerfil.php">Mi Perfil</a></li>
            <?php endif; ?>
            <!-- Mostrar "Cerrar Sesión" para ambos -->
            <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
          <?php else: ?>
            <!-- Si no ha iniciado sesión, mostrar "Iniciar Sesión" -->
            <li class="nav-item"><a class="nav-link" href="loginform.php">Iniciar Sesión</a></li>
          <?php endif; ?>
        </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido del perfil -->
    <div class="profile-container">
        <div class="profile-header">
            <img src="uploads/<?= htmlspecialchars($banner_pic) ?>" alt="Foto de encabezado" class="cover-photo">
            <div class="profile-pic-container">
                <img src="uploads/<?= htmlspecialchars($profile_pic) ?>" alt="Foto de perfil" class="profile-pic">
            </div>
        </div>

        <!-- Información del perfil -->
        <div class="profile-info">
            <h1><?= htmlspecialchars($username) ?></h1>
            <p><strong><?= count($reviews) ?> Reseñas</strong></p>
            <p><?= htmlspecialchars($description) ?></p>
            <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($location) ?></p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="bi bi-pencil"></i> Editar perfil
            </button>
        </div>

        <!-- Reseñas del usuario -->
        <div class="review-container">
            <?php if (empty($reviews)): ?>
                <p>No has escrito ninguna reseña todavía.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <h3><?= htmlspecialchars($review['business_name']) ?> <span class="stars"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></span></h3>
                        <p><?= htmlspecialchars($review['review_text']) ?></p>
                        <?php if (!empty($review['image_path'])): ?>
                            <img src="uploads/<?= htmlspecialchars($review['image_path']) ?>" alt="Imagen de reseña" class="img-fluid rounded mt-2" style="max-width: 200px;">
                        <?php endif; ?>
                        <small><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="profile_pic" class="form-label">Cambiar foto de perfil</label>
                            <input type="file" class="form-control" name="profile_pic" id="profile_pic" accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div class="mb-3">
                            <label for="banner_pic" class="form-label">Cambiar banner</label>
                            <input type="file" class="form-control" name="banner_pic" id="banner_pic" accept="image/jpeg, image/png">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($location) ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar previsualización de imágenes
        function previewImage(input, previewElement) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Previsualizar la foto de perfil
        document.getElementById('profile_pic').addEventListener('change', function() {
            previewImage(this, document.querySelector('.profile-pic'));
        });

        // Previsualizar el banner
        document.getElementById('banner_pic').addEventListener('change', function() {
            previewImage(this, document.querySelector('.cover-photo'));
        });
    </script>
</body>
</html>