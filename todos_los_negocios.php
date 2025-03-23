<?php
session_start();

// Incluir la conexión a la base de datos
include "conexion.php";

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el pueblo desde la URL (si se proporciona)
$pueblo = isset($_GET['pueblo']) ? $_GET['pueblo'] : null;

// Consulta SQL para obtener los negocios, ordenados por número de reseñas
if ($pueblo) {
    // Si se especifica un pueblo, filtrar por ese pueblo
    $query = "
        SELECT b.id, b.username, b.business_name, b.profile_pic, b.tipo_negocio, b.description, COUNT(r.id) AS review_count
        FROM businesses b
        LEFT JOIN reviews r ON b.business_name = r.business_name
        WHERE b.location LIKE ? AND b.role = 'negocio'
        GROUP BY b.id
        ORDER BY review_count DESC
    ";
    $stmt = $conn->prepare($query);
    $pueblo_like = "%$pueblo%";
    $stmt->bind_param("s", $pueblo_like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Si no se especifica un pueblo, mostrar todos los negocios
    $query = "
        SELECT b.id, b.username, b.business_name, b.profile_pic, b.tipo_negocio, b.description, COUNT(r.id) AS review_count
        FROM businesses b
        LEFT JOIN reviews r ON b.business_name = r.business_name
        WHERE b.role = 'negocio'
        GROUP BY b.id
        ORDER BY review_count DESC
    ";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Negocios<?php echo $pueblo ? " de $pueblo" : ""; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <style>
        .business-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
            margin-bottom: 20px;
        }
        .business-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .business-card img {
            height: 200px;
            object-fit: cover;
        }
        .business-card .card-body {
            padding: 20px;
        }
        .business-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .business-card .card-text {
            font-size: 0.9rem;
            color: #555;
        }
        .business-card .card-category {
            font-size: 0.9rem;
            color: #006d6d;
            font-weight: bold;
        }
        .btn-ver-negocio {
            background-color: #006d6d;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-ver-negocio:hover {
            background-color: #005757;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
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

    <!-- Contenido Principal -->
    <div class="container mt-5 pt-4">
        <h1 class="text-center mb-4">Todos los Negocios<?php echo $pueblo ? " de $pueblo" : ""; ?></h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card business-card">';
                    echo '<img src="uploads/' . htmlspecialchars($row['profile_pic']) . '" class="card-img-top" alt="' . htmlspecialchars($row['business_name']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['business_name']) . '</h5>';
                    echo '<p class="card-category"><strong>Categoría:</strong> ' . htmlspecialchars($row['tipo_negocio']) . '</p>';
                    echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                    echo '<a href="MiNegocio.php?username=' . urlencode($row['username']) . '" class="btn btn-ver-negocio">Ver Negocio</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No hay negocios registrados<?php echo $pueblo ? " en $pueblo" : ""; ?>.</p>';
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <!-- Escudos de los Ayuntamientos -->
                <div class="col-md-6 text-center mb-4">
                    <h4 class="mb-3">Con el apoyo de:</h4>
                    <div class="d-flex justify-content-center flex-wrap">
                        <img src="img/Escudo_de_Tapia_de_Casariego.gif" alt="Escudo Tapia de Casariego" class="img-fluid m-2" style="max-height: 80px;">
                        <img src="img/escudoNavia.png" alt="Escudo Taramundi" class="img-fluid m-2" style="max-height: 80px;">
                        <img src="img/Escudo_de_Castropol.svg" alt="Escudo Castropol" class="img-fluid m-2" style="max-height: 80px;">
                        <img src="img/Escudo_de_Vegadeo.svg" alt="Escudo Vegadeo" class="img-fluid m-2" style="max-height: 80px;">
                        <img src="img/escudoNavia.png" alt="Escudo Navia" class="img-fluid m-2" style="max-height: 80px;">
                    </div>
                </div>

                <!-- Enlaces útiles -->
                <div class="col-md-3 mb-4">
                    <h4 class="mb-3">Enlaces útiles</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Política de privacidad</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Términos y condiciones</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Preguntas frecuentes</a></li>
                    </ul>
                </div>

                <!-- Contáctanos -->
                <div class="col-md-3 mb-4">
                    <h4 class="mb-3">Contáctanos</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:info@asturred.com" class="text-white text-decoration-none">info@asturred.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:+34985123456" class="text-white text-decoration-none">+34 985 123 456</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="text-white">Calle Asturias, 123, 33700, Asturias</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2"></i>
                            <span class="text-white">Lunes a Viernes: 9:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Derechos de autor -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2025 AsturRed | Todos los derechos reservados</p>
                    <p class="mb-0">Diseñado con <i class="fas fa-heart text-danger"></i> para Asturias</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>