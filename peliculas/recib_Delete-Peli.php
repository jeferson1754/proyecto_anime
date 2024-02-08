<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros = $_REQUEST['id'];
$estado = $_REQUEST['estado'];
echo $estado;

$deleteQueryPendientes = "DELETE peliculas.*, pendientes.*
                FROM peliculas
                JOIN pendientes ON peliculas.ID_Pendientes = pendientes.ID_Pendientes
                WHERE peliculas.ID = '$idRegistros'";

$deleteQueryPeliculas = "DELETE FROM peliculas 
                         WHERE peliculas.ID = '$idRegistros'";

$insertQuery = "INSERT INTO id_peliculas (`ID`) VALUES ('$idRegistros')";

if ($estado == "Finalizado") {
    $result_update = mysqli_query($conexion, $insertQuery);
    $result_update = mysqli_query($conexion, $deleteQueryPeliculas);
    mostrarExito("Eliminado de Película");
} elseif ($estado == "Pendiente") {
    $result_update = mysqli_query($conexion, $insertQuery);
    $result_update = mysqli_query($conexion, $deleteQueryPendientes);
    mostrarExito("Eliminado de Película y Pendiente");
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

$conexion = null;
?>