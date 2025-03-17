<?php
// conexion.php

// Configuración de la base de datos
$host = "bjuzdlsknbz0uch5kv40-mysql.services.clever-cloud.com"; 
$user = "ukhzdlk7mqzsshz1"; 
$password = "Xu8Q61oB9r28uMUlAGPe";  
$database = "bjuzdlsknbz0uch5kv40"; 
$port = 3306; 

// Crear conexión con la base de datos
$conn = new mysqli($host, $user, $password, $database, $port);

// Establecer el tiempo de espera de la conexión
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 60);

// Comprobar si hubo un error de conexión
if ($conn->connect_error) {
    // Manejo de errores de conexión
    error_log("Error de conexión: " . $conn->connect_error);  // Guardar error en el log
    die("Hubo un problema al conectar con la base de datos. Intenta más tarde.");
}

// Establecer el conjunto de caracteres para la conexión
$conn->set_charset("utf8mb4");
?>