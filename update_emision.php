<?php

date_default_timezone_set('America/Santiago');

$max_queries_per_day = 1;

$current_time = date("Y-m-d H:i:s");
$Hoy = date("Y-m-d");

// Consultamos el número de consultas realizadas en la última hora
$query = "
 SELECT 
    COUNT(*) AS num_queries, 
    MAX(Fecha) AS ultima_actualizacion 
FROM 
    actualizaciones_anime 
WHERE 
    Fecha LIKE '%$Hoy%'
;
";

$result = mysqli_query($conexion, $query);
if (!$result) {
    die("La consulta falló: " . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($result);
$num_queries_last_day = $row['num_queries'];
$ultima_actualizacion = $row['ultima_actualizacion'];

mysqli_free_result($result);

// Formatear la fecha y hora de la última actualización a "HH:MM"
if ($ultima_actualizacion) {
    $datetime = new DateTime($ultima_actualizacion);
    $formatted_time = $datetime->format('H:i');
    $new_time = $datetime->format('Y-m-d');
} else {
    $formatted_time = "No disponible"; // Manejo de caso donde no haya actualizaciones
    $new_time = "";
}

// Obtenemos el día actual
$sql1 = "SELECT CONCAT( CASE WEEKDAY(CURDATE()) 
    WHEN 0 THEN 'Lunes' 
    WHEN 1 THEN 'Martes' 
    WHEN 2 THEN 'Miercoles' 
    WHEN 3 THEN 'Jueves' 
    WHEN 4 THEN 'Viernes' 
    WHEN 5 THEN 'Sabado' 
    WHEN 6 THEN 'Domingo' 
    END ) 
    AS DiaActual;";
$date = mysqli_query($conexion, $sql1);
$day = mysqli_fetch_assoc($date)['DiaActual'];
mysqli_free_result($date);

// Consultamos el número de webtoons en emisión para el día actual
$consulta = "SELECT COUNT(*) AS count FROM `emision` WHERE `Dia`= '$day' AND Emision='Emision'";
$result = mysqli_query($conexion, $consulta);
$count = mysqli_fetch_assoc($result)['count'];
mysqli_free_result($result);

// Si se han superado las consultas permitidas, lanzamos un error
if ($count >= 1 && $num_queries_last_day < $max_queries_per_day) {
    $query = "INSERT INTO actualizaciones_anime (Fecha) VALUES ('$current_time')";
    mysqli_query($conexion, $query);

    $sql = "UPDATE `emision` SET `Faltantes` = `Faltantes` + 1 WHERE `Dia`= '$day' AND Emision='Emision' AND Faltantes != Totales";
    mysqli_query($conexion, $sql);
}

if ($new_time == $Hoy) {
    $text = "Hoy se actualizo a las " . $formatted_time;
    $estatus = "activo";
} else {
    $text = "No se actualizo " . $ultima_actualizacion;
    $estatus = "finalizado";
}
