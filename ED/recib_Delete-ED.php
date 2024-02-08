<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

// Obtener los datos del formulario
$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['anime'];
$ed = $_REQUEST['ed'];
$link = $_REQUEST['link'];

// Preparar la consulta para eliminar el registro de la base de datos
$sql = "DELETE FROM ed WHERE ID = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$idRegistros]);

// Mensaje de éxito con SweetAlert
echo '<script>
Swal.fire({
    icon: "success",
    title: "Eliminando ED ' . $ed . ' de ' . $nombre . '",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "' . $link . '";
});
</script>';

// Cerrar la conexión
$conexion = null;
?>










//header("location:index.php");
?>