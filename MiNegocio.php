<?php
session_start();

// Si no hay sesión de negocio ni username en GET, redirigir al index
if (!isset($_SESSION['business_username']) && !isset($_GET['username'])) {
    header("Location: index.php");
    exit();
}

include "conexion.php";

$username = $_GET['username'] ?? ($_SESSION['business_username'] ?? '');
$is_owner = isset($_SESSION['business_username']) && ($username === $_SESSION['business_username']);


$profile_pic = 'default-profile.jpg';
$banner_pic = 'default-banner.jpg';
$business_name = '';
$address = '';
$phone = '';
$description = '';
$tipo_negocio = '';
$latitude = null;
$longitude = null;

$stmt = $conn->prepare("SELECT business_name, address, phone, description, profile_pic, banner_pic, tipo_negocio, latitude, longitude FROM businesses WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $business_name = $row['business_name'] ?? '';
    $address = $row['address'] ?? '';
    $phone = $row['phone'] ?? '';
    $description = $row['description'] ?? '';
    $profile_pic = $row['profile_pic'] ?? $profile_pic;
    $banner_pic = $row['banner_pic'] ?? $banner_pic;
    $tipo_negocio = $row['tipo_negocio'] ?? '';
    $latitude = $row['latitude'] ?? null;
    $longitude = $row['longitude'] ?? null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_owner) {
    $upload_errors = [];

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024;

        if (in_array($_FILES['profile_pic']['type'], $allowed_types)) {
            if ($_FILES['profile_pic']['size'] <= $max_size) {
                $extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $username . '_' . time() . '.' . $extension;
                $target = 'uploads/' . $filename;

                if ($profile_pic !== 'default-profile.jpg' && file_exists('uploads/' . $profile_pic)) {
                    unlink('uploads/' . $profile_pic);
                }

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    $stmt = $conn->prepare("UPDATE businesses SET profile_pic = ? WHERE username = ?");
                    $stmt->bind_param("ss", $filename, $username);
                    $stmt->execute();
                    $profile_pic = $filename;
                } else {
                    $upload_errors[] = "Error al subir la imagen de perfil.";
                }
            } else {
                $upload_errors[] = "La imagen de perfil supera el tamaño máximo (2MB).";
            }
        } else {
            $upload_errors[] = "Formato de imagen no permitido (solo JPG, PNG, GIF).";
        }
    }

    if (isset($_FILES['banner_pic']) && $_FILES['banner_pic']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 5 * 1024 * 1024;

        if (in_array($_FILES['banner_pic']['type'], $allowed_types)) {
            if ($_FILES['banner_pic']['size'] <= $max_size) {
                $extension = pathinfo($_FILES['banner_pic']['name'], PATHINFO_EXTENSION);
                $filename = 'banner_' . $username . '_' . time() . '.' . $extension;
                $target = 'uploads/' . $filename;

                if ($banner_pic !== 'default-banner.jpg' && file_exists('uploads/' . $banner_pic)) {
                    unlink('uploads/' . $banner_pic);
                }

                if (move_uploaded_file($_FILES['banner_pic']['tmp_name'], $target)) {
                    $stmt = $conn->prepare("UPDATE businesses SET banner_pic = ? WHERE username = ?");
                    $stmt->bind_param("ss", $filename, $username);
                    $stmt->execute();
                    $banner_pic = $filename;
                } else {
                    $upload_errors[] = "Error al subir el banner.";
                }
            } else {
                $upload_errors[] = "El banner supera el tamaño máximo (5MB).";
            }
        } else {
            $upload_errors[] = "Formato de banner no permitido (solo JPG, PNG).";
        }
    }

    if (isset($_FILES['gallery_images'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM business_gallery WHERE business_username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_image_count = $row['total'];

        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            if ($current_image_count >= 6) break;

            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024;

                if (in_array($_FILES['gallery_images']['type'][$key], $allowed_types)) {
                    if ($_FILES['gallery_images']['size'][$key] <= $max_size) {
                        $extension = pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION);
                        $filename = 'gallery_' . $username . '_' . time() . '_' . $key . '.' . $extension;
                        $target = 'uploads/' . $filename;

                        if (move_uploaded_file($tmp_name, $target)) {
                            $stmt = $conn->prepare("INSERT INTO business_gallery (business_username, image_path) VALUES (?, ?)");
                            $stmt->bind_param("ss", $username, $filename);
                            $stmt->execute();
                            $current_image_count++;
                        }
                    }
                }
            }
        }
    }

    if (isset($_POST['business_name'])) {
        $business_name = $_POST['business_name'];
        $stmt = $conn->prepare("UPDATE businesses SET business_name = ? WHERE username = ?");
        $stmt->bind_param("ss", $business_name, $username);
        $stmt->execute();
    }

    if (isset($_POST['address'])) {
        $address = $_POST['address'];
        $stmt = $conn->prepare("UPDATE businesses SET address = ? WHERE username = ?");
        $stmt->bind_param("ss", $address, $username);
        $stmt->execute();
    }

    if (isset($_POST['phone'])) {
        $phone = $_POST['phone'];
        $stmt = $conn->prepare("UPDATE businesses SET phone = ? WHERE username = ?");
        $stmt->bind_param("ss", $phone, $username);
        $stmt->execute();
    }

    if (isset($_POST['tipo_negocio'])) {
        $tipo_negocio = $_POST['tipo_negocio'];
        $stmt = $conn->prepare("UPDATE businesses SET tipo_negocio = ? WHERE username = ?");
        $stmt->bind_param("ss", $tipo_negocio, $username);
        $stmt->execute();
    }

    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $stmt = $conn->prepare("UPDATE businesses SET description = ? WHERE username = ?");
        $stmt->bind_param("ss", $description, $username);
        $stmt->execute();
    }

    if (isset($_POST['latitude'])) {
        $latitude = $_POST['latitude'];
        $stmt = $conn->prepare("UPDATE businesses SET latitude = ? WHERE username = ?");
        $stmt->bind_param("ds", $latitude, $username);
        $stmt->execute();
    }

    if (isset($_POST['longitude'])) {
        $longitude = $_POST['longitude'];
        $stmt = $conn->prepare("UPDATE businesses SET longitude = ? WHERE username = ?");
        $stmt->bind_param("ds", $longitude, $username);
        $stmt->execute();
    }
}

