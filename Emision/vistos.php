<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php

require '../bd.php';
// Establecer la zona horaria para Santiago de Chile.
date_default_timezone_set('America/Santiago');

// Obtener la fecha y hora actual con 5 horas de retraso.
$fecha_actual_retrasada = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Array con los nombres de los días en español.
$nombres_dias = array(
    'domingo',
    'lunes',
    'martes',
    'miércoles',
    'jueves',
    'viernes',
    'sábado'
);

// Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.).
$numero_dia = date('w', strtotime($fecha_actual_retrasada));

// Obtener el nombre del día actual en español.
$nombre_dia = $nombres_dias[$numero_dia];

$sql = "SELECT (Totales-Capitulos) as total FROM `emision` INNER join anime ON emision.ID_Anime = anime.id WHERE emision.Dia='" . $nombre_dia . "'and anime.Estado='Emision' ORDER BY `total` ASC limit 1";
$sql2 = $conexion->query($sql);
echo $sql . "<br>";

while ($mostrar = mysqli_fetch_array($sql2)) {

    $resta = $mostrar['total'];
    echo $resta . "<br>";
}

if ($resta <= 0) {
    echo "Menor a Cero<br>";

    echo '<script>
    Swal.fire({
        title: "Error!",
        text: "No se puede actualizar los animes del dia ' . $nombre_dia . ' en Emision.",
        icon: "error",
        showConfirmButton: false,
        timer: 2500
    }).then(function() {
        window.location = "/Anime/Emision/?enviar=&accion=HOY";
    });
    </script>';
} else {
    echo "Mayor a Cero<br>";
    $consulta = "UPDATE emision 
    INNER JOIN anime ON emision.ID_Anime = anime.id 
    SET emision.Capitulos = emision.Capitulos + 1 WHERE emision.dia LIKE '%" . $nombre_dia . "%'   
    AND anime.Estado = 'Emision' 
    AND emision.ID > 1;";
    $emision = $conexion->query($consulta);

    if ($emision === TRUE) {
        echo "La consulta fue exitosa.";
    } else {
        echo "La consulta falló: " . $conexion->error;
    }

    echo '<script>
    Swal.fire({
        title: "Exito!",
        text: "Se actualizaron los animes del dia ' . $nombre_dia . ' en Emision.",
        icon: "success",
        showConfirmButton: false,
        timer: 2500
    }).then(function() {
        window.location = "/Anime/Emision/?enviar=&accion=HOY";
    });
    </script>';
}




echo "<br>";
