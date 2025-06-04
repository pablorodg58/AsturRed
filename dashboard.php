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

// Obtener tipos de negocio únicos de la base de datos
$stmt = $conn->prepare("SELECT DISTINCT tipo_negocio FROM businesses WHERE tipo_negocio IS NOT NULL AND tipo_negocio != '' ORDER BY tipo_negocio");
$stmt->execute();
$result = $stmt->get_result();
$tipos_negocio = [];
while ($row = $result->fetch_assoc()) {
    $tipos_negocio[] = $row['tipo_negocio'];
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

// Obtener todos los turistas con filtros
$filtro_nombre_turista = isset($_GET['nombre_turista']) ? $_GET['nombre_turista'] : '';
$filtro_ubicacion_turista = isset($_GET['ubicacion_turista']) ? $_GET['ubicacion_turista'] : '';
$orden_turistas = isset($_GET['orden_turistas']) ? $_GET['orden_turistas'] : 'id ASC';

$sql_turistas = "SELECT * FROM users WHERE 1=1";
$params_turistas = [];

if (!empty($filtro_nombre_turista)) {
    $sql_turistas .= " AND name LIKE ?";
    $params_turistas[] = "%$filtro_nombre_turista%";
}

if (!empty($filtro_ubicacion_turista)) {
    $sql_turistas .= " AND location LIKE ?";
    $params_turistas[] = "%$filtro_ubicacion_turista%";
}

$sql_turistas .= " ORDER BY $orden_turistas";

$stmt = $conn->prepare($sql_turistas);
if (!empty($params_turistas)) {
    $types = str_repeat('s', count($params_turistas));
    $stmt->bind_param($types, ...$params_turistas);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $turistas[] = $row;
    }
}

// Obtener todos los negocios con filtros
$filtro_nombre_negocio = isset($_GET['nombre_negocio']) ? $_GET['nombre_negocio'] : '';
$filtro_tipo_negocio = isset($_GET['tipo_negocio']) ? $_GET['tipo_negocio'] : '';
$filtro_ubicacion_negocio = isset($_GET['ubicacion_negocio']) ? $_GET['ubicacion_negocio'] : '';
$orden_negocios = isset($_GET['orden_negocios']) ? $_GET['orden_negocios'] : 'id ASC';

$sql_negocios = "SELECT * FROM businesses WHERE role = 'negocio'";
$params_negocios = [];

if (!empty($filtro_nombre_negocio)) {
    $sql_negocios .= " AND business_name LIKE ?";
    $params_negocios[] = "%$filtro_nombre_negocio%";
}

if (!empty($filtro_tipo_negocio)) {
    $sql_negocios .= " AND tipo_negocio LIKE ?";
    $params_negocios[] = "%$filtro_tipo_negocio%";
}

if (!empty($filtro_ubicacion_negocio)) {
    $sql_negocios .= " AND location LIKE ?";
    $params_negocios[] = "%$filtro_ubicacion_negocio%";
}

$sql_negocios .= " ORDER BY $orden_negocios";

$stmt = $conn->prepare($sql_negocios);
if (!empty($params_negocios)) {
    $types = str_repeat('s', count($params_negocios));
    $stmt->bind_param($types, ...$params_negocios);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $negocios[] = $row;
    }
}

// Obtener todos los ayuntamientos con filtros
$filtro_nombre_ayuntamiento = isset($_GET['nombre_ayuntamiento']) ? $_GET['nombre_ayuntamiento'] : '';
$filtro_ubicacion_ayuntamiento = isset($_GET['ubicacion_ayuntamiento']) ? $_GET['ubicacion_ayuntamiento'] : '';
$orden_ayuntamientos = isset($_GET['orden_ayuntamientos']) ? $_GET['orden_ayuntamientos'] : 'id ASC';

$sql_ayuntamientos = "SELECT * FROM businesses WHERE role = 'ayuntamiento'";
$params_ayuntamientos = [];