$show_review_alert = false;
$review_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (isset($_SESSION['username'])) {
        $current_user = $_SESSION['username'];
        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reviews WHERE business_name = ? AND username = ?");
        $stmt->bind_param("ss", $business_name, $current_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $show_review_alert = true;
            $review_error = "Ya has realizado una reseña para este negocio.";
        } else {
            if (empty($_POST['rating']) || empty(trim($_POST['review_text']))) {
                $review_error = "Por favor completa todos los campos requeridos.";
            } else {
                $rating = intval($_POST['rating']);
                $review_text = trim($_POST['review_text']);
                $image_path = '';
                
                if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $max_size = 5 * 1024 * 1024;
                    
                    if (in_array($_FILES['review_image']['type'], $allowed_types) && $_FILES['review_image']['size'] <= $max_size) {
                        $extension = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
                        $filename = 'review_' . $current_user . '_' . time() . '.' . $extension;
                        $target = 'uploads/' . $filename;
                        
                        if (move_uploaded_file($_FILES['review_image']['tmp_name'], $target)) {
                            $image_path = $filename;
                        }
                    }
                }
                
                $stmt = $conn->prepare("INSERT INTO reviews (business_name, username, review_text, rating, image_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $business_name, $current_user, $review_text, $rating, $image_path);
                
                if ($stmt->execute()) {
                    header("Location: ".$_SERVER['PHP_SELF']."?username=".urlencode($username));
                    exit();
                } else {
                    $review_error = "Error al guardar la reseña. Por favor intenta nuevamente.";
                }
            }
        }
    }
}

