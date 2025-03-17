<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre la belleza de nuestro pueblo, sus negocios locales, recetas y eventos.">
    <title>Vegadeo</title>
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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
        }

        .content.show {
            display: block; 
            opacity: 1;
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pueblos.html">Pueblos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="NegociosLocales.html">Negocios Locales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reseñas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PerfilUsuario.html">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="image-banner">
        <img src="img/vegadeo2.jpg" alt="Vegadeo">
        <h2>Vegadeo</h2>
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
                        <h2>Información del pueblo</h2>
                        <p>Vegadeo es un encantador pueblo asturiano que combina tradición y modernidad. Su historia se remonta a tiempos antiguos, donde la pesca y la agricultura fueron pilares de su economía. Hoy en día, se ha convertido en un destino turístico muy valorado por sus paisajes y su gastronomía.</p>
                        <img src="https://s1.elespanol.com/2024/06/14/quincemil/vivir/escapadas/862924818_244310954_1024x576.jpg" alt="Vista del pueblo al atardecer">
                        <p>Las festividades del pueblo son un reflejo de su rica cultura, con eventos que incluyen ferias, fiestas patronales y actividades para toda la familia. Cada verano, los visitantes son bienvenidos a disfrutar de la hospitalidad de nuestra comunidad.</p>
                        <img src="https://whereisasturias.com/wp-content/uploads/2012/05/rio-mojardin-vegadeo.jpg" alt="Gente disfrutando de un festival en el pueblo">
                        <p>Te invitamos a explorar los hermosos rincones de Vegadeo, desde sus senderos naturales hasta sus plazas históricas, ideales para caminatas y paseos en bicicleta. ¡Ven y descubre lo que tenemos para ofrecerte!</p>
                        
                        <div class="image-container">
                            <img src="https://www.turismoasturias.es/o/adaptive-media/image/10760593/3/89b0e4a0-ffe0-3ecc-2b3a-a19070735b3a?t=1732627871327" alt="Playa de Vegadeo">
                            <img src="https://whereisasturias.com/wp-content/uploads/2012/05/parque-medal-vegadeo.jpg" alt="Fiestas en Vegadeo">
                        </div>
                    </div>
                </div>

                <!-- Sección adicional -->
                <div class="section">
                    <h2>Añadir un subtítulo</h2>
                    <div class="grid">
                        <div class="grid-item">
                            <img src="img/VegadeoKiosko.webp" alt="Mercado local">
                            <p>Explora nuestro mercado local.</p>
                        </div>
                        <div class="grid-item">
                            <img src="img/Senda12puentesVegadeo.jpg" alt="Recetas tradicionales">
                            <p>Prueba nuestras recetas tradicionales.</p>
                        </div>
                        <div class="grid-item">
                            <img src="img/Cristo_de_Paramios.jpg" alt="Eventos en el pueblo">
                            <p>Participa en nuestros eventos anuales.</p>
                        </div>
                        <div class="grid-item">
                            <img src="img/RutaPalacios.jpg" alt="Naturaleza alrededor del pueblo">
                            <p>Disfruta de la naturaleza que rodea nuestra localidad.</p>
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

                    // Obtener los negocios de Vegadeo
                    $query = "SELECT id, username, business_name, profile_pic FROM businesses WHERE address LIKE '%Vegadeo%'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<a href="MiNegocio.php?username=' . urlencode($row['username']) . '" class="business-item">';
                            echo '<img src="uploads/' . htmlspecialchars($row['profile_pic']) . '" alt="' . htmlspecialchars($row['business_name']) . '">';
                            echo '<p>' . htmlspecialchars($row['business_name']) . '</p>';
                            echo '</a>';
                        }
                    } else {
                        echo '<p>No hay negocios registrados en Vegadeo.</p>';
                    }

                    // Cerrar la conexión
                    $conn->close();
                    ?>
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