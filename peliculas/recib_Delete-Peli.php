<!-- Comentarios -->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$idRegistros = $_REQUEST['id'];
$id_anime = $_REQUEST['id_anime'];
$estado = $_REQUEST['estado'];
$nombre = $_REQUEST['nombre'];

echo $estado . "<br>";
echo $idRegistros;

function alerta($alertTitle, $alertText, $alertType, $redireccion)
{

    echo '
 <script>
        Swal.fire({
            title: "' . $alertTitle . '",
            text: "' . $alertText . '",
            html: "' . $alertText . '",
            icon: "' . $alertType . '",
            showCancelButton: false,
            confirmButtonText: "OK",
            closeOnConfirm: false
        }).then(function() {
          ' . $redireccion . '  ; // Redirigir a la página principal
        });
    </script>';
}

// Establecer la conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $password, $basededatos);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Preparar las consultas
$deleteQueryPendientes = "DELETE peliculas.*, pendientes.*
                          FROM peliculas
                          JOIN pendientes ON peliculas.ID_Pendientes = pendientes.ID
                          WHERE peliculas.ID = '$idRegistros'";

$deleteQueryPeliculas = "DELETE FROM peliculas WHERE ID = '$idRegistros'";

// Consulta para insertar en la tabla id_peliculas
$insertQuery = "INSERT INTO id_peliculas (`ID`) VALUES ('$idRegistros')";

// Ejecutar las consultas según el estado
if ($estado == "Finalizado") {
    // Ejecutar las consultas para "Finalizado"
    if (mysqli_query($conexion, $insertQuery) && mysqli_query($conexion, $deleteQueryPeliculas)) {

        $alertTitle = '¡Pelicula Eliminada!';
        $alertText = 'Pelicula Eliminada ' . $nombre;
        $alertType = 'success';
        $redireccion = "window.location='./'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
    } else {
        $alertTitle = '¡Error al Eliminar!';
        $alertText = 'Error al eliminar de Película ' . $nombre;
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die();
    }
} elseif ($estado == "Pendiente") {
    // Ejecutar las consultas para "Pendiente"
    if (mysqli_query($conexion, $insertQuery) && mysqli_query($conexion, $deleteQueryPendientes)) {
        $alertTitle = '¡Pelicula y Pendientes Eliminado!';
        $alertText = 'Pelicula Eliminada ' . $nombre;
        $alertType = 'success';
        $redireccion = "window.location='./'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
    } else {
        $alertTitle = '¡Error al Eliminar!';
        $alertText = 'Error al eliminar de Película y Pendiente ' . $nombre;
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die();
    }
} else {
    $alertTitle = '¡Estado Invalido!';
    $alertText = 'La pelicula ' . $nombre . ' tiene un Estado Invalido, revisarlo';
    $alertType = 'error';
    $redireccion = "window.location='javascript:history.back()'";

    alerta($alertTitle, $alertText, $alertType, $redireccion);
    die();
}


try {
    // Conexión PDO
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Iniciar una transacción
    $conn->beginTransaction();

    // Consulta para obtener el total de películas por cada id_anime
    $sqlCount = "SELECT ID_Anime, COUNT(*) AS total_peliculas FROM peliculas where ID_Anime=:id_anime";
    $stmtCount = $conn->prepare($sqlCount);

    $stmtCount->execute([
        ':id_anime' => $id_anime
    ]);

    $stmtCount->execute();

    // Recuperar el resultado de la consulta
    $resultado = $stmtCount->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // Obtener el total de películas
        $totalPeliculas = $resultado['total_peliculas'];
        $idAnime = $resultado['ID_Anime'];

        // Actualizar el campo 'peliculas' en la tabla anime con el total de películas
        $sqlUpdate = "UPDATE anime SET peliculas = :totalPeliculas WHERE id = :idAnime";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':totalPeliculas' => $totalPeliculas,
            ':idAnime' => $idAnime
        ]);
    } 

    // Confirmar la transacción
    $conn->commit();
} catch (PDOException $e) {
    // Si ocurre un error, revertir la transacción
    $conn->rollBack();

    // Manejar el error
    $alertTitle = '¡Error!';
    $alertText = 'Error al actualizar anime: ' . $e->getMessage();
    $alertType = 'error';
    $redireccion = "window.location='javascript:history.back()'";

    alerta($alertTitle, $alertText, $alertType, $redireccion);
    exit(); // Detener la ejecución si hay error
}



mysqli_close($conexion);
?>