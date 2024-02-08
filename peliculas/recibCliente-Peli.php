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

$sql = "SELECT * FROM `peliculas` WHERE Nombre='$nombre'";
$sql2 = "SELECT * FROM `pendientes` WHERE Nombre='$nombre'";
$peli = mysqli_query($conexion, $sql);
$pendientes = mysqli_query($conexion, $sql2);

if (mysqli_num_rows($peli) == 0) {
    if ($estado == "Finalizado") {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insertar registro en la tabla de películas
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`, `Nombre`, `Ano`, `Estado`, `ID_Pendientes`) 
                                  VALUES ('$id', '$nombre', '$fecha', '$estado', 1)";
            $conn->exec($sqlInsertPelicula);

            // Eliminar registro de la tabla de IDs de películas
            $sqlDeleteIdPelicula = "DELETE FROM `id_peliculas` WHERE `ID` = '$id'";
            $conn->exec($sqlDeleteIdPelicula);

            $conn = null;
            mostrarExito("Creando registro de $nombre en Películas");
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
    } elseif ($estado == "Pendiente") {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insertar registro en la tabla de pendientes
            $sqlInsertPendiente = "INSERT INTO `pendientes` (`Nombre`, `Tipo`, `Vistos`, `Total`) 
                                   VALUES ('$nombre', 'Pelicula', 0, 1)";
            $conn->exec($sqlInsertPendiente);
            $last_id1 = $conn->lastInsertId();

            // Insertar registro en la tabla de películas
            $sqlInsertPelicula = "INSERT INTO `peliculas` (`ID`, `Nombre`, `Ano`, `Estado`, `ID_Pendientes`) 
                                  VALUES ('$id', '$nombre', '$fecha', '$estado', '$last_id1')";
            $conn->exec($sqlInsertPelicula);

            // Actualizar campo Pendientes en la tabla de pendientes
            $sqlUpdatePendientes = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
            $conn->exec($sqlUpdatePendientes);

            // Eliminar registro de la tabla de IDs de películas
            $sqlDeleteIdPelicula = "DELETE FROM `id_peliculas` WHERE `ID` = '$id'";
            $conn->exec($sqlDeleteIdPelicula);

            $conn = null;
            mostrarExito("Creando registro de $nombre en Pendientes y Películas");
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
    }
} else {
    mostrarError("Registro de $nombre ya existe en Películas");
}

function mostrarExito($mensaje)
{
    echo '<script>
            Swal.fire({
                icon: "success",
                title: "' . $mensaje . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "./";
            });
        </script>';
}

function mostrarError($mensaje)
{
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "' . $mensaje . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "./";
            });
        </script>';
}
?>