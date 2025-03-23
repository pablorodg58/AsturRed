<?php
session_start();

// Incluir la conexión a la base de datos
include "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username']) && !isset($_SESSION['admin_logged_in']) && (!isset($_SESSION['business_username']) || empty($_SESSION['business_username']))) {
    header("Location: loginform.php");
    exit();
}

// Obtener la consulta de búsqueda
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

if (!empty($search_query)) {
    // Convertir la consulta a minúsculas para hacer la búsqueda insensible a mayúsculas
    $search_query_lower = strtolower($search_query);

    // Lista de pueblos y sus páginas correspondientes
    $pueblos = [
        'tapia' => 'Tapia.php',
        'castropol' => 'Castropol.php',
        'navia' => 'Navia.php',
        'vegadeo' => 'Vegadeo.php',
        'puerto de vega' => 'PuertoVega.php',
        'taramundi' => 'Taramundi.php',
    ];

    // Lista de categorías de negocios
    $categorias = ['restaurante', 'hotel', 'tienda', 'fruteria', 'karting', 'cafeteria', 'museo', 'panadería', 'bar', 'heladería', 'ludoteca', 'hipica'];

    // Verificar si la búsqueda coincide con un pueblo
    if (array_key_exists($search_query_lower, $pueblos)) {
        // Redirigir a la página del pueblo
        header("Location: " . $pueblos[$search_query_lower]);
        exit();
    }

    // Verificar si la búsqueda contiene una categoría y una ubicación
    $search_terms = explode(' ', $search_query_lower);
    $categoria_busqueda = null;
    $ubicacion_busqueda = null;

    foreach ($search_terms as $term) {
        if (in_array($term, $categorias)) {
            $categoria_busqueda = $term;
        }
        if (array_key_exists($term, $pueblos)) {
            $ubicacion_busqueda = $term;
        }
    }

    if ($categoria_busqueda && $ubicacion_busqueda) {
        // Búsqueda por categoría y ubicación
        $sql = "SELECT id, username, business_name AS nombre, description, tipo_negocio AS categoria, location AS ubicacion 
                FROM businesses 
                WHERE tipo_negocio = ? AND location = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $categoria_busqueda, $ubicacion_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();
    } elseif ($categoria_busqueda) {
        // Búsqueda por categoría
        $sql = "SELECT id, username, business_name AS nombre, description, tipo_negocio AS categoria, location AS ubicacion 
                FROM businesses 
                WHERE tipo_negocio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoria_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();
    } elseif ($ubicacion_busqueda) {
        // Búsqueda por ubicación
        $sql = "SELECT id, username, business_name AS nombre, description, tipo_negocio AS categoria, location AS ubicacion 
                FROM businesses 
                WHERE location = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ubicacion_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Búsqueda general (eventos, negocios, etc.)
        $sql = "SELECT id, title AS nombre, description, 'evento' AS categoria, location AS ubicacion, date, created_by, NULL AS username 
                FROM events 
                WHERE title LIKE ? OR description LIKE ? OR location LIKE ?
                UNION
                SELECT id, username, business_name AS nombre, description, tipo_negocio AS categoria, location AS ubicacion, NULL AS date, NULL AS created_by 
                FROM businesses 
                WHERE business_name LIKE ? OR description LIKE ? OR tipo_negocio LIKE ?
                ORDER BY date ASC";
        $stmt = $conn->prepare($sql);
        $search_term = "%$search_query%";
        $stmt->bind_param("ssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else {
    // Si no hay consulta, redirigir a la página principal
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - AsturRed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        
        .event-card, .business-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .event-card:hover, .business-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .event-card .card-body, .business-card .card-body {
            padding: 20px;
        }
        .event-card .card-title, .business-card .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #004955;
        }
        .event-card .card-text, .business-card .card-text {
            color: #666;
        }

    </style>
</head>
<body>
    <!-- NavBar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo" style="height: 50px; width: auto;">
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

    <!-- Contenido principal -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Resultados de Búsqueda para "<?= htmlspecialchars($search_query) ?>"</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php if (isset($row['date'])): ?>
                    <!-- Resultado de evento -->
                    <div class="card event-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                            <p class="card-text"><small class="text-muted">Fecha: <?= htmlspecialchars($row['date']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Publicado por: <?= htmlspecialchars($row['created_by']) ?></small></p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Resultado de negocio -->
                    <div class="card business-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                            <p class="card-text"><small class="text-muted">Categoría: <?= htmlspecialchars($row['categoria']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></small></p>
                            <a href="MiNegocio.php?username=<?= $row['username'] ?>" class="btn btn-primary">Ver Perfil</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No se encontraron resultados para "<?= htmlspecialchars($search_query) ?>".</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row">
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
                <div class="col-md-3 mb-4">
                    <h4 class="mb-3">Enlaces útiles</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Política de privacidad</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Términos y condiciones</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Preguntas frecuentes</a></li>
                    </ul>
                </div>
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
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2025 AsturRed | Todos los derechos reservados</p>
                    <p class="mb-0">Diseñado con <i class="fas fa-heart text-danger"></i> para Asturias</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>