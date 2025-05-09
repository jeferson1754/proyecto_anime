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


try {
    // Conexión PDO para todas las consultas
    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

    // Consultas necesarias
    $sqlPelicula = "SELECT * FROM `peliculas` WHERE ID='$idRegistros'";
    $sqlPendientes = "SELECT * FROM `pendientes` WHERE ID='$idPendientes' AND ID > 1";

    $resultadoPelicula = $conn->query($sqlPelicula);
    $resultadoPendientes = $conn->query($sqlPendientes);

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
        if ($estado == "Finalizado") {
            if ($resultadoPendientes->rowCount() == 0) {

                try {
                    // Conexión PDO para la actualización
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "UPDATE peliculas SET 
                    Nombre = :nombre,
                    Ano = :fecha,
                    ID_Anime = :id_anime,
                    Estado = :estado ,
                    ID_Pendientes = :idPendientes
                    WHERE ID = :idRegistros";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':fecha' => $fecha,
                        ':estado' => $estado,
                        ':idRegistros' => $idRegistros,
                        ':id_anime' => $id_anime,
                        ':idPendientes' => $idPendientes
                    ]);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                    $alertTitle = '¡Pelicula Actualizada!';
                    $alertText = 'Actualizando registro de ' . $nombre_anime . ' en Películas';
                    $alertType = 'success';
                    $redireccion = "window.location='./'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    die();
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
            } else {
                try {
                    // Conexión PDO para la actualización
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "UPDATE peliculas SET 
                        Nombre = :nombre,
                        Ano = :fecha,
                        ID_Anime = :id_anime,
                        Estado = :estado ,
                        ID_Pendientes = :idPendientes
                        WHERE ID = :idRegistros";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':fecha' => $fecha,
                        ':estado' => $estado,
                        ':idRegistros' => $idRegistros,
                        ':id_anime' => $id_anime,
                        ':idPendientes' => 1
                    ]);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al actualizar la pelicula y eliminar el pendiente: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    // Aquí no es necesario el `die()` porque ya estamos redirigiendo al usuario
                    exit();
                }

                try {
                    // Conexión PDO para la eliminación
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "DELETE FROM pendientes WHERE ID = :idPendientes";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':idPendientes' => $idPendientes
                    ]);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al eliminar el pendiente: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    exit(); // Evitar continuar si hay un error
                }

                // Si ambos bloques son exitosos, mostrar éxito
                $alertTitle = '¡Pelicula Actualizada!';
                $alertText = 'Actualizando registro de ' . $nombre_anime . ' en Películas y Eliminando en Pendientes';
                $alertType = 'success';
                $redireccion = "window.location='./'";

                alerta($alertTitle, $alertText, $alertType, $redireccion);
            }
        }
        // Lógica de estado 'Pendiente'
        else if ($estado == "Pendiente" or $estado == "Viendo") {
            if ($resultadoPendientes->rowCount() == 0) {
                try {
                    // Conexión PDO para la eliminación
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "INSERT INTO pendientes (ID_Anime, Temporada, Tipo, Vistos, Total) VALUES (:id_anime, :nombre, 'Pelicula', 0, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id_anime' => $id_anime, ':nombre' => $nombre]);

                    $id_new_pendientes = $conn->lastInsertId();

                    // Actualiza el campo de pendientes
                    $sqlUpdate = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
                    $conn->exec($sqlUpdate);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al insertar el pendiente: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    exit(); // Evitar continuar si hay un error
                }

                try {
                    // Conexión PDO para la actualización
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "UPDATE peliculas SET 
                        Nombre = :nombre,
                        Ano = :fecha,
                        ID_Anime = :id_anime,
                        Estado = :estado ,
                        ID_Pendientes = :idPendientes
                        WHERE ID = :idRegistros";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':fecha' => $fecha,
                        ':estado' => $estado,
                        ':idRegistros' => $idRegistros,
                        ':id_anime' => $id_anime,
                        ':idPendientes' => $id_new_pendientes
                    ]);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al actualizar la pelicula y insertar el pendiente: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    // Aquí no es necesario el `die()` porque ya estamos redirigiendo al usuario
                    exit();
                }


                $alertTitle = '¡Pelicula Actualizada!';
                $alertText = 'Actualizando registro de ' . $nombre_anime . ' en Películas y Creando en Pendientes';
                $alertType = 'success';
                $redireccion = "window.location='./'";

                alerta($alertTitle, $alertText, $alertType, $redireccion);
            } else {

                try {
                    // Conexión PDO para la actualización
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "UPDATE pendientes SET 
                    Temporada = :nombre,
                    ID_Anime = :id_anime,
                    Vistos = 0,
                    Total = 1,
                    Tipo = 'Pelicula'
                    WHERE ID = :idPendientes";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':nombre' => $nombre, ':id_anime' => $id_anime, ':idPendientes' => $idPendientes]);

                    // Actualiza el campo de pendientes
                    $sqlUpdate = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
                    $conn->exec($sqlUpdate);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al actualizar el pendiente y pelicula: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    // Aquí no es necesario el `die()` porque ya estamos redirigiendo al usuario
                    exit();
                }

                try {
                    // Conexión PDO para la actualización
                    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

                    // Iniciar una transacción
                    $conn->beginTransaction();

                    $sql = "UPDATE peliculas SET 
                        Nombre = :nombre,
                        Ano = :fecha,
                        ID_Anime = :id_anime,
                        Estado = :estado ,
                        ID_Pendientes = :idPendientes
                        WHERE ID = :idRegistros";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':fecha' => $fecha,
                        ':estado' => $estado,
                        ':idRegistros' => $idRegistros,
                        ':id_anime' => $id_anime,
                        ':idPendientes' => $idPendientes
                    ]);
                    // Confirmación
                    $conn->commit();  // Confirmar los cambios

                } catch (PDOException $e) {
                    // Si ocurre un error, revertir la transacción
                    $conn->rollBack();

                    $alertTitle = '¡Error!';
                    $alertText = 'Error al actualizar la pelicula y el pendiente: ' . $e->getMessage();
                    $alertType = 'error';
                    $redireccion = "window.location='javascript:history.back()'";

                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    // Aquí no es necesario el `die()` porque ya estamos redirigiendo al usuario
                    exit();
                }

                $alertTitle = '¡Pelicula Actualizada!';
                $alertText = 'Actualizando registro de ' . $nombre_anime . ' en Películas y en Pendientes';
                $alertType = 'success';
                $redireccion = "window.location='./'";

                alerta($alertTitle, $alertText, $alertType, $redireccion);
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