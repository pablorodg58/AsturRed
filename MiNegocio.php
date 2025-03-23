<?php
session_start();

include "conexion.php";  // Incluir conexión a la base de datos

// Obtener el nombre de usuario del negocio desde la URL
$username = $_GET['username'] ?? $_SESSION['business_username'];

// Verificar si el usuario actual es el propietario del negocio
$is_owner = ($username === ($_SESSION['business_username'] ?? ''));

// Obtener datos del negocio
$profile_pic = 'default-profile.jpg';
$banner_pic = 'default-banner.jpg';
$business_name = '';
$address = '';
$phone = '';
$description = '';
$tipo_negocio = ''; // Añadir esta variable

$stmt = $conn->prepare("SELECT business_name, address, phone, description, profile_pic, banner_pic, tipo_negocio FROM businesses WHERE username = ?");
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
    $tipo_negocio = $row['tipo_negocio'] ?? ''; // Obtener el tipo de negocio
}

// Procesar subida de imágenes y actualización de datos del negocio (solo si es el propietario)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_owner) {
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

                // Eliminar la imagen anterior si existe
                if ($profile_pic !== 'default-profile.jpg' && file_exists('uploads/' . $profile_pic)) {
                    unlink('uploads/' . $profile_pic);
                }

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    // Actualizar base de datos
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

    // Subir imágenes de la galería
    if (isset($_FILES['gallery_images'])) {
        // Obtener el número actual de imágenes en la galería
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM business_gallery WHERE business_username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_image_count = $row['total'];

        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            if ($current_image_count >= 6) {
                break; // No permitir más de 6 imágenes
            }

            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024; // 5MB

                if (in_array($_FILES['gallery_images']['type'][$key], $allowed_types)) {
                    if ($_FILES['gallery_images']['size'][$key] <= $max_size) {
                        $extension = pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION);
                        $filename = 'gallery_' . $username . '_' . time() . '_' . $key . '.' . $extension;
                        $target = 'uploads/' . $filename;

                        if (move_uploaded_file($tmp_name, $target)) {
                            // Guardar en la base de datos
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

    // Actualizar datos del negocio
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
}

// Obtener reseñas y valoración promedio
$reviews = [];
$average_rating = 0;

$stmt = $conn->prepare("SELECT username, review_text, rating, created_at, image_path FROM reviews WHERE business_name = ? ORDER BY created_at DESC");
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

// Obtener imágenes de la galería
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
            color: black; /* Color negro para el nombre de usuario */
        }
        .review p {
            margin: 5px 0;
            font-size: 0.9em;
            color: black; /* Color negro para el texto de la reseña */
        }
        .review small {
            color: gray; /* Color gris para la fecha */
        }
        .stars {
            color: gold;
            font-size: 0.9em;
        }
        .gallery-container {
            padding: 20px;
        }
        .gallery-container img {
            width: 100%;
            border-radius: 10px;
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

        <!-- Información del negocio -->
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
            <p><i class="bi bi-shop"></i> <?= htmlspecialchars($tipo_negocio) ?></p> <!-- Mostrar el tipo de negocio -->
            <?php if ($is_owner): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil"></i> Editar perfil
                </button>
            <?php endif; ?>
        </div>

        <!-- Galería de imágenes -->
        <div class="gallery-container mt-4 p-3">
            <h3>Galería de imágenes</h3>
            <div class="row">
                <?php foreach ($gallery_images as $image): ?>
                    <div class="col-md-4 mb-3">
                        <img src="uploads/<?= htmlspecialchars($image['image_path']) ?>" alt="Imagen de galería" class="img-fluid rounded">
                        <?php if ($is_owner): ?>
                            <form method="POST" action="delete_image.php" style="display:inline;">
                                <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm mt-2">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($is_owner && count($gallery_images) < 6): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-upload"></i> Subir imágenes
                </button>
            <?php endif; ?>
        </div>

        <!-- Formulario para dejar una reseña -->
        <?php if (!$is_owner): ?>
            <div class="review-container">
                <h3>Dejar una reseña</h3>
                <?php if (isset($_SESSION['username'])): ?>
                    <form method="POST" action="submit_review.php" enctype="multipart/form-data">
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
                            <textarea class="form-control" name="review_text" id="review_text" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="review_image" class="form-label">Subir imagen (opcional)</label>
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

        <!-- Reseñas del negocio -->
        <div class="review-container">
            <h3>Reseñas</h3>
            <?php if (empty($reviews)): ?>
                <p>No hay reseñas todavía.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <h3><?= htmlspecialchars($review['username']) ?></h3>
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
                            <img src="uploads/<?= htmlspecialchars($review['image_path']) ?>" alt="Imagen de reseña" class="img-fluid rounded mt-2" style="max-width: 200px;"><p><br></p>
                        <?php endif; ?>
                        <small><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para editar perfil (solo para el propietario) -->
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

        <!-- Modal para subir imágenes (solo para el propietario) -->
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

    <!-- Modal de alerta para iniciar sesión -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Lógica para las estrellas de valoración
    const stars = document.querySelectorAll('.star-rating .bi-star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            document.getElementById('rating').value = rating;

            // Cambiar el color de las estrellas
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

    // Manejar el envío del formulario de reseñas
    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault();  // Evitar el envío tradicional del formulario

        const formData = new FormData(this);

        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);  // Mostrar mensaje de éxito
                    window.location.reload();  // Recargar la página
                } else {
                    alert(data.message);  // Mostrar mensaje de error
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al enviar la reseña.');
            });
    });
</script>
</body>
</html>