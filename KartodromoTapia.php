<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Descubre la belleza y la historia de Castros de Coaña, sus negocios locales, rutas culturales y eventos.">
  <title>Kartodromo de Tapia</title>
  <!-- Si no utilizas StyloHtml.css, puedes eliminarlo -->
  <link href="StyloHtml.css" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Ajuste para que la navbar fija no oculte el contenido */
    body {
      padding: 0;
      margin:0;
    }
    
    /* Banner de imagen */
    .image-banner {
      position: relative;
      text-align: center;
      margin-top: 0; 
      padding: 0;
    }
    .image-banner img {
      width: 100%;
      height: auto;
      filter: brightness(0.8);
      max-height: 570px;
      margin-top: 0;
    }
    .image-banner h2 {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 36px; /* Tamaño por defecto */
        text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        white-space: nowrap;
      }
    /* Contenedor de negocios locales */
    .business-container {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    /* Contenedor de imágenes en fila */
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
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      margin-right: 10px;
    }
    .image-container img:last-child {
      margin-right: 0;
    }
    /* Sección de contenido (ahora visible por defecto) */
    .content {
      padding: 20px;
      max-width: 800px;
      margin: 20px auto;
      display: block;
    }
    .content img {
      width: 50%;
      height: auto;
      margin-top: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    /* Secciones generales */
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
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    /* Link para mostrar contenido (si decides usarlo) */
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
    /* Estilos base para el botón */
    .btn-custom {
      background: linear-gradient(45deg,rgb(1, 67, 67),rgb(11, 143, 143));
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background 1s ease, transform 1s ease;
    }

    /* Estilos al pasar el cursor */
    .btn-custom:hover {
      background: linear-gradient(45deg, rgb (11, 143, 143), rgb (1, 67, 67));
      transform: scale(1.05);
    }


    /* Footer */
    .footer {
      position: relative;
      bottom: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 20px;
    }
    

      @media (max-width: 768px) {
        .image-banner h2 {
          font-size: 24px; /* Reducir el tamaño en pantallas pequeñas */
        }
      }

      @media (max-width: 480px) {
        .image-banner h2 {
          font-size: 18px; /* Aún más pequeño en móviles más compactos */
        }
      }

  </style>
</head>
<body>

  <!-- Navbar fija -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="img/LogotipoMasTop-fotor-bg-remover-2024092820215 (1).png" alt="Logo" class="logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.html">Inicio</a>
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

  <!-- Banner de imagen -->
  <header class="image-banner">
    <img src="img/Trazado-Kartodromo-de-Tapia.webp" alt="Castros de Coaña">
    <h2>Kartodromo de Tapia</h2>
  </header>
  
  <!-- Contenido principal -->
  <div class="content">
    <h2 class="text-center">Acerca de Castros de Coaña</h2>
    <p>
      Castros de Coaña es un lugar lleno de historia y tradición, reconocido por sus vestigios arqueológicos y su impresionante paisaje natural. 
      Ubicado en el corazón de Asturias, este destino ofrece rutas históricas, experiencias culturales y una gastronomía que resalta lo mejor de la región. 
      Ideal para los amantes de la historia y la naturaleza.
    </p>
    
    <div class="image-container">
      <img src="img/Trazado-Kartodromo-de-Tapia.webp" alt="Castros de Coaña">
      <img src="img/castro-coana.jpg" alt="Paisaje de Castros de Coaña">
    </div>
    
    <div class="text-center my-4">
      <a href="Navia.html" class="btn btn-custom">Volver a Tapia</a>
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