$reviews = [];
$average_rating = 0;

// Consulta modificada para obtener también la foto de perfil del usuario
$stmt = $conn->prepare("SELECT r.username, r.review_text, r.rating, r.created_at, r.image_path, u.profile_pic 
                       FROM reviews r 
                       JOIN users u ON r.username = u.name 
                       WHERE r.business_name = ? 
                       ORDER BY r.created_at DESC");
$stmt->bind_param("s", $business_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $total_rating = 0;
    $review_count = 0;

    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
        $total_rating += $row['rating'];
        $review_count++;
    }

    $average_rating = $total_rating / $review_count;
}

$user_has_reviewed = false;
if (isset($_SESSION['username'])) {
    $current_user = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reviews WHERE business_name = ? AND username = ?");
    $stmt->bind_param("ss", $business_name, $current_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_has_reviewed = ($row['count'] > 0);
}

$gallery_images = [];
$stmt = $conn->prepare("SELECT id, image_path FROM business_gallery WHERE business_username = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gallery_images[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($business_name) ?> - AsturRed</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            background-color: #f5f8fa;
        }
        .profile-container {
            max-width: 800px;
            margin: 20px auto 20px;
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
            height: 200px;
            object-fit: cover;
            background-color: #e1e8ed;
            background-image: url('uploads/default-banner.jpg');
            background-size: cover;
            background-position: center;
        }
        .profile-pic-container {
            position: absolute;
            bottom: -50px;
            left: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }
        .profile-info {
            padding: 80px 20px 20px;
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
            color: black;
        }
        .review p {
            margin: 5px 0;
            font-size: 0.9em;
            color: black;
        }
        .review small {
            color: gray;
        }
        .stars {
            color: gold;
            font-size: 0.9em;
        }
        .gallery-container {
            padding: 20px;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            aspect-ratio: 1 / 1;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        .gallery-item .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gallery-item:hover .delete-btn {
            opacity: 1;
        }
        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .star-rating {
            display: flex;
            gap: 5px;
        }
        .star-rating .bi-star-fill {
            color: gold;
        }
        .star-rating .bi-star {
            color: #ddd;
        }
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin-top: 20px;
            z-index: 0;
        }
        .leaflet-container {
            background: #f8f9fa;
        }
        .map-container {
            padding: 20px;
        }
        .map-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        .approximate-location {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            border-left: 4px solid #ffc107;
        }
        .alert-review {
            margin-top: 15px;
        }
        .location-controls {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .editing-mode {
            background-color: #e7f5ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid #4dabf7;
        }
        
        /* Estilos para la foto de perfil en las reseñas */
        .review-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        .review-user-avatar:hover {
            transform: scale(1.1);
        }
        .review-user-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        /* Loading Spinner Styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        .spinner {
            width: 70px;
            height: 70px;
            position: relative;
        }
        
        .spinner .dot {
            position: absolute;
            width: 12px;
            height: 12px;
            background-color: #2e8b57;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
        }
        
        .spinner .dot:nth-child(1) {
            top: 0;
            left: 29px;
            animation-delay: 0s;
        }
        
        .spinner .dot:nth-child(2) {
            top: 6px;
            left: 50px;
            animation-delay: 0.1s;
        }
        
        .spinner .dot:nth-child(3) {
            top: 20px;
            left: 58px;
            animation-delay: 0.2s;
        }
        
        .spinner .dot:nth-child(4) {
            top: 38px;
            left: 50px;
            animation-delay: 0.3s;
        }
        
        .spinner .dot:nth-child(5) {
            top: 58px;
            left: 29px;
            animation-delay: 0.4s;
        }
        
        .spinner .dot:nth-child(6) {
            top: 50px;
            left: 8px;
            animation-delay: 0.5s;
        }
        
        .spinner .dot:nth-child(7) {
            top: 38px;
            left: 0;
            animation-delay: 0.6s;
        }
        
        .spinner .dot:nth-child(8) {
            top: 20px;
            left: 6px;
            animation-delay: 0.7s;
        }
        
        @keyframes spin {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(0.3);
                opacity: 0.5;
            }
        }
        
        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            color: #2e8b57;
            font-weight: bold;
            text-align: center;
        }
        
        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .fade-out {
            opacity: 0;
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            .profile-pic {
                width: 80px;
                height: 80px;
            }
            .profile-pic-container {
                bottom: -40px;
            }
        }
        @media (max-width: 576px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <div class="loading-text">Cargando AsturRed...</div>
        </div>
    </div>

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
                            <li class="nav-item"><a class="nav-link" href="MiNegocio.php">Mi Negocio</a></li>
                        <?php elseif (isset($_SESSION['business_username']) && $_SESSION['role'] === 'ayuntamiento'): ?>
                            <li class="nav-item"><a class="nav-link" href="crear_evento.php">Crear Evento</a></li>
                        <?php elseif (isset($_SESSION['username'])): ?>
                            <li class="nav-item"><a class="nav-link" href="miPerfil.php">Mi Perfil</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="loginform.php">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <img src="uploads/<?= htmlspecialchars($banner_pic) ?>" alt="Foto de encabezado" class="cover-photo">
            <div class="profile-pic-container">
                <img src="uploads/<?= htmlspecialchars($profile_pic) ?>" alt="Foto de perfil" class="profile-pic">
            </div>
        </div>

        <div class="profile-info">
            <h1><?= htmlspecialchars($business_name) ?></h1>
            <div class="rating-container">
                <p><strong><?= count($reviews) ?> Reseñas</strong></p>
                <p class="stars">
                    <?= str_repeat('★', round($average_rating)) . str_repeat('☆', 5 - round($average_rating)) ?>
                    <span class="text-muted">(<?= number_format($average_rating, 1) ?> / 5)</span>
                </p>
            </div>
            <p><?= htmlspecialchars($description) ?></p>
            <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($address) ?></p>
            <p><i class="bi bi-telephone"></i> <?= htmlspecialchars($phone) ?></p>
            <p><i class="bi bi-shop"></i> <?= htmlspecialchars($tipo_negocio) ?></p>
            <?php if ($is_owner): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil"></i> Editar perfil
                </button>
            <?php endif; ?>
        </div>

        <div class="map-container p-3">
            <h3>Ubicación</h3>
            <div id="map" class="map-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando mapa...</span>
                </div>
            </div>
            <div id="locationMessage" class="approximate-location" style="display: none;"></div>
            <div id="editingModeMessage" class="editing-mode" style="display: none;">
                <i class="bi bi-info-circle"></i> Modo edición: Haz clic en el mapa para establecer la nueva ubicación
            </div>
            <?php if ($is_owner): ?>
                <div class="location-controls">
                    <button class="btn btn-primary" id="updateLocationBtn">
                        <i class="bi bi-geo-alt"></i> Editar Ubicación
                    </button>
                    <form method="POST" id="coordsForm" style="display: none;">
                        <input type="hidden" name="latitude" id="inputLatitude">
                        <input type="hidden" name="longitude" id="inputLongitude">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Guardar Ubicación
                        </button>
                        <button type="button" class="btn btn-secondary" id="cancelUpdateBtn">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="gallery-container mt-4 p-3">
            <h3>Galería de imágenes</h3>
            <?php if (!empty($gallery_images)): ?>
                <div class="gallery-grid">
                    <?php foreach ($gallery_images as $image): ?>
                        <div class="gallery-item">
                            <img src="uploads/<?= htmlspecialchars($image['image_path']) ?>" alt="Imagen de galería" class="img-fluid">
                            <?php if ($is_owner): ?>
                                <form method="POST" action="delete_image.php" class="delete-btn">
                                    <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay imágenes en la galería.</p>
            <?php endif; ?>
            <?php if ($is_owner && count($gallery_images) < 6): ?>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-upload"></i> Subir imágenes
                </button>
            <?php endif; ?>
        </div>

        <?php if (!$is_owner): ?>
            <div class="review-container">
                <h3>Dejar una reseña</h3>
                <?php if (isset($_SESSION['username'])): ?>
                    <?php if (!empty($review_error) && !$show_review_alert): ?>
                        <div class="alert alert-danger alert-review"><?= htmlspecialchars($review_error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="submit_review" value="1">
                        <input type="hidden" name="business_name" value="<?= htmlspecialchars($business_name) ?>">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Valoración</label>
                            <div class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star" data-rating="<?= $i ?>" style="cursor: pointer;"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                        </div>
                        <div class="mb-3">
                            <label for="review_text" class="form-label">Reseña</label>
                            <textarea class="form-control" name="review_text" id="review_text" rows="3" required><?= isset($_POST['review_text']) ? htmlspecialchars($_POST['review_text']) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="review_image" class="form-label">Imagen (opcional)</label>
                            <input type="file" class="form-control" name="review_image" id="review_image" accept="image/jpeg, image/png, image/gif">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar reseña</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginAlertModal">
                        Dejar una reseña
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="review-container">
            <h3>Reseñas</h3>
            <?php if (empty($reviews)): ?>
                <p>No hay reseñas todavía.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <div class="review-user-container">
                            <a href="miPerfil.php?user=<?= urlencode($review['username']) ?>">
                                <img src="uploads/<?= htmlspecialchars($review['profile_pic'] ?? 'default-profile.jpg') ?>" 
                                     alt="Foto de perfil de <?= htmlspecialchars($review['username']) ?>" 
                                     class="review-user-avatar">
                            </a>
                            <h3><?= htmlspecialchars($review['username']) ?></h3>
                        </div>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $review['rating']): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php else: ?>
                                    <i class="bi bi-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars($review['review_text']) ?></p>
                        <?php if (!empty($review['image_path'])): ?>
                            <img src="uploads/<?= htmlspecialchars($review['image_path']) ?>" alt="Imagen de reseña" class="img-fluid rounded mt-2" style="max-width: 200px;">
                            <p><br></p>
                        <?php endif; ?>
                        <small><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($is_owner): ?>
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
                                <label for="business_name" class="form-label">Nombre del negocio</label>
                                <input type="text" class="form-control" name="business_name" value="<?= htmlspecialchars($business_name) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tipo_negocio" class="form-label">Tipo de negocio</label>
                                <input type="text" class="form-control" name="tipo_negocio" value="<?= htmlspecialchars($tipo_negocio) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Cambiar foto de perfil</label>
                                <input type="file" class="form-control" name="profile_pic" id="profile_pic" accept="image/jpeg, image/png, image/gif">
                            </div>
                            <div class="mb-3">
                                <label for="banner_pic" class="form-label">Cambiar banner</label>
                                <input type="file" class="form-control" name="banner_pic" id="banner_pic" accept="image/jpeg, image/png">
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Subir imágenes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="gallery_images[]" multiple accept="image/jpeg, image/png, image/gif" class="form-control">
                            <button type="submit" class="btn btn-primary mt-2">Subir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal fade" id="loginAlertModal" tabindex="-1" aria-labelledby="loginAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginAlertModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Debes iniciar sesión para dejar una reseña.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="loginform.php" class="btn btn-primary">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reviewAlertModal" tabindex="-1" aria-labelledby="reviewAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewAlertModalLabel">Reseña existente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Ya has realizado una reseña para este negocio. No puedes enviar más de una reseña por negocio.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar modal de alerta de reseña si es necesario
        <?php if ($show_review_alert): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var reviewAlertModal = new bootstrap.Modal(document.getElementById('reviewAlertModal'));
                reviewAlertModal.show();
            });
        <?php endif; ?>

        // Lógica para las estrellas de valoración
        const stars = document.querySelectorAll('.star-rating .bi-star');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating').value = rating;

                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                    } else {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    }
                });
            });
        });

        // Inicializar el mapa
        let map;
        let marker;
        let isEditing = false;
        const DEFAULT_ZOOM = 17;

        async function initMap() {
            const defaultLat = 43.361914;
            const defaultLng = -5.849388;
            
            let businessCoords = null;
            const businessAddress = "<?= addslashes($address) ?>";
            const locationMessage = document.getElementById('locationMessage');
            
            if (businessAddress) {
                try {
                    businessCoords = await geocodeAddress(businessAddress);
                    
                    if (!businessCoords) {
                        const simplifiedAddress = simplifyAddress(businessAddress);
                        if (simplifiedAddress !== businessAddress) {
                            businessCoords = await geocodeAddress(simplifiedAddress);
                            if (businessCoords) {
                                locationMessage.textContent = "Ubicación aproximada (se ha simplificado la dirección para encontrarla)";
                                locationMessage.style.display = 'block';
                            }
                        }
                    }
                    
                    if (!businessCoords) {
                        const town = extractTown(businessAddress);
                        if (town) {
                            businessCoords = await geocodeAddress(town + ', Asturias');
                            if (businessCoords) {
                                locationMessage.textContent = "Ubicación aproximada (mostrando el centro de la localidad)";
                                locationMessage.style.display = 'block';
                            }
                        }
                    }
                } catch (error) {
                    console.error("Error en geocodificación:", error);
                }
            }
            
            const businessLat = <?= $latitude ? json_encode($latitude) : 'null' ?> || (businessCoords?.lat ?? null);
            const businessLng = <?= $longitude ? json_encode($longitude) : 'null' ?> || (businessCoords?.lng ?? null);
            
            const initialLat = businessLat || defaultLat;
            const initialLng = businessLng || defaultLng;
            
            map = L.map('map').setView([initialLat, initialLng], DEFAULT_ZOOM);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            if (businessLat && businessLng) {
                marker = L.marker([businessLat, businessLng], {
                    title: "<?= htmlspecialchars($business_name) ?>",
                    draggable: false
                }).addTo(map)
                .bindPopup(`<b><?= htmlspecialchars($business_name) ?></b><br><?= htmlspecialchars($address) ?>`);
                
                map.setView([businessLat, businessLng], DEFAULT_ZOOM);
            } else if (businessAddress) {
                L.popup()
                    .setLatLng([initialLat, initialLng])
                    .setContent(`No se pudo encontrar la ubicación exacta de:<br><b><?= htmlspecialchars($address) ?></b>`)
                    .openOn(map);
                locationMessage.textContent = "No se pudo encontrar la ubicación exacta. Por favor, actualiza la ubicación manualmente.";
                locationMessage.style.display = 'block';
            }
            
            setupLocationUpdate();
        }
        
        function simplifyAddress(address) {
            return address.replace(/\d{5}/g, '')
                         .replace(/(TC|Ctra|Carretera)\s*[-0-9]+,?/i, '')
                         .replace(/(\d+)(?=\s)/g, '')
                         .replace(/\s+/g, ' ')
                         .trim();
        }
        
        function extractTown(address) {
            const match = address.match(/(\d{5})\s*([^,]+),?\s*Asturias/i);
            if (match && match[2]) {
                return match[2].trim();
            }
            return null;
        }
        
        async function geocodeAddress(address) {
            if (!address) return null;
            
            let response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&countrycodes=es`);
            let data = await response.json();
            
            if (data && data.length > 0) {
                return {
                    lat: parseFloat(data[0].lat),
                    lng: parseFloat(data[0].lon),
                    display_name: data[0].display_name
                };
            }
            
            if (address.toLowerCase().endsWith(', asturias')) {
                const newAddress = address.substring(0, address.length - 10).trim();
                response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(newAddress)}&limit=1&countrycodes=es`);
                data = await response.json();
                
                if (data && data.length > 0) {
                    return {
                        lat: parseFloat(data[0].lat),
                        lng: parseFloat(data[0].lon),
                        display_name: data[0].display_name
                    };
                }
            }
            
            return null;
        }
        
        function setupLocationUpdate() {
            const updateBtn = document.getElementById('updateLocationBtn');
            const cancelBtn = document.getElementById('cancelUpdateBtn');
            const coordsForm = document.getElementById('coordsForm');
            const editingModeMessage = document.getElementById('editingModeMessage');
            
            if (!updateBtn) return;
            
            updateBtn.addEventListener('click', function() {
                isEditing = true;
                updateBtn.style.display = 'none';
                coordsForm.style.display = 'block';
                editingModeMessage.style.display = 'block';
                
                // Si no hay marcador, crear uno nuevo
                if (!marker) {
                    const center = map.getCenter();
                    marker = L.marker([center.lat, center.lng], {
                        draggable: true
                    }).addTo(map);
                } else {
                    // Hacer el marcador existente arrastrable
                    marker.setLatLng(marker.getLatLng());
                    marker.draggable = true;
                    marker.update();
                }
                
                // Actualizar las coordenadas ocultas con la posición actual del marcador
                updateHiddenCoords();
                
                // Permitir cambiar la ubicación haciendo clic en el mapa
                map.on('click', onMapClick);
                
                // Actualizar coordenadas cuando se arrastra el marcador
                marker.on('dragend', updateHiddenCoords);
            });
            
            cancelBtn.addEventListener('click', function() {
                resetLocationUpdate();
            });
            
            coordsForm.addEventListener('submit', function() {
                isEditing = false;
            });
        }
        
        function onMapClick(e) {
            if (!isEditing) return;
            
            const clickedLatLng = e.latlng;
            
            // Si no hay marcador, crear uno nuevo
            if (!marker) {
                marker = L.marker(clickedLatLng, {
                    draggable: true
                }).addTo(map);
            } else {
                // Mover el marcador existente a la nueva ubicación
                marker.setLatLng(clickedLatLng);
            }
            
            // Actualizar las coordenadas ocultas
            updateHiddenCoords();
            
            // Centrar el mapa en la nueva ubicación
            map.setView(clickedLatLng);
        }
        
        function updateHiddenCoords() {
            if (!marker) return;
            
            const position = marker.getLatLng();
            document.getElementById('inputLatitude').value = position.lat;
            document.getElementById('inputLongitude').value = position.lng;
        }
        
        function resetLocationUpdate() {
            isEditing = false;
            document.getElementById('updateLocationBtn').style.display = 'block';
            document.getElementById('coordsForm').style.display = 'none';
            document.getElementById('editingModeMessage').style.display = 'none';
            
            // Quitar el evento de clic del mapa
            map.off('click', onMapClick);
            
            if (marker) {
                // Hacer el marcador no arrastrable
                marker.draggable = false;
                marker.update();
            }
        }
        
        // Mostrar el spinner mientras la página se carga
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar el spinner cuando todo esté cargado
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const loadingOverlay = document.getElementById('loadingOverlay');
                    loadingOverlay.classList.add('fade-out');
                    
                    // Eliminar el spinner después de la animación
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                    }, 500); // Tiempo igual a la duración de la transición CSS
                }, 300); // Pequeño retraso para asegurar que todo está listo
            });
            
            // Ocultar el spinner si la carga tarda demasiado (fallback)
            setTimeout(function() {
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay.style.display !== 'none') {
                    loadingOverlay.classList.add('fade-out');
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                    }, 500);
                }
            }, 5000); // 5 segundos como máximo
            
            // Inicializar el mapa después de que todo esté cargado
            initMap();
        });
         window.addEventListener('beforeunload', function() {
            var loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        });
    </script>
</body>
</html>