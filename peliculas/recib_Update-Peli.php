<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>


<?php
include '../bd.php';

$idRegistros = $_POST['id'];
$idPendientes = $_POST['pendi'] ?? '1';
$id_anime = $_POST['anime'] ?? NULL;
$nombre_anime = $_POST['nombre_aviso'];
$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$fecha = $_POST['fecha'];
$link           = $_POST['enlace'];
$estado_link           = $_POST['estado_link'];

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

function conectarPDO($servidor, $basededatos, $usuario, $password)
{
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}


if ($estado == "Viendo") {

    // 1. Obtener el total actual de animes pendientes/viendo
    $sql = "SELECT COUNT(*) FROM peliculas WHERE Estado IN ('Pendiente','Viendo')";
    $result = mysqli_query($conexion, $sql);
    $fila = mysqli_fetch_row($result);
    $total_actual = (int) $fila[0];

    // 2. Consultar el ÚLTIMO valor insertado en la tabla de historial
    $stmt_check = $connect->prepare("
        SELECT total_anterior 
        FROM estadisticas_historial 
        WHERE categoria = 'Películas' 
        ORDER BY fecha_actualizacion DESC LIMIT 1
    ");
    $stmt_check->execute();
    $ultimo_registro = $stmt_check->fetchColumn();

    // 3. Insertar una NUEVA FILA solo si el valor cambió o si no hay registros previos
    if ($ultimo_registro === false || $total_actual != $ultimo_registro) {
        $stmt = $connect->prepare("
            INSERT INTO estadisticas_historial (categoria, total_anterior, fecha_actualizacion)
            VALUES ('Peliculas', ?, NOW())
        ");
        $stmt->execute([$total_actual]);
    }
} else {
    echo "El estado no es viendo";
    echo "<br>";
}


try {
    // Conexión PDO para todas las consultas
    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

    // Consultas necesarias
    $sqlPelicula = "SELECT * FROM `peliculas` WHERE ID='$idRegistros'";


    $resultadoPelicula = $conn->query($sqlPelicula);


    if ($resultadoPelicula->rowCount() == 0) {
        $alertTitle = '¡Pelicula No Existe!';
        $alertText = 'No se puede editar porque ' . $nombre_anime . ' no existe en Peliculas';
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die();
    } else {
        echo "Existe en Peliculas<br>";

        echo $nombre . "<br>";
        echo $fecha . "<br>";
        echo $id_anime . "<br>";

        // Lógica de estado 'Finalizado'


        try {
            // Conexión PDO para la actualización
            $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

            // Iniciar una transacción
            $conn->beginTransaction();

            $sql = "UPDATE peliculas SET 
                    Nombre = :nombre,
                    Ano = :fecha,
                    ID_Anime = :id_anime,
                    Estado = :estado,
                    Link = :link,
                    Estado_Link = :estado_link,
                    ID_Pendientes = :idPendientes
                    WHERE ID = :idRegistros";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':fecha' => $fecha,
                ':estado' => $estado,
                ':link' => $link,
                ':estado_link' => $estado_link,
                ':idRegistros' => $idRegistros,
                ':id_anime' => $id_anime,
                ':idPendientes' => $idPendientes
            ]);
            // Confirmación
            $conn->commit();  // Confirmar los cambios

            // Conexión PDO para la eliminación
            $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

            // Iniciar una transacción
            $conn->beginTransaction();
            // Actualiza el campo de pendientes
            $sqlUpdate = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
            $conn->exec($sqlUpdate);
            // Confirmación
            $conn->commit();  // Confirmar los cambios

            $alertTitle = '¡Pelicula Actualizada!';
            $alertText = 'Actualizando registro de ' . $nombre_anime . ' en Películas';
            $alertType = 'success';
            $redireccion = "window.location='./'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
        } catch (PDOException $e) {
            // Si ocurre un error, revertir la transacción
            $conn->rollBack();

            $alertTitle = '¡Error!';
            $alertText = 'Error al actualizar la pelicula: ' . $e->getMessage();
            $alertType = 'error';
            $redireccion = "window.location='javascript:history.back()'";

            alerta($alertTitle, $alertText, $alertType, $redireccion);
            die();
        }
    }


    try {
        // Conexión PDO
        $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

        // Iniciar una transacción
        $conn->beginTransaction();

        // Consulta para actualizar el campo peliculas en la tabla anime con el total de películas
        $sqlUpdate = "
                UPDATE anime a
                JOIN (
                    SELECT ID_Anime, COUNT(*) AS total_peliculas 
                    FROM peliculas 
                    GROUP BY ID_Anime
                ) p ON a.id = p.ID_Anime
                SET a.peliculas = p.total_peliculas
            ";

        // Ejecutar la consulta
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute();

        // Confirmar la transacción
        $conn->commit();
    } catch (PDOException $e) {
        // Si ocurre un error, revertir la transacción
        $conn->rollBack();

        // Manejar el error
        $alertTitle = '¡Error!';
        $alertText = 'Error al actualizar peliculas masivamente: ' . $e->getMessage();
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        exit(); // Detener la ejecución si hay error
    }
} catch (PDOException $e) {

    $alertTitle = '¡Error!';
    $alertText = 'Error al procesar la operación: ' . $e->getMessage();
    $alertType = 'error';
    $redireccion = "window.location='javascript:history.back()'";

    alerta($alertTitle, $alertText, $alertType, $redireccion);
    die();
}


?>