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
    <title>Pueblos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="StyloHtml.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #333;
            overflow-x: hidden; /* Evita el desplazamiento horizontal */
            overflow-y: auto; /* Permite el desplazamiento vertical */
        }
        .background {
            background-image: url('img/tapia-de-casariego_260.jpg');
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.658); 
            z-index: -1;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.507);
            padding: 40px 20px;
            border-radius: 8px;
            max-width: 800px;
            margin-top: 100px;
            text-align: center;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px; 
        }
        .pueblo-item {
            position: relative;
            display: flex;
            flex-direction: column; 
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .pueblo-image {
            position: absolute;
            top: 0px; 
            left: 50%;
            transform: translateX(-50%);
            width: 99px; 
            height: auto; 
            transition: opacity 0.3s ease;
            opacity: 0;
            z-index: 0; 
        }
        .pueblo-button {
            background-color: #2e8b57;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            width: 170px; 
            height: 60px; 
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; 
            transition: background-color 0.3s ease;
            margin-top: 80px; 
            z-index: 1; 
        }
        .pueblo-button:hover {
            background-color: #006d6d;
            color: white;
        }
        .pueblo-item:hover .pueblo-image {
            opacity: 1;
        }
        .bubbles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }
        .bubble {
            position: absolute;
            bottom: -50px;
            width: 20px;
            height: 20px;
            background-color: rgba(173, 216, 230, 0.7);
            border-radius: 50%;
            animation: rise 5s infinite ease-in;
        }
        @keyframes rise {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh);
                opacity: 0;
            }
        }
        .navbar-brand img {
            height: 40px;
            width: auto; /* Mantiene las proporciones del logo */
        }

        .navbar-nav .nav-link {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #006d6d;
            color: white;
        }

        .navbar-nav .nav-link.active {
            background-color: #004d4d;
            color: white;
        }

        @media (max-width: 576px) {
            .pueblo-button {
                width: 250px; 
                font-size: 14px;
                margin-top: 60px;
            }
            .pueblo-image {
                width: 80px;
            }
        }
    </style>
</head>
<body>
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
    

    <div class="background"></div>
    <div class="overlay"></div>
    <div class="bubbles"></div>

    <div class="container">
        <h1>¿Qué pueblo quieres conocer?</h1>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up">
                <a href="Tapia.php">
                    <img src="img/faro tapia.png" alt="Faro de Tapia" class="pueblo-image">
                    <button class="pueblo-button">Tapia de Casariego</button>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up" data-aos-delay="100">
                <a href="Taramundi.php">
                    <img src="img/molinoTaramundi.png" alt="Imagen 2" class="pueblo-image">
                    <button class="pueblo-button">Taramundi</button>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up" data-aos-delay="200">
                <a href="castropol.php">
                    <img src="img/castropolIglesia.png" alt="Imagen 3" class="pueblo-image">
                    <button class="pueblo-button">Castropol</button>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up" data-aos-delay="300">
                <a href="Navia.php">
                    <img src="img/naviaItem.png" alt="Imagen 4" class="pueblo-image">
                    <button class="pueblo-button">Navia</button>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up" data-aos-delay="400">
                <a href="vegadeo.php">
                    <img src="img/vegadeo.png" alt="Imagen 5" class="pueblo-image">
                    <button class="pueblo-button">Vegadeo</button>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12 pueblo-item" data-aos="fade-up" data-aos-delay="500">
                <a href="puertovega.php">
                    <img src="img/puertoVegaPuerto.png" alt="Imagen 6" class="pueblo-image">
                    <button class="pueblo-button">Puerto de Vega</button>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
        
        function createBubbles() {
            const bubbleContainer = document.querySelector('.bubbles');
            for (let i = 0; i < 30; i++) {
                let bubble = document.createElement('div');
                bubble.classList.add('bubble');
                let size = Math.random() * 20 + 10;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${Math.random() * 100}vw`;
                bubble.style.animationDuration = `${Math.random() * 3 + 2}s`;
                bubbleContainer.appendChild(bubble);
            }
        }
        createBubbles();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>