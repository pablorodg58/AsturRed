<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: loginform.php");
    exit();
}

include "conexion.php";  // Incluir conexión a la base de datos

// Inicializar variables como arrays vacíos
$turistas = [];
$negocios = [];
$ayuntamientos = [];
$eventos = [];
$reseñas = [];
$galerias = [];

// Verificar conexión a la base de datos
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el número de turistas
$stmt = $conn->prepare("SELECT COUNT(*) as total_turistas FROM users");
$stmt->execute();
$result = $stmt->get_result();
$total_turistas = $result->fetch_assoc()['total_turistas'];

// Obtener el número de negocios
$stmt = $conn->prepare("SELECT COUNT(*) as total_negocios FROM businesses WHERE role = 'negocio'");
$stmt->execute();
$result = $stmt->get_result();
$total_negocios = $result->fetch_assoc()['total_negocios'];

// Obtener el número de eventos
$stmt = $conn->prepare("SELECT COUNT(*) as total_eventos FROM events");
$stmt->execute();
$result = $stmt->get_result();
$total_eventos = $result->fetch_assoc()['total_eventos'];

// Obtener el número de reseñas
$stmt = $conn->prepare("SELECT COUNT(*) as total_reseñas FROM reviews");
$stmt->execute();
$result = $stmt->get_result();
$total_reseñas = $result->fetch_assoc()['total_reseñas'];

// Obtener todos los turistas
$stmt = $conn->prepare("SELECT * FROM users");
if (!$stmt) {
    die("Error en la consulta SQL de usuarios: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $turistas[] = $row;
    }
}

// Obtener todos los negocios y ayuntamientos
$stmt = $conn->prepare("SELECT * FROM businesses");
if (!$stmt) {
    die("Error en la consulta SQL de negocios: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] === 'negocio') {
            $negocios[] = $row;
        } elseif ($row['role'] === 'ayuntamiento') {
            $ayuntamientos[] = $row;
        }
    }
}

