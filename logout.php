<?php
session_start();

// Determinar la URL de redirección
$redirect_url = 'index.php';

// Verificar si viene del dashboard (por parámetro o por referer)
$from_dashboard = isset($_GET['from']) && $_GET['from'] === 'dashboard';
if (!$from_dashboard && isset($_SERVER['HTTP_REFERER'])) {
    $referer = filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL);
    $path = parse_url($referer, PHP_URL_PATH);
    $excluded = ['loginform.php', 'loginauth.php', 'register.php', 'registerauth.php'];
    if (!in_array(basename($path), $excluded)) {
        $redirect_url = $referer;
    }
}

// Destruir sesión
session_unset();
session_destroy();

// Redirigir
header("Location: " . $redirect_url);
exit();
?>