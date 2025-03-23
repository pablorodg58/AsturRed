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
    <meta name="description" content="Descubre la belleza de nuestro pueblo, sus negocios locales, recetas y eventos.">
    <title>Navia</title>
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Estilos CSS (igual que en Tapia.php) */
        .image-banner {
            position: relative;
            text-align: center;
            margin-top: -20px; 
            padding: 0; 
        }
        .image-banner img {
            width: 100%;
            height: auto;
            filter: brightness(0.8); 
            max-height: 570px;
        }
        .image-banner h2 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 36px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .business-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .business-container h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .business-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            text-decoration: none; /* Quitar subrayado de los enlaces */
            color: inherit; /* Heredar el color del texto */
        }

        .business-item:hover {
            background-color: #e9ecef; /* Cambiar el color de fondo al pasar el ratón */
            border-radius: 8px;
        }

        .business-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .business-item p {
            margin: 0;
            font-size: 1rem;
        }

        .image-container {
            max-width: 600px; 
            margin: 0 auto;
            display: flex; 
            justify-content: flex-start; 
            margin-top: 15px; 
        }

        .image-container img {
            width: 10%; 
            height: auto; 
            border-radius: 5px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin-right: 10px;
        }

        .image-container img:last-child {
            margin-right: 0;
        }

        .content {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            opacity: 0;
            overflow: hidden;
            transition: max-height 1s ease, opacity 1s ease;
            display: none;
            text-align: justify; /* Texto justificado */

        }
        
        .content.show {
            display: block; 
            opacity: 1;
        }
        .content p {
            text-align: justify; /* Asegurar que todos los párrafos estén justificados */
        }
        .content img {
            width: 50%; 
            height: auto;
            margin-top: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .section {
            margin-top: 30px;
            text-align: center;
        }

        .section h2 {
            font-size: 24px;
            margin-bottom: 10px;
            
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 10px;
            max-width: 800px;
            margin: 0 auto; 
        }

        .grid-item {
            background-color: #f4f4f4; 
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            text-align: center;
            display: flex; 
            flex-direction: column; 
            align-items: center;
            justify-content: center;
        }

        .grid-item img {
            width: 70%; 
            height: auto; 
            max-height: 150px;
            object-fit: cover; 
            margin-bottom: 10px;
            border-radius: 5px; 
        }

        .toggle-link {
            font-weight: bold;
            font-size: 18px;
            color: #006d6d;
            text-decoration: none;
            position: relative;
            display: inline-block;
            padding-bottom: 5px;
            transition: color 0.3s ease;
        }

        .toggle-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #006d6d;
            transition: width 0.3s ease;
        }

        .toggle-link:hover {
            color: #005757;
        }

        .toggle-link:hover::after {
            width: 100%;
        }
        .btn-primary {
            background-color: #005757;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #004545;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .hidden {
            display: none;
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

    <div class="image-banner">
        <img src="img/navia2.jpg" alt="Navia">
        <h2>Navia</h2>
    </div>

    <div class="container">
        <div class="row">
            <!-- Información del pueblo -->
            <div class="col-md-9">
                <div style="text-align: center; margin-top: 30px;">
                    <a class="toggle-link" onclick="toggleContent()">Ver Información del Pueblo</a>
                </div>
                <div class="content" id="contentSection">
                    <div class="section">
                        <p>Navia, un concejo situado en la costa occidental de Asturias, es un territorio que combina tradición marinera, belleza natural y un rico patrimonio histórico. Su ubicación estratégica, a orillas de la ría de Navia y con vistas al mar Cantábrico, le ha conferido un papel importante a lo largo de los siglos.
<br>
La historia de Navia está marcada por su tradición pesquera y su actividad comercial. Su puerto, protegido por la ría, ha sido históricamente un punto clave para el comercio marítimo y la pesca.
<br>La villa de Navia, capital del concejo, conserva el encanto de su pasado, con casas de indianos de colores vivos y calles estrechas que invitan a pasear. La plaza del Ayuntamiento, con su iglesia parroquial y su palacio de Campos, es el corazón de la villa.

</p>
                        <img src="https://www.cantabricoexperience.com/wp-content/uploads/2015/08/Navia2.jpeg" alt="Vista del pueblo al atardecer">
                        <p><br>Navia es conocida por sus playas, como la playa de Navia, la playa de Frejulfe y la playa de Barayo, declarada Monumento Natural. La ría de Navia, declarada Reserva de la Biosfera, es un lugar ideal para la práctica de deportes náuticos y la observación de aves.




</p>
                        <img src="https://www.turismoasturias.es/documents/39908/43287/PlayaNavia2.jpg/7b4fd68d-bcaa-c916-485e-2331245617bf" alt="Gente disfrutando de un festival en el pueblo">
                        <p><br>Navia también es un destino popular para los amantes de la gastronomía, gracias a sus productos frescos del mar y su cocina tradicional. La villa cuenta con una amplia oferta de restaurantes y sidrerías donde se puede degustar la cocina asturiana.


</p>
                        
                        <div class="image-container">
                            <img src="https://www.turismoasturias.es/documents/39908/c82a1ae3-ac68-9e94-67c2-e738765e1213?t=1674171233871" alt="Playa de Navia">
                            <img src="img/cañonNavia.JPG" alt="Fiestas en Navia">
                        </div>
                    </div>
                </div>

                <!-- Sección adicional -->
                <div class="section">
                    <h2>Conoce Navia</h2>
                    <div class="grid">
                    <div class="grid-item" onclick="window.location.href='parquehistoricoNavia.php';" style="cursor: pointer; text-decoration: none; color: inherit;">
                    <img src="img/ParqueHistoricoNavia.jpg" alt="Parque Historico de Navia">
                            <p>Visita el Parque Histórico del Navia y descubre el legado de nuestros antepasados.</p>
                        </div>
                        <div class="grid-item" onclick="window.location.href='CostaNavia.php';" style="cursor: pointer; text-decoration: none; color: inherit;">
                        <img src="img/faroNavia.avif" alt="Costa de Navia">
                            <p>Visita el Faro de Disanti, un lugar donde el tiempo se detiene frente al mar</p>
                        </div>
                        <div class="grid-item" onclick="window.location.href='AFondoNavia.php';" style="cursor: pointer; text-decoration: none; color: inherit;">
                            <img src="img/CasinoNavia.jpg" alt="Casino de Navia">
                            <p>Un paseo por la historia y la belleza: Ría, puerto y parque en un mismo lugar.</p>
                        </div>
                        <div class="grid-item" onclick="window.location.href='CastrosCoaña.php';" style="cursor: pointer; text-decoration: none; color: inherit;">
                            <img src="img/castro-coana.jpg" alt="Castros de Coaña">
                            <p>Descubre la historia celta en uno de los yacimientos más impresionantes de Asturias</p>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Contenedor de negocios locales -->
     <div class="col-md-3">
                <div class="business-container">
                    <h3>Negocios Locales</h3>
                    <?php
                    // Incluir la conexión a la base de datos
                    include "conexion.php";

                    // Verificar si la conexión fue exitosa
                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }

                    // Consulta SQL para obtener los negocios de Navia, ordenados por número de reseñas
                    $query = "
            SELECT b.id, b.username, b.business_name, b.profile_pic, COUNT(r.id) AS review_count
            FROM businesses b
            LEFT JOIN reviews r ON b.business_name = r.business_name
            WHERE b.location LIKE '%Navia%' AND b.role = 'negocio'
            GROUP BY b.id
            ORDER BY review_count DESC
        ";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        $count = 0;
                        while ($row = $result->fetch_assoc()) {
                            // Mostrar solo los primeros 5 negocios
                            if ($count < 5) {
                                echo '<a href="MiNegocio.php?username=' . urlencode($row['username']) . '" class="business-item">';
                                echo '<img src="uploads/' . htmlspecialchars($row['profile_pic']) . '" alt="' . htmlspecialchars($row['business_name']) . '">';
                                echo '<p>' . htmlspecialchars($row['business_name']) . '</p>';
                                echo '</a>';
                                $count++;
                            }
                        }

                        // Mostrar el botón si hay más de 5 negocios
                        if ($result->num_rows > 5) {
                            echo '<div class="text-center mt-3">';
                            echo '<a href="todos_los_negocios.php?pueblo=Navia" class="btn btn-primary">Ver todos los negocios de Navia</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hay negocios registrados en Navia.</p>';
                    }

                    // Cerrar la conexión
                    $conn->close();
                    ?>
                </div>
            </div>
            </div>
        </div>
    </div>

    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <!-- Escudos de los Ayuntamientos -->
                <div class="col-md-6 text-center mb-4">
                    <h4 class="mb-3">Con el apoyo de:</h4>
                    <div class="d-flex justify-content-center flex-wrap">
                        <img src="img/Escudo_de_Tapia_de_Casariego.gif" alt="Escudo Tapia de Casariego" class="img-fluid m-2" style="max-height: 80px;">
                        <img src="img/escudoNavia.png" alt="Escudo Navia" class="img-fluid m-2" style="max-height: 80px;">
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

    <script>
        function toggleContent() {
            const content = document.getElementById('contentSection');
            content.classList.toggle('show');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>