// Obtener todos los eventos
$stmt = $conn->prepare("SELECT * FROM events");
if (!$stmt) {
    die("Error en la consulta SQL de eventos: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
}

// Obtener todas las reseñas
$stmt = $conn->prepare("SELECT * FROM reviews");
if (!$stmt) {
    die("Error en la consulta SQL de reseñas: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reseñas[] = $row;
    }
}

// Obtener todas las imágenes de la galería de negocios
$stmt = $conn->prepare("SELECT * FROM business_gallery");
if (!$stmt) {
    die("Error en la consulta SQL de galerías: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $galerias[] = $row;
    }
}

// Procesar operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_changes'])) {
        // Actualizar turistas
        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $user) {
                $id = $user['id'];
                $name = $user['name'];
                $description = $user['description'];
                $location = $user['location'];

                $stmt = $conn->prepare("UPDATE users SET name = ?, description = ?, location = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $description, $location, $id);
                $stmt->execute();
            }
        }

        // Actualizar negocios
        if (isset($_POST['negocios'])) {
            foreach ($_POST['negocios'] as $negocio) {
                $id = $negocio['id'];
                $business_name = $negocio['business_name'];
                $email = $negocio['email'];
                $address = $negocio['address'];
                $location = $negocio['location'];
                $phone = $negocio['phone'];
                $description = $negocio['description'];
                $tipo_negocio = $negocio['tipo_negocio'];

                $stmt = $conn->prepare("UPDATE businesses SET business_name = ?, email = ?, address = ?, location = ?, phone = ?, description = ?, tipo_negocio = ? WHERE id = ?");
                $stmt->bind_param("sssssssi", $business_name, $email, $address, $location, $phone, $description, $tipo_negocio, $id);
                $stmt->execute();
            }
        }

        // Actualizar ayuntamientos
        if (isset($_POST['ayuntamientos'])) {
            foreach ($_POST['ayuntamientos'] as $ayuntamiento) {
                $id = $ayuntamiento['id'];
                $business_name = $ayuntamiento['business_name'];
                $email = $ayuntamiento['email'];
                $address = $ayuntamiento['address'];
                $location = $ayuntamiento['location'];
                $phone = $ayuntamiento['phone'];
                $description = $ayuntamiento['description'];

                $stmt = $conn->prepare("UPDATE businesses SET business_name = ?, email = ?, address = ?, location = ?, phone = ?, description = ? WHERE id = ?");
                $stmt->bind_param("ssssssi", $business_name, $email, $address, $location, $phone, $description, $id);
                $stmt->execute();
            }
        }

        // Actualizar eventos
        if (isset($_POST['eventos'])) {
            foreach ($_POST['eventos'] as $evento) {
                $id = $evento['id'];
                $title = $evento['title'];
                $description = $evento['description'];
                $date = $evento['date'];
                $location = $evento['location'];

                $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date = ?, location = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $title, $description, $date, $location, $id);
                $stmt->execute();
            }
        }

        // Actualizar reseñas
        if (isset($_POST['reseñas'])) {
            foreach ($_POST['reseñas'] as $reseña) {
                $id = $reseña['id'];
                $username = $reseña['username'];
                $business_name = $reseña['business_name'];
                $review_text = $reseña['review_text'];
                $rating = $reseña['rating'];

                $stmt = $conn->prepare("UPDATE reviews SET username = ?, business_name = ?, review_text = ?, rating = ? WHERE id = ?");
                $stmt->bind_param("sssii", $username, $business_name, $review_text, $rating, $id);
                $stmt->execute();
            }
        }

        // Recargar la página para ver los cambios
        header("Location: dashboard.php");
        exit();
    } elseif (isset($_POST['delete_user'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_business'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM businesses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_event'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_review'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_gallery'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM business_gallery WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['create_user'])) {
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $description = $_POST['description'];
        $location = $_POST['location'];

        $stmt = $conn->prepare("INSERT INTO users (name, password, description, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $password, $description, $location);
        $stmt->execute();
    } elseif (isset($_POST['create_business'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $business_name = $_POST['business_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $location = $_POST['location'];
        $phone = $_POST['phone'];
        $description = $_POST['description'];
        $role = $_POST['role'];
        $tipo_negocio = $_POST['tipo_negocio'];

        $stmt = $conn->prepare("INSERT INTO businesses (username, password, business_name, email, address, location, phone, description, role, tipo_negocio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $password, $business_name, $email, $address, $location, $phone, $description, $role, $tipo_negocio);
        $stmt->execute();
    } elseif (isset($_POST['create_event'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $created_by = $_SESSION['admin_username']; // Asignar el administrador como creador

        $stmt = $conn->prepare("INSERT INTO events (title, description, date, location, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $date, $location, $created_by);
        $stmt->execute();
    } elseif (isset($_POST['create_review'])) {
        $username = $_POST['username'];
        $business_name = $_POST['business_name'];
        $review_text = $_POST['review_text'];
        $rating = $_POST['rating'];

        $stmt = $conn->prepare("INSERT INTO reviews (username, business_name, review_text, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $business_name, $review_text, $rating);
        $stmt->execute();
    }

    // Recargar la página para ver los cambios
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .container {
            margin-left: 270px; /* Ajuste para el margen izquierdo */
            margin-right: 30px;
            flex-grow: 1;
            padding: 20px;
            display: none; 
        }
        .table {
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 40px;
        }
        .btn-save {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            margin-bottom: 30px;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .modal-content {
            border-radius: 10px;
        }
        .modal-header {
            background-color: #343a40;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .modal-title {
            font-weight: bold;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            text-align: center;
        }
        .card-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .card-text {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-turistas {
            background-color: #007bff;
            color: white;
        }
        .card-negocios {
            background-color: #28a745;
            color: white;
        }
        .card-eventos {
            background-color: #ffc107;
            color: white;
        }
        .card-reseñas {
            background-color: #dc3545;
            color: white;
        }
        .chart-container {
            width: 100%;
            margin: 0 auto;
        }
        #generalChart {
            max-height: 400px;
        }

        /* Estilos para el menú móvil */
        .menu-mobile {
            display: none; /* Ocultar por defecto en pantallas grandes */
        }

        @media (max-width: 768px) {
            .menu-mobile {
                display: block; /* Mostrar en pantallas pequeñas */
                background-color: #343a40;
                padding: 10px;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1000;
            }

            .menu-content {
                display: none; /* Ocultar el contenido del menú por defecto */
                flex-direction: row;
                overflow-x: auto; /* Barra horizontal */
                white-space: nowrap; /* Evitar saltos de línea */
                background-color: #343a40;
                padding: 10px;
            }

            .menu-content.show {
                display: flex; /* Mostrar el contenido del menú cuando tenga la clase "show" */
            }

            .menu-content a {
                color: white;
                text-decoration: none;
                padding: 10px;
                margin: 0 5px;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .menu-content a:hover {
                background-color: #495057;
            }

            .menu-content .btn-danger {
                margin: 0 5px; /* Alinear el botón "Cerrar Sesión" con los demás */
            }

            #menu-toggle {
                display: block;
                margin-bottom: 10px;
            }

            /* Ajustar el margen superior del contenido principal para evitar solapamiento */
            .container {
                margin-top: 70px; /* Ajusta según la altura del menú */
                margin-left: 20px; /* Centrar el contenido en móvil */
                margin-right: 20px; /* Centrar el contenido en móvil */
                width: calc(100% - 40px); /* Ajustar el ancho */
            }
        }
    </style>
</head>
<body>
   <!-- Menú móvil -->
   <div class="menu-mobile">
        <button id="menu-toggle" class="btn btn-secondary">☰</button>
        <div id="menu-content" class="menu-content">
            <a href="#" onclick="showSection('datos-generales')">Datos Generales</a>
            <a href="#" onclick="showSection('turistas')">Turistas</a>
            <a href="#" onclick="showSection('negocios')">Negocios</a>
            <a href="#" onclick="showSection('ayuntamientos')">Ayuntamientos</a>
            <a href="#" onclick="showSection('eventos')">Eventos</a>
            <a href="#" onclick="showSection('reseñas')">Reseñas</a>
            <a href="#" onclick="showSection('galerias')">Galerías</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Menú lateral para pantallas grandes -->
    <div class="sidebar d-none d-md-block">
        <h3>Menú</h3>
        <a href="#" onclick="showSection('datos-generales')">Datos Generales</a>
        <a href="#" onclick="showSection('turistas')">Turistas</a>
        <a href="#" onclick="showSection('negocios')">Negocios</a>
        <a href="#" onclick="showSection('ayuntamientos')">Ayuntamientos</a>
        <a href="#" onclick="showSection('eventos')">Eventos</a>
        <a href="#" onclick="showSection('reseñas')">Reseñas</a>
        <a href="#" onclick="showSection('galerias')">Galerías</a>
        <a href="logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
    </div>

    <!-- Sección de Datos Generales -->
    <div class="container" id="datos-generales-section">
        <h2>Datos Generales</h2>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card card-turistas">
                    <div class="card-body">
                        <h5 class="card-title">Turistas Registrados</h5>
                        <p class="card-text"><?= $total_turistas ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-negocios">
                    <div class="card-body">
                        <h5 class="card-title">Negocios</h5>
                        <p class="card-text"><?= $total_negocios ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-eventos">
                    <div class="card-body">
                        <h5 class="card-title">Eventos</h5>
                        <p class="card-text"><?= $total_eventos ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-reseñas">
                    <div class="card-body">
                        <h5 class="card-title">Reseñas</h5>
                        <p class="card-text"><?= $total_reseñas ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 chart-container">
                <canvas id="generalChart"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Función para mostrar la sección seleccionada
        function showSection(section) {
            // Ocultar todas las secciones
            document.querySelectorAll('.container').forEach(container => {
                container.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            document.getElementById(`${section}-section`).style.display = 'block';
        }

        // Mostrar la sección de datos generales por defecto
        showSection('datos-generales');

        // Datos para el gráfico
        const data = {
            labels: ['Turistas', 'Negocios', 'Eventos', 'Reseñas'],
            datasets: [{
                label: 'Cantidad',
                data: [<?= $total_turistas ?>, <?= $total_negocios ?>, <?= $total_eventos ?>, <?= $total_reseñas ?>],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        };

        // Configuración del gráfico
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Renderizar el gráfico
        const generalChart = new Chart(
            document.getElementById('generalChart'),
            config
        );

        // Manejar el clic en el botón de "hamburguesa"
        document.getElementById('menu-toggle').addEventListener('click', function() {
            var menuContent = document.getElementById('menu-content');
            menuContent.classList.toggle('show');
        });
    </script>

    <!-- Sección de Turistas -->
    <div class="container" id="turistas-section">
        <h2>Turistas</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">Crear Turista</button>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turistas as $turista): ?>
                        <tr>
                            <td><?= htmlspecialchars($turista['id']) ?></td>
                            <td>
                                <input type="hidden" name="users[<?= $turista['id'] ?>][id]" value="<?= $turista['id'] ?>">
                                <input type="text" name="users[<?= $turista['id'] ?>][name]" 
                                       value="<?= htmlspecialchars($turista['name']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="users[<?= $turista['id'] ?>][description]" 
                                       value="<?= htmlspecialchars($turista['description']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="users[<?= $turista['id'] ?>][location]" 
                                       value="<?= htmlspecialchars($turista['location']) ?>" class="form-control">
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $turista['id'] ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar este turista?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save_changes" class="btn btn-primary btn-save">Guardar Cambios</button>
        </form>
    </div>

    <!-- Sección de Negocios -->
    <div class="container" id="negocios-section">
        <h2>Negocios</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createBusinessModal">Crear Negocio</button>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
                        <th>Descripción</th>
                        <th>Tipo de Negocio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($negocios as $negocio): ?>
                        <tr>
                            <td><?= htmlspecialchars($negocio['id']) ?></td>
                            <td>
                                <input type="hidden" name="negocios[<?= $negocio['id'] ?>][id]" value="<?= $negocio['id'] ?>">
                                <input type="text" name="negocios[<?= $negocio['id'] ?>][business_name]" 
                                       value="<?= htmlspecialchars($negocio['business_name']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="email" name="negocios[<?= $negocio['id'] ?>][email]" 
                                       value="<?= htmlspecialchars($negocio['email']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="negocios[<?= $negocio['id'] ?>][address]" 
                                       value="<?= htmlspecialchars($negocio['address']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="negocios[<?= $negocio['id'] ?>][location]" 
                                       value="<?= htmlspecialchars($negocio['location']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="tel" name="negocios[<?= $negocio['id'] ?>][phone]" 
                                       value="<?= htmlspecialchars($negocio['phone']) ?>" class="form-control">
                            </td>
                            <td>
                                <textarea name="negocios[<?= $negocio['id'] ?>][description]" 
                                    class="form-control"><?= htmlspecialchars($negocio['description']) ?></textarea>
                            </td>
                            <td>
                                <input type="text" name="negocios[<?= $negocio['id'] ?>][tipo_negocio]" 
                                       value="<?= htmlspecialchars($negocio['tipo_negocio']) ?>" class="form-control">
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $negocio['id'] ?>">
                                    <button type="submit" name="delete_business" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar este negocio?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save_changes" class="btn btn-primary btn-save">Guardar Cambios</button>
        </form>
    </div>

    <!-- Sección de Ayuntamientos -->
    <div class="container" id="ayuntamientos-section">
        <h2>Ayuntamientos</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createBusinessModal">Crear Ayuntamiento</button>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ayuntamientos as $ayuntamiento): ?>
                        <tr>
                            <td><?= htmlspecialchars($ayuntamiento['id']) ?></td>
                            <td>
                                <input type="hidden" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][id]" value="<?= $ayuntamiento['id'] ?>">
                                <input type="text" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][business_name]" 
                                       value="<?= htmlspecialchars($ayuntamiento['business_name']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="email" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][email]" 
                                       value="<?= htmlspecialchars($ayuntamiento['email']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][address]" 
                                       value="<?= htmlspecialchars($ayuntamiento['address']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][location]" 
                                       value="<?= htmlspecialchars($ayuntamiento['location']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="tel" name="ayuntamientos[<?= $ayuntamiento['id'] ?>][phone]" 
                                       value="<?= htmlspecialchars($ayuntamiento['phone']) ?>" class="form-control">
                            </td>
                            <td>
                                <textarea name="ayuntamientos[<?= $ayuntamiento['id'] ?>][description]" 
                                    class="form-control"><?= htmlspecialchars($ayuntamiento['description']) ?></textarea>
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $ayuntamiento['id'] ?>">
                                    <button type="submit" name="delete_business" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar este ayuntamiento?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save_changes" class="btn btn-primary btn-save">Guardar Cambios</button>
        </form>
    </div>

    <!-- Sección de Eventos -->
    <div class="container" id="eventos-section">
        <h2>Eventos</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createEventModal">Crear Evento</button>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                        <tr>
                            <td><?= htmlspecialchars($evento['id']) ?></td>
                            <td>
                                <input type="hidden" name="eventos[<?= $evento['id'] ?>][id]" value="<?= $evento['id'] ?>">
                                <input type="text" name="eventos[<?= $evento['id'] ?>][title]" 
                                       value="<?= htmlspecialchars($evento['title']) ?>" class="form-control">
                            </td>
                            <td>
                                <textarea name="eventos[<?= $evento['id'] ?>][description]" 
                                    class="form-control"><?= htmlspecialchars($evento['description']) ?></textarea>
                            </td>
                            <td>
                                <input type="datetime-local" name="eventos[<?= $evento['id'] ?>][date]" 
                                       value="<?= htmlspecialchars($evento['date']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="eventos[<?= $evento['id'] ?>][location]" 
                                       value="<?= htmlspecialchars($evento['location']) ?>" class="form-control">
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                                    <button type="submit" name="delete_event" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save_changes" class="btn btn-primary btn-save">Guardar Cambios</button>
        </form>
    </div>

    <!-- Sección de Reseñas -->
    <div class="container" id="reseñas-section">
        <h2>Reseñas</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createReviewModal">Crear Reseña</button>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Negocio</th>
                        <th>Reseña</th>
                        <th>Valoración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reseñas as $reseña): ?>
                        <tr>
                            <td><?= htmlspecialchars($reseña['id']) ?></td>
                            <td>
                                <input type="hidden" name="reseñas[<?= $reseña['id'] ?>][id]" value="<?= $reseña['id'] ?>">
                                <input type="text" name="reseñas[<?= $reseña['id'] ?>][username]" 
                                       value="<?= htmlspecialchars($reseña['username']) ?>" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="reseñas[<?= $reseña['id'] ?>][business_name]" 
                                       value="<?= htmlspecialchars($reseña['business_name']) ?>" class="form-control">
                            </td>
                            <td>
                                <textarea name="reseñas[<?= $reseña['id'] ?>][review_text]" 
                                    class="form-control"><?= htmlspecialchars($reseña['review_text']) ?></textarea>
                            </td>
                            <td>
                                <input type="number" name="reseñas[<?= $reseña['id'] ?>][rating]" 
                                       value="<?= htmlspecialchars($reseña['rating']) ?>" class="form-control">
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $reseña['id'] ?>">
                                    <button type="submit" name="delete_review" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta reseña?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save_changes" class="btn btn-primary btn-save">Guardar Cambios</button>
        </form>
    </div>

    <!-- Sección de Galerías -->
    <div class="container" id="galerias-section">
        <h2>Galerías</h2>
        <form method="POST" action="dashboard.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Negocio</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($galerias as $galeria): ?>
                        <tr>
                            <td><?= htmlspecialchars($galeria['id']) ?></td>
                            <td><?= htmlspecialchars($galeria['business_username']) ?></td>
                            <td>
                                <img src="uploads/<?= htmlspecialchars($galeria['image_path']) ?>" alt="Imagen de galería" style="max-width: 100px;">
                            </td>
                            <td>
                                <form method="POST" action="dashboard.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $galeria['id'] ?>">
                                    <button type="submit" name="delete_gallery" class="btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta imagen?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- Modal para crear turista -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Crear Turista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="dashboard.php">
                        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        <input type="text" name="description" class="form-control" placeholder="Descripción">
                        <input type="text" name="location" class="form-control" placeholder="Ubicación">
                        <button type="submit" name="create_user" class="btn btn-success">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear negocio/ayuntamiento -->
    <div class="modal fade" id="createBusinessModal" tabindex="-1" aria-labelledby="createBusinessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBusinessModalLabel">Crear Negocio/Ayuntamiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="dashboard.php">
                        <input type="text" name="username" class="form-control" placeholder="Nombre de usuario" required>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        <input type="text" name="business_name" class="form-control" placeholder="Nombre del negocio/ayuntamiento" required>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <input type="text" name="address" class="form-control" placeholder="Dirección">
                        <input type="text" name="location" class="form-control" placeholder="Ubicación">
                        <input type="text" name="phone" class="form-control" placeholder="Teléfono">
                        <input type="text" name="description" class="form-control" placeholder="Descripción">
                        <select name="role" class="form-control" required>
                            <option value="negocio">Negocio</option>
                            <option value="ayuntamiento">Ayuntamiento</option>
                        </select>
                        <input type="text" name="tipo_negocio" class="form-control" placeholder="Tipo de negocio">
                        <button type="submit" name="create_business" class="btn btn-success">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear evento -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Crear Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="dashboard.php">
                        <input type="text" name="title" class="form-control" placeholder="Título" required>
                        <textarea name="description" class="form-control" placeholder="Descripción"></textarea>
                        <input type="datetime-local" name="date" class="form-control" placeholder="Fecha" required>
                        <input type="text" name="location" class="form-control" placeholder="Ubicación" required>
                        <button type="submit" name="create_event" class="btn btn-success">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear reseña -->
    <div class="modal fade" id="createReviewModal" tabindex="-1" aria-labelledby="createReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReviewModalLabel">Crear Reseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="dashboard.php">
                        <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                        <input type="text" name="business_name" class="form-control" placeholder="Negocio" required>
                        <textarea name="review_text" class="form-control" placeholder="Reseña"></textarea>
                        <input type="number" name="rating" class="form-control" placeholder="Valoración (1-5)" min="1" max="5" required>
                        <button type="submit" name="create_review" class="btn btn-success">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar la sección seleccionada
        function showSection(section) {
            // Ocultar todas las secciones
            document.querySelectorAll('.container').forEach(container => {
                container.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            document.getElementById(`${section}-section`).style.display = 'block';
        }

        // Mostrar la sección de datos generales por defecto
        showSection('datos-generales');
    </script>
</body>
</html>