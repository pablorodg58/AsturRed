<?php
session_start();

// Redirigir al formulario de inicio de sesión si no hay sesión activa
if (!isset($_SESSION['username']) && !isset($_SESSION['admin_logged_in']) && !isset($_SESSION['business_username'])) {
    // No redirigimos, simplemente mostramos "Iniciar Sesión"
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsturRed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding-top: 56px; /* Añadido para compensar el navbar fijo */
        }
        .carousel-item img {
            width: 100%;
            height: 600px;
            object-fit: cover;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        .carousel-caption h5 {
            font-size: 3rem;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.69);
        }
        .carousel-caption p {
            font-size: 1.2rem;
            color: white;
        }
        .section-title {
            font-size: 2.5rem;
            margin-top: 30px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }
        .section-text {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .btn-primary {
            background-color: #2e8b57;
            border: none;
            width: 150px;
        }
        .btn-primary:hover {
            background-color: #2196F3;
            width: 300px;
            transition: all 0.5s;
        }
        .btn-primary:not(:hover) {
            transition: all 0.5s;
        }
        .business-card img {
            height: 200px;
            object-fit: cover;
        }
        .business-card .card-body {
            text-align: center;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .event-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .event-card .card-body {
            padding: 20px;
        }
        .event-card .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #004955;
        }
        .event-card .card-text {
            color: #666;
        }
        .event-card .btn-group {
            margin-top: 10px;
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
    
    /* Estilos para el navbar fijo */
    .navbar {
        transition: all 0.3s ease;
    }
    
    .navbar.fixed-top {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
    <!-- NavBar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <!-- Logo en el navbar-brand -->
            <a class="navbar-brand" href="#">
                <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo" style="height: 50px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
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

    <!-- Header -->
    <header class="bg-dark text-white text-center py-3">
        <h2 class="display-4">Bienvenidos a AsturRed</h2>
    </header>

    <!-- Carrusel -->
    <div id="carouselAsturRed" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/tapia-de-casariego_260.jpg" class="d-block w-100" alt="Tapia de Casariego">
                <a href="Tapia.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Tapia de Casariego</h5>
                    <p>Donde el mar y la tradición se abrazan</p>
                </div></a>
            </div>
            <div class="carousel-item">
                <img src="img/Taramundi_molino.jpg" class="d-block w-100" alt="Taramundi">
                <a href="Taramundi.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Taramundi</h5>
                    <p>Donde la naturaleza y la tradición se encuentran</p>
                </div></a>
            </div>
            <div class="carousel-item">
                <img src="img/Castropol.jpg" class="d-block w-100" alt="Castropol">
                <a href="Castropol.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Castropol</h5>
                    <p>Un balcón privilegiado sobre la ría del Eo</p>
                </div></a>
            </div>
            <div class="carousel-item">
                <img src="img/vegadeoIndex.jpg" class="d-block w-100" alt="Castropol">
                <a href="Vegadeo.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Vegadeo</h5>
                    <p>Puerta de entrada a la Asturias más auténtica</p>
                </div></a>
            </div>
            <div class="carousel-item">
                <img src="img/navia.jpg" class="d-block w-100" alt="Castropol">
                <a href="Navia.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Navia</h5>
                    <p>Donde el Cantábrico besa la tradición</p>
                </div></a>
            </div>
            <div class="carousel-item">
                <img src="img/PuertoVega.jpg" class="d-block w-100" alt="Castropol">
                <a href="PuertoVega.php"><div class="carousel-caption d-none d-md-block">
                    <h5>Puerto de Vega</h5>
                    <p>El encanto de un pueblo pesquero</p>
                </div></a>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselAsturRed" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselAsturRed" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Sección de contenido dinámico -->
    <section class="container my-5 text-center" id="pueblos">
        <h2 class="section-title">¿Qué es AsturRed?</h2>
        <p class="section-text">AsturRed es una plataforma única diseñada para conectar a turistas con los negocios locales de Asturias. Aquí podrás descubrir los pueblos más encantadores, leer reseñas de otros viajeros, encontrar eventos y promociones exclusivas, y disfrutar de la cultura asturiana desde la comodidad de tu móvil o ordenador.</p>
        <div class="d-flex justify-content-center"> 
            <div id="carouselServicios" class="carousel slide my-5" data-bs-ride="carousel" style="max-width: 800px; width: 100%;">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/fabada.jpg" class="d-block w-100" alt="Gastronomía Asturiana" style="height: 300px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Experiencias Gastronómicas</h5>
                            <p>Descubre sidrerías tradicionales y productos locales</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/rutaTaramundi.jpg" class="d-block w-100" alt="Turismo Aventura" style="height: 300px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Aventuras en la Naturaleza</h5>
                            <p>Rutas de senderismo, kayak y paisajes espectaculares</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/playaAsturias.jpg" class="d-block w-100" alt="Alojamientos Rurales" style="height: 300px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Playas de Asturias</h5>
                            <p>El mejor lugar para un día de verano</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicios" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselServicios" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>        
        <?php if (isset($_SESSION['username']) || isset($_SESSION['admin_logged_in']) || isset($_SESSION['business_username'])): ?>
            <!-- Barra de búsqueda (solo para usuarios autenticados) -->
            <form action="search_results.php" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search_query" placeholder="Buscar en AsturRed..." aria-label="Buscar en AsturRed">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        <?php else: ?>
            <!-- Botón de inicio de sesión (solo para usuarios no autenticados) -->
            <a href="loginform.php" class="btn btn-primary">Iniciar Sesión</a>
        <?php endif; ?>
    </section>

    <!-- Sección de negocios -->
    <section class="container my-5" id="negocios">
        <h2 class="section-title">Lugares Destacados</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card business-card">
                    <img src="img/Trazado-Kartodromo-de-Tapia.webp" class="card-img-top" alt="Restaurante El Sabor">
                    <div class="card-body">
                        <h5 class="card-title">Kartodromo de Tapia</h5>
                        <p class="card-text">Tapia: ¡Conviértete en el piloto más rápido de las pistas del norte!</p>
                        <a href="KartodromoTapia.php" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card business-card">
                    <img src="img/museoNavajas.jpg" class="card-img-top" alt="Hotel Costa Verde">
                    <div class="card-body">
                        <h5 class="card-title">Museo de la Cuchillería</h5>
                        <p class="card-text">Descubre la historia de la cuchillería en Taramundi: un legado ancestral.</p>
                        <a href="MuseoCuchilleria.php" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card business-card">
                    <img src="img/castro-coana.jpg" class="card-img-top" alt="Artesanías del Norte">
                    <div class="card-body">
                        <h5 class="card-title">Castro de Coaña</h5>
                        <p class="card-text">Vive la auténtica historia celta en Coaña: ¡visita los castros!</p>
                        <a href="CastrosCoaña.php" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de eventos -->
    <section class="container my-5" id="eventos">
        <h2 class="section-title">Eventos Próximos</h2>
        <?php
        include "conexion.php";

        // Eliminar eventos pasados
        $currentDateTime = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("DELETE FROM events WHERE date < ?");
        $stmt->bind_param("s", $currentDateTime);
        $stmt->execute();

        // Obtener eventos futuros (limitamos a 3 eventos)
        $sql = "SELECT * FROM events WHERE date >= ? ORDER BY date ASC LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $currentDateTime);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card event-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                        <p class="card-text"><small class="text-muted">Fecha: <?= htmlspecialchars($row['date']) ?></small></p>
                        <p class="card-text"><small class="text-muted">Ubicación: <?= htmlspecialchars($row['location']) ?></small></p>
                        <p class="card-text"><small class="text-muted">Publicado por: <?= htmlspecialchars($row['created_by']) ?></small></p>
                        
                        <!-- Botones de editar y eliminar (solo para el ayuntamiento que creó el evento) -->
                        <?php if (isset($_SESSION['business_username']) && $_SESSION['business_username'] === $row['created_by']): ?>
                            <div class="btn-group">
                                <a href="editar_evento.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_evento.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este evento?')">Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Botón "Ver más eventos" -->
            <?php
            // Verificar si hay más de 3 eventos
            $sqlCount = "SELECT COUNT(*) as total FROM events WHERE date >= ?";
            $stmtCount = $conn->prepare($sqlCount);
            $stmtCount->bind_param("s", $currentDateTime);
            $stmtCount->execute();
            $resultCount = $stmtCount->get_result();
            $rowCount = $resultCount->fetch_assoc();
            if ($rowCount['total'] > 3): ?>
                <div class="text-center mt-4">
                    <a href="eventos.php" class="btn btn-primary">Ver más eventos</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center">No hay eventos próximos.</p>
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
                        <img src="img/escudoTaramundi.png" alt="Escudo Navia" class="img-fluid m-2" style="max-height: 80px;">
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
    <script>
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
    });
    
    // Script para hacer el navbar fijo al desplazarse
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('fixed-top');
            document.body.style.paddingTop = navbar.offsetHeight + 'px';
        } else {
            navbar.classList.remove('fixed-top');
            document.body.style.paddingTop = '0';
        }
    });
    </script>
</body>
</html>