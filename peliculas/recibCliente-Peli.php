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
$enlace  = $_REQUEST['enlace'];


if ($enlace == "" || $enlace == null) {
    $estado_link = "Faltante";
} else {
    $estado_link = "Correcto";
}


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
try {
    // 1. Una sola conexión PDO para todo el proceso
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Verificar si la película ya existe usando PDO
    $stmtCheck = $conn->prepare("SELECT * FROM `peliculas` WHERE Nombre = :nombre and ID_Anime = :id_anime");
    $stmtCheck->execute([':nombre' => $nombre, ':id_anime' => $id_anime]);

    if ($stmtCheck->rowCount() == 0) {

        if ($estado == "Finalizado") {
            // NOTA: Cambié el '1' por :id_pendiente. Pasa la variable real correspondiente.
            // Si no aplica, recuerda cambiarlo a NULL en SQL y configurar la columna como NULLABLE.
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`,`ID_Anime`, `Nombre`, `Ano`, `Estado`, `ID_Pendientes`, `Link`, `Estado_Link`) 
                                  VALUES (:id, :id_anime, :nombre, :fecha, :estado, :id_pendiente, :enlace, :estado_link)";
            $stmtInsert = $conn->prepare($sqlInsertPelicula);
            $stmtInsert->execute([
                ':id' => $id,
                ':id_anime' => $id_anime,
                ':nombre' => $nombre,
                ':fecha' => $fecha,
                ':estado' => $estado,
                ':id_pendiente' => 1, // <-- Reemplaza por la variable real si es necesario
                ':enlace' => $enlace,
                ':estado_link' => $estado_link
            ]);

            // Eliminar de id_peliculas
            $stmtDeleteId = $conn->prepare("DELETE FROM `id_peliculas` WHERE `ID` = :id");
            $stmtDeleteId->execute([':id' => $id]);

            $alertTitle = '¡Pelicula Creada!';
            $alertText = 'Creando registro de ' . $nombre . ' en Películas';
            $alertType = 'success';
            $redireccion = "window.location='./'";
            alerta($alertTitle, $alertText, $alertType, $redireccion);
        } elseif ($estado == "Pendiente") {

            // Insertar película
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`,`ID_Anime`, `Nombre`, `Ano`, `Estado`, `ID_Pendientes`, `Link`, `Estado_Link`) 
                                  VALUES (:id, :id_anime, :nombre, :fecha, :estado, :id_pendiente, :enlace, :estado_link)";
            $stmtInsert = $conn->prepare($sqlInsertPelicula);
            $stmtInsert->execute([
                ':id' => $id,
                ':id_anime' => $id_anime,
                ':nombre' => $nombre,
                ':fecha' => $fecha,
                ':estado' => $estado,
                ':id_pendiente' => 1, // <-- Reemplaza por la variable real si es necesario
                ':enlace' => $enlace,
                ':estado_link' => $estado_link
            ]);

            // CORRECCIÓN: Se eliminaron las comillas simples de :estado
            $sqlUpdatePendientes = "UPDATE anime SET Estado = :estado WHERE ID = :id_anime";
            $stmtUpdatePen = $conn->prepare($sqlUpdatePendientes);

            // Ahora PDO reemplazará :estado por 'Pendiente' de forma segura y correcta
            $stmtUpdatePen->execute([
                ':estado'   => 'Pendiente',
                ':id_anime' => $id_anime
            ]);

            // Eliminar de id_peliculas
            $stmtDeleteId = $conn->prepare("DELETE FROM `id_peliculas` WHERE `ID` = :id");
            $stmtDeleteId->execute([':id' => $id]);

            $alertTitle = '¡Pelicula Creada!';
            $alertText = 'Creando registro de ' . $nombre . ' en Películas';
            $alertType = 'success';
            $redireccion = "window.location='./'";
            alerta($alertTitle, $alertText, $alertType, $redireccion);
        }
    } else {
        // Película repetida
        $alertTitle = '¡Pelicula Repetida!';
        $alertText = 'Pelicula ' . $nombre . ' ya existe en Peliculas';
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";
        alerta($alertTitle, $alertText, $alertType, $redireccion);
    }
} catch (PDOException $e) {
    // Si algo falla, el catch captura cualquier error de las consultas internas
    $alertTitle = '¡Error!';
    $alertText = 'Error en el proceso: ' . $e->getMessage();
    $alertType = 'error';
    $redireccion = "window.location='javascript:history.back()'";
    alerta($alertTitle, $alertText, $alertType, $redireccion);
    die();
} finally {
    $conn = null; // Cerrar la conexión de forma segura siempre
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