if (!empty($filtro_nombre_ayuntamiento)) {
    $sql_ayuntamientos .= " AND business_name LIKE ?";
    $params_ayuntamientos[] = "%$filtro_nombre_ayuntamiento%";
}

if (!empty($filtro_ubicacion_ayuntamiento)) {
    $sql_ayuntamientos .= " AND location LIKE ?";
    $params_ayuntamientos[] = "%$filtro_ubicacion_ayuntamiento%";
}

$sql_ayuntamientos .= " ORDER BY $orden_ayuntamientos";

$stmt = $conn->prepare($sql_ayuntamientos);
if (!empty($params_ayuntamientos)) {
    $types = str_repeat('s', count($params_ayuntamientos));
    $stmt->bind_param($types, ...$params_ayuntamientos);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ayuntamientos[] = $row;
    }
}

// Obtener todos los eventos con filtros
$filtro_titulo_evento = isset($_GET['titulo_evento']) ? $_GET['titulo_evento'] : '';
$filtro_fecha_evento = isset($_GET['fecha_evento']) ? $_GET['fecha_evento'] : '';
$filtro_ubicacion_evento = isset($_GET['ubicacion_evento']) ? $_GET['ubicacion_evento'] : '';
$orden_eventos = isset($_GET['orden_eventos']) ? $_GET['orden_eventos'] : 'date DESC';

$sql_eventos = "SELECT * FROM events WHERE 1=1";
$params_eventos = [];

if (!empty($filtro_titulo_evento)) {
    $sql_eventos .= " AND title LIKE ?";
    $params_eventos[] = "%$filtro_titulo_evento%";
}

if (!empty($filtro_fecha_evento)) {
    $sql_eventos .= " AND DATE(date) = ?";
    $params_eventos[] = $filtro_fecha_evento;
}

if (!empty($filtro_ubicacion_evento)) {
    $sql_eventos .= " AND location LIKE ?";
    $params_eventos[] = "%$filtro_ubicacion_evento%";
}

$sql_eventos .= " ORDER BY $orden_eventos";

$stmt = $conn->prepare($sql_eventos);
if (!empty($params_eventos)) {
    $types = str_repeat('s', count($params_eventos));
    $stmt->bind_param($types, ...$params_eventos);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
}

// Obtener todas las reseñas con filtros
$filtro_usuario_resena = isset($_GET['usuario_resena']) ? $_GET['usuario_resena'] : '';
$filtro_negocio_resena = isset($_GET['negocio_resena']) ? $_GET['negocio_resena'] : '';
$filtro_valoracion_resena = isset($_GET['valoracion_resena']) ? $_GET['valoracion_resena'] : '';
$orden_resenas = isset($_GET['orden_resenas']) ? $_GET['orden_resenas'] : 'created_at DESC';

$sql_resenas = "SELECT * FROM reviews WHERE 1=1";
$params_resenas = [];

if (!empty($filtro_usuario_resena)) {
    $sql_resenas .= " AND username LIKE ?";
    $params_resenas[] = "%$filtro_usuario_resena%";
}

if (!empty($filtro_negocio_resena)) {
    $sql_resenas .= " AND business_name LIKE ?";
    $params_resenas[] = "%$filtro_negocio_resena%";
}

if (!empty($filtro_valoracion_resena)) {
    $sql_resenas .= " AND rating = ?";
    $params_resenas[] = $filtro_valoracion_resena;
}

$sql_resenas .= " ORDER BY $orden_resenas";

$stmt = $conn->prepare($sql_resenas);
if (!empty($params_resenas)) {
    $types = str_repeat('s', count($params_resenas));
    $stmt->bind_param($types, ...$params_resenas);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reseñas[] = $row;
    }
}

// Obtener todas las imágenes de la galería de negocios con filtros
$filtro_negocio_galeria = isset($_GET['negocio_galeria']) ? $_GET['negocio_galeria'] : '';
$orden_galerias = isset($_GET['orden_galerias']) ? $_GET['orden_galerias'] : 'uploaded_at DESC';

