<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$id = $_REQUEST['id'];
$nombre = $_REQUEST['nombre'];
$estado = $_REQUEST['estado'];
$fecha = $_REQUEST['fecha'];
$id_anime = $_REQUEST['anime'] ?? NULL;

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

$sql = "SELECT * FROM `peliculas` WHERE Nombre='$nombre'";
$peli = mysqli_query($conexion, $sql);

if (mysqli_num_rows($peli) == 0) {
    if ($estado == "Finalizado") {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insertar registro en la tabla de películas
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`,`ID_Anime`,  `Nombre`, `Ano`, `Estado`, `ID_Pendientes`) 
                                  VALUES ('$id','$id_anime', '$nombre', '$fecha', '$estado', 1)";
            $conn->exec($sqlInsertPelicula);

            // Eliminar registro de la tabla de IDs de películas
            $sqlDeleteIdPelicula = "DELETE FROM `id_peliculas` WHERE `ID` = '$id'";
            $conn->exec($sqlDeleteIdPelicula);

            $conn = null;
            $alertTitle = '¡Pelicula Creada!';
            $alertText = 'Creando registro de ' . $nombre . ' en Películas';
            $alertType = 'success';
            $redireccion = "window.location='./'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
        } catch (PDOException $e) {
            $conn = null;

            $alertTitle = '¡Error!';
            $alertText = 'Error: ' . $e;
            $alertType = 'error';
            $redireccion = "window.location='javascript:history.back()'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
            die();
        }
    } elseif ($estado == "Pendiente") {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insertar registro en la tabla de pendientes
            $sqlInsertPendiente = "INSERT INTO `pendientes` (`ID_Anime`, `Temporada`, `Tipo`, `Vistos`, `Total`) 
                                   VALUES ('$id_anime','$nombre', 'Pelicula', 0, 1)";
            $conn->exec($sqlInsertPendiente);
            $last_id1 = $conn->lastInsertId();

            // Insertar registro en la tabla de películas
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`,`ID_Anime`, `Nombre`, `Ano`, `Estado`, `ID_Pendientes`) 
                                  VALUES ('$id', '$id_anime','$nombre', '$fecha', '$estado', '$last_id1')";
            $conn->exec($sqlInsertPelicula);

            // Actualizar campo Pendientes en la tabla de pendientes
            $sqlUpdatePendientes = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
            $conn->exec($sqlUpdatePendientes);

            // Eliminar registro de la tabla de IDs de películas
            $sqlDeleteIdPelicula = "DELETE FROM `id_peliculas` WHERE `ID` = '$id'";
            $conn->exec($sqlDeleteIdPelicula);

            $conn = null;
            $alertTitle = '¡Pelicula Creada!';
            $alertText = 'Creando registro de ' . $nombre . ' en Películas y en Pendintes';
            $alertType = 'success';
            $redireccion = "window.location='./'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
        } catch (PDOException $e) {
            $conn = null;
            $alertTitle = '¡Error!';
            $alertText = 'Error: ' . $e;
            $alertType = 'error';
            $redireccion = "window.location='javascript:history.back()'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
            die();
        }
    }
} else {

    $alertTitle = '¡Pelicula Repetida!';
    $alertText = 'Pelicula ' . $nombre . ' ya existe en Peliculas';
    $alertType = 'error';
    $redireccion = "window.location='javascript:history.back()'";

    alerta($alertTitle, $alertText, $alertType, $redireccion);
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


?>