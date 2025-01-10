<!-- Comentarios -->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$idRegistros = $_REQUEST['id'];
$estado = $_REQUEST['estado'];

echo $estado;

// Establecer la conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $password, $basededatos);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Preparar las consultas
$deleteQueryPendientes = "DELETE peliculas.*, pendientes.*
                          FROM peliculas
                          JOIN pendientes ON peliculas.ID_Pendientes = pendientes.ID_Pendientes
                          WHERE peliculas.ID = '$idRegistros'";

$deleteQueryPeliculas = "DELETE FROM peliculas WHERE ID = '$idRegistros'";

// Consulta para insertar en la tabla id_peliculas
$insertQuery = "INSERT INTO id_peliculas (`ID`) VALUES ('$idRegistros')";

// Ejecutar las consultas según el estado
if ($estado == "Finalizado") {
    // Ejecutar las consultas para "Finalizado"
    if (mysqli_query($conexion, $insertQuery) && mysqli_query($conexion, $deleteQueryPeliculas)) {
        mostrarExito("Eliminado de Película");
    } else {
        mostrarError("Error al eliminar de Película");
    }
} elseif ($estado == "Pendiente") {
    // Ejecutar las consultas para "Pendiente"
    if (mysqli_query($conexion, $insertQuery) && mysqli_query($conexion, $deleteQueryPendientes)) {
        mostrarExito("Eliminado de Película y Pendiente");
    } else {
        mostrarError("Error al eliminar de Película y Pendiente");
    }
}

// Función para mostrar mensajes de éxito
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

// Función para mostrar mensajes de error
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

mysqli_close($conexion);
?>