$sql_galerias = "SELECT * FROM business_gallery WHERE 1=1";
$params_galerias = [];

if (!empty($filtro_negocio_galeria)) {
    $sql_galerias .= " AND business_username LIKE ?";
    $params_galerias[] = "%$filtro_negocio_galeria%";
}

$sql_galerias .= " ORDER BY $orden_galerias";

$stmt = $conn->prepare($sql_galerias);
if (!empty($params_galerias)) {
    $types = str_repeat('s', count($params_galerias));
    $stmt->bind_param($types, ...$params_galerias);
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

        try {
            $conn->begin_transaction();

            // 1. Eliminar reseñas asociadas al usuario
            $stmt = $conn->prepare("DELETE FROM reviews WHERE username = (SELECT name FROM users WHERE id = ?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // 2. Ahora eliminar el usuario
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $conn->commit();
                $_SESSION['success'] = "Turista eliminado correctamente";
            } else {
                $conn->rollback();
                $_SESSION['error'] = "Error al eliminar: " . $stmt->error;
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Error en la transacción: " . $e->getMessage();
        }

        header("Location: dashboard.php?section=turistas");
        exit();
    } elseif (isset($_POST['delete_business'])) {
        $id = $_POST['id'];
        try {
            $conn->begin_transaction();

            // 1. Eliminar imágenes de galería asociadas
            $stmt = $conn->prepare("DELETE FROM business_gallery WHERE business_username = (SELECT username FROM businesses WHERE id = ?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // 2. Eliminar reseñas asociadas
            $stmt = $conn->prepare("DELETE FROM reviews WHERE business_name = (SELECT business_name FROM businesses WHERE id = ?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // 3. Eliminar el negocio
            $stmt = $conn->prepare("DELETE FROM businesses WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $conn->commit();
                $_SESSION['success'] = "Negocio eliminado correctamente";
            } else {
                $conn->rollback();
                $_SESSION['error'] = "Error al eliminar: " . $stmt->error;
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Error en la transacción: " . $e->getMessage();
        }

        header("Location: dashboard.php?section=" . ($_POST['role'] == 'ayuntamiento' ? 'ayuntamientos' : 'negocios'));
        exit();
    } elseif (isset($_POST['delete_event'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Evento eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar evento: " . $stmt->error;
        }
        header("Location: dashboard.php?section=eventos");
        exit();
    } elseif (isset($_POST['delete_review'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Reseña eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar reseña: " . $stmt->error;
        }
        header("Location: dashboard.php?section=reseñas");
        exit();
    } elseif (isset($_POST['delete_gallery'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM business_gallery WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Imagen eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar imagen: " . $stmt->error;
        }
        header("Location: dashboard.php?section=galerias");
        exit();
    } elseif (isset($_POST['create_user'])) {
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $description = $_POST['description'];
        $location = $_POST['location'];

        $stmt = $conn->prepare("INSERT INTO users (name, password, description, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $password, $description, $location);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Turista creado correctamente";
        } else {
            $_SESSION['error'] = "Error al crear turista: " . $stmt->error;
        }
        header("Location: dashboard.php?section=turistas");
        exit();
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
        if ($stmt->execute()) {
            $_SESSION['success'] = ucfirst($role) . " creado correctamente";
        } else {
            $_SESSION['error'] = "Error al crear " . $role . ": " . $stmt->error;
        }
        header("Location: dashboard.php?section=" . ($role == 'ayuntamiento' ? 'ayuntamientos' : 'negocios'));
        exit();
    } elseif (isset($_POST['create_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    
    // Verificar si el usuario que crea el evento existe en la tabla businesses
    $created_by = $_SESSION['business_username'] ?? 'admin';
    
    // Verificar si el creador existe en la tabla businesses
    $stmt_check = $conn->prepare("SELECT username FROM businesses WHERE username = ?");
    $stmt_check->bind_param("s", $created_by);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows === 0) {
        $_SESSION['error'] = "El usuario que intenta crear el evento no existe en la base de datos";
        header("Location: dashboard.php?section=eventos");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO events (title, description, date, location, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $date, $location, $created_by);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Evento creado correctamente";
    } else {
        $_SESSION['error'] = "Error al crear evento: " . $stmt->error;
    }
    header("Location: dashboard.php?section=eventos");
    exit();

    } elseif (isset($_POST['create_review'])) {
        $username = $_POST['username'];
        $business_name = $_POST['business_name'];
        $review_text = $_POST['review_text'];
        $rating = $_POST['rating'];

        $stmt = $conn->prepare("INSERT INTO reviews (username, business_name, review_text, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $business_name, $review_text, $rating);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Reseña creada correctamente";
        } else {
            $_SESSION['error'] = "Error al crear reseña: " . $stmt->error;
        }
        header("Location: dashboard.php?section=reseñas");
        exit();
    }
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
            margin-left: 270px;
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

        .filtros {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .filtros h5 {
            margin-bottom: 15px;
            color: #495057;
        }

        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
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

            0%,
            100% {
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

        /* Estilos para el menú móvil */
        .menu-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            .menu-mobile {
                display: block;
                background-color: #343a40;
                padding: 10px;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1000;
            }

            .menu-content {
                display: none;
                flex-direction: row;
                overflow-x: auto;
                white-space: nowrap;
                background-color: #343a40;
                padding: 10px;
            }

            .menu-content.show {
                display: flex;
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
                margin: 0 5px;
            }

            #menu-toggle {
                display: block;
                margin-bottom: 10px;
            }

            .container {
                margin-top: 70px;
                margin-left: 20px;
                margin-right: 20px;
                width: calc(100% - 40px);
            }

            .sidebar {
                display: none;
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
            <div class="loading-text">Cargando Panel de Administración...</div>
        </div>
    </div>

    <!-- Mostrar mensajes de éxito/error -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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
        <a href="logout.php?from=dashboard" class="btn btn-danger mt-3">Cerrar Sesión</a>
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

    <!-- Sección de Turistas -->
    <div class="container" id="turistas-section">
        <h2>Turistas</h2>

        <!-- Filtros para turistas -->
        <div class="filtros">
            <h5>Filtrar Turistas</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="turistas">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="nombre_turista" class="form-control" placeholder="Nombre"
                            value="<?= htmlspecialchars($filtro_nombre_turista) ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="ubicacion_turista" class="form-control" placeholder="Ubicación"
                            value="<?= htmlspecialchars($filtro_ubicacion_turista) ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="orden_turistas" class="form-control">
                            <option value="id ASC" <?= $orden_turistas == 'id ASC' ? 'selected' : '' ?>>ID ↑</option>
                            <option value="id DESC" <?= $orden_turistas == 'id DESC' ? 'selected' : '' ?>>ID ↓</option>
                            <option value="name ASC" <?= $orden_turistas == 'name ASC' ? 'selected' : '' ?>>Nombre A-Z</option>
                            <option value="name DESC" <?= $orden_turistas == 'name DESC' ? 'selected' : '' ?>>Nombre Z-A</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=turistas" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar este turista?')">
                                    <input type="hidden" name="id" value="<?= $turista['id'] ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger">
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

        <!-- Filtros para negocios -->
        <div class="filtros">
            <h5>Filtrar Negocios</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="negocios">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="nombre_negocio" class="form-control" placeholder="Nombre del negocio"
                            value="<?= htmlspecialchars($filtro_nombre_negocio) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo_negocio" class="form-control">
                            <option value="">Todos los tipos</option>
                            <?php foreach ($tipos_negocio as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo) ?>" <?= $filtro_tipo_negocio == $tipo ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="ubicacion_negocio" class="form-control">
                            <option value="">Todas las ubicaciones</option>
                            <option value="Tapia" <?= $filtro_ubicacion_negocio == 'Tapia' ? 'selected' : '' ?>>Tapia</option>
                            <option value="Castropol" <?= $filtro_ubicacion_negocio == 'Castropol' ? 'selected' : '' ?>>Castropol</option>
                            <option value="Taramundi" <?= $filtro_ubicacion_negocio == 'Taramundi' ? 'selected' : '' ?>>Taramundi</option>
                            <option value="Vegadeo" <?= $filtro_ubicacion_negocio == 'Vegadeo' ? 'selected' : '' ?>>Vegadeo</option>
                            <option value="Puerto de Vega" <?= $filtro_ubicacion_negocio == 'Puerto de Vega' ? 'selected' : '' ?>>Puerto de Vega</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=negocios" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar este negocio?')">
                                    <input type="hidden" name="id" value="<?= $negocio['id'] ?>">
                                    <input type="hidden" name="role" value="<?= $negocio['role'] ?>">
                                    <button type="submit" name="delete_business" class="btn btn-danger">
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

        <!-- Filtros para ayuntamientos -->
        <div class="filtros">
            <h5>Filtrar Ayuntamientos</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="ayuntamientos">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="nombre_ayuntamiento" class="form-control" placeholder="Nombre"
                            value="<?= htmlspecialchars($filtro_nombre_ayuntamiento) ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="ubicacion_ayuntamiento" class="form-control">
                            <option value="">Todas las ubicaciones</option>
                            <option value="Tapia" <?= $filtro_ubicacion_ayuntamiento == 'Tapia' ? 'selected' : '' ?>>Tapia</option>
                            <option value="Navia" <?= $filtro_ubicacion_ayuntamiento == 'Navia' ? 'selected' : '' ?>>Navia</option>
                            <option value="Castropol" <?= $filtro_ubicacion_ayuntamiento == 'Castropol' ? 'selected' : '' ?>>Castropol</option>
                            <option value="Vegadeo" <?= $filtro_ubicacion_ayuntamiento == 'Vegadeo' ? 'selected' : '' ?>>Vegadeo</option>
                            <option value="Taramundi" <?= $filtro_ubicacion_ayuntamiento == 'Taramundi' ? 'selected' : '' ?>>Taramundi</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="orden_ayuntamientos" class="form-control">
                            <option value="id ASC" <?= $orden_ayuntamientos == 'id ASC' ? 'selected' : '' ?>>ID ↑</option>
                            <option value="id DESC" <?= $orden_ayuntamientos == 'id DESC' ? 'selected' : '' ?>>ID ↓</option>
                            <option value="business_name ASC" <?= $orden_ayuntamientos == 'business_name ASC' ? 'selected' : '' ?>>Nombre A-Z</option>
                            <option value="business_name DESC" <?= $orden_ayuntamientos == 'business_name DESC' ? 'selected' : '' ?>>Nombre Z-A</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=ayuntamientos" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar este ayuntamiento?')">
                                    <input type="hidden" name="id" value="<?= $ayuntamiento['id'] ?>">
                                    <input type="hidden" name="role" value="<?= $ayuntamiento['role'] ?>">
                                    <button type="submit" name="delete_business" class="btn btn-danger">
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

        <!-- Filtros para eventos -->
        <div class="filtros">
            <h5>Filtrar Eventos</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="eventos">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="titulo_evento" class="form-control" placeholder="Título"
                            value="<?= htmlspecialchars($filtro_titulo_evento) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_evento" class="form-control"
                            value="<?= htmlspecialchars($filtro_fecha_evento) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="ubicacion_evento" class="form-control" placeholder="Ubicación"
                            value="<?= htmlspecialchars($filtro_ubicacion_evento) ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=eventos" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                                    <button type="submit" name="delete_event" class="btn btn-danger">
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

        <!-- Filtros para reseñas -->
        <div class="filtros">
            <h5>Filtrar Reseñas</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="reseñas">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="usuario_resena" class="form-control" placeholder="Usuario"
                            value="<?= htmlspecialchars($filtro_usuario_resena) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="negocio_resena" class="form-control" placeholder="Negocio"
                            value="<?= htmlspecialchars($filtro_negocio_resena) ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="valoracion_resena" class="form-control">
                            <option value="">Todas</option>
                            <option value="1" <?= $filtro_valoracion_resena == '1' ? 'selected' : '' ?>>1 ★</option>
                            <option value="2" <?= $filtro_valoracion_resena == '2' ? 'selected' : '' ?>>2 ★★</option>
                            <option value="3" <?= $filtro_valoracion_resena == '3' ? 'selected' : '' ?>>3 ★★★</option>
                            <option value="4" <?= $filtro_valoracion_resena == '4' ? 'selected' : '' ?>>4 ★★★★</option>
                            <option value="5" <?= $filtro_valoracion_resena == '5' ? 'selected' : '' ?>>5 ★★★★★</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="orden_resenas" class="form-control">
                            <option value="created_at DESC" <?= $orden_resenas == 'created_at DESC' ? 'selected' : '' ?>>Más recientes</option>
                            <option value="created_at ASC" <?= $orden_resenas == 'created_at ASC' ? 'selected' : '' ?>>Más antiguas</option>
                            <option value="rating DESC" <?= $orden_resenas == 'rating DESC' ? 'selected' : '' ?>>Mejor valoradas</option>
                            <option value="rating ASC" <?= $orden_resenas == 'rating ASC' ? 'selected' : '' ?>>Peor valoradas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=reseñas" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar esta reseña?')">
                                    <input type="hidden" name="id" value="<?= $reseña['id'] ?>">
                                    <button type="submit" name="delete_review" class="btn btn-danger">
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

        <!-- Filtros para galerías -->
        <div class="filtros">
            <h5>Filtrar Galerías</h5>
            <form method="GET" action="dashboard.php">
                <input type="hidden" name="section" value="galerias">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="negocio_galeria" class="form-control" placeholder="Negocio"
                            value="<?= htmlspecialchars($filtro_negocio_galeria) ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="orden_galerias" class="form-control">
                            <option value="uploaded_at DESC" <?= $orden_galerias == 'uploaded_at DESC' ? 'selected' : '' ?>>Más recientes</option>
                            <option value="uploaded_at ASC" <?= $orden_galerias == 'uploaded_at ASC' ? 'selected' : '' ?>>Más antiguas</option>
                            <option value="business_username ASC" <?= $orden_galerias == 'business_username ASC' ? 'selected' : '' ?>>Negocio A-Z</option>
                            <option value="business_username DESC" <?= $orden_galerias == 'business_username DESC' ? 'selected' : '' ?>>Negocio Z-A</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="dashboard.php?section=galerias" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

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
                                <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?')">
                                    <input type="hidden" name="id" value="<?= $galeria['id'] ?>">
                                    <button type="submit" name="delete_gallery" class="btn btn-danger">
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

            // Actualizar el parámetro de sección en la URL
            const url = new URL(window.location);
            url.searchParams.set('section', section);
            window.history.pushState({}, '', url);
        }

        // Mostrar la sección de datos generales por defecto o la sección de la URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'datos-generales';
            showSection(section);

            // Configurar el gráfico
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

            const generalChart = new Chart(
                document.getElementById('generalChart'),
                config
            );
        });

        // Manejar el clic en el botón de "hamburguesa"
        document.getElementById('menu-toggle').addEventListener('click', function() {
            var menuContent = document.getElementById('menu-content');
            menuContent.classList.toggle('show');
        });

        // Cerrar automáticamente los mensajes de alerta después de 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

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
        window.addEventListener('beforeunload', function() {
            var loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        });
    </script>
</body>

</html>