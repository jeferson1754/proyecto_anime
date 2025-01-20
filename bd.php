<?php

$usuario  = "root";
$password = "";
$servidor = "localhost";
$basededatos = "anime_test";
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
mysqli_query($conexion, "SET SESSION collation_connection ='utf8_unicode_ci'");
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

try {
    $connect = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


//Linea para los caracteres �

if (!mysqli_set_charset($conexion, "utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
    exit();
}

if (mysqli_connect_errno()) {
    die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
}
/*

$max_queries_per_hour = 700;

$current_time = date("Y-m-d H:i:s", time());

// Consultamos el número de consultas realizadas en la última hora
$query = "SELECT COUNT(*) AS num_queries FROM consultas WHERE fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$result = mysqli_query($conexion, $query);

// Si la consulta falla, lanzamos un error
if (!$result) {
    die("La consulta falló: " . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($result);
$num_queries_last_hour = $row["num_queries"];

// Liberamos el resultado de la consulta
mysqli_free_result($result);

// Si se han superado las consultas permitidas, lanzamos un error
if ($num_queries_last_hour >= $max_queries_per_hour) {
    mysqli_close($conexion); // Cerramos la conexión a la base de datos
    die("Lo siento, has superado el límite de consultas por hora.");
}

$query = "INSERT INTO consultas (fecha) VALUES ('$current_time')";
$result = mysqli_query($conexion, $query);

if (!$result) {
    die("La consulta falló: " . mysqli_error($conexion));
}

*/

// Establecer la zona horaria para Santiago de Chile.
date_default_timezone_set('America/Santiago');

// Obtener la fecha y hora actual con 5 horas de retraso.
$fecha_actual_retrasada = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Array con los nombres de los días en español.
$nombres_dias = array(
    'Domingo',
    'Lunes',
    'Martes',
    'Miercoles',
    'Jueves',
    'Viernes',
    'Sabado'
);

// Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.).
$numero_dia = date('w', strtotime($fecha_actual_retrasada));

// Obtener el nombre del día actual en español.
$day = $nombres_dias[$numero_dia];

// Mapeo de valores de temporada
$temporadas = [
    1 => "Invierno",
    2 => "Primavera",
    3 => "Verano",
    4 => "Otoño",
    5 => "Desconocida"
];
