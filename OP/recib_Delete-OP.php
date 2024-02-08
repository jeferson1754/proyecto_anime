<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['anime'];
$op = $_REQUEST['op'];
$link = $_REQUEST['link'];

// Consulta para eliminar el registro de la base de datos
$delete = "DELETE FROM op WHERE `ID` = '$idRegistros'";

// Ejecutar la consulta de eliminación
$result_update = mysqli_query($conexion, $delete);

// Mostrar mensaje de éxito con SweetAlert
echo '<script>
    Swal.fire({
        icon: "success",
        title: "Eliminando OP ' . $op . ' de ' . $nombre . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
</script>';

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>









//header("location:index.php");
?>