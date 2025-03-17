<?php
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: loginform.php");
    exit();
}

include "conexion.php";  // Incluir conexión a la base de datos

// Obtener todos los turistas
$turistas = [];
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $turistas[] = $row;
    }
}

// Obtener todos los negocios
$negocios = [];
$stmt = $conn->prepare("SELECT * FROM businesses");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $negocios[] = $row;
    }
}

// Procesar actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $location = $_POST['location'];

        $stmt = $conn->prepare("UPDATE users SET name = ?, description = ?, location = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $description, $location, $id);
        $stmt->execute();
    } elseif (isset($_POST['update_business'])) {
        $id = $_POST['id'];
        $business_name = $_POST['business_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE businesses SET business_name = ?, email = ?, address = ?, phone = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $business_name, $email, $address, $phone, $description, $id);
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
        }
        .container {
            margin-top: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .form-control {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Panel de Administración</h1>
        <a href="logout.php" class="btn btn-danger mb-3">Cerrar Sesión</a>

        <!-- Sección de Turistas -->
        <h2>Turistas</h2>
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
                            <form method="POST" action="dashboard.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $turista['id'] ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($turista['name']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="description" value="<?= htmlspecialchars($turista['description']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="location" value="<?= htmlspecialchars($turista['location']) ?>" class="form-control">
                        </td>
                        <td>
                                <button type="submit" name="update_user" class="btn btn-primary">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Sección de Negocios -->
        <h2>Negocios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Negocio</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($negocios as $negocio): ?>
                    <tr>
                        <td><?= htmlspecialchars($negocio['id']) ?></td>
                        <td>
                            <form method="POST" action="dashboard.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $negocio['id'] ?>">
                                <input type="text" name="business_name" value="<?= htmlspecialchars($negocio['business_name']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="email" value="<?= htmlspecialchars($negocio['email']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="address" value="<?= htmlspecialchars($negocio['address']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="phone" value="<?= htmlspecialchars($negocio['phone']) ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="description" value="<?= htmlspecialchars($negocio['description']) ?>" class="form-control">
                        </td>
                        <td>
                                <button type="submit" name="update_business" class="btn btn-primary">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>