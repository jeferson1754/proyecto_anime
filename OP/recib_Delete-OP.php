<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['anime'];
$op = $_REQUEST['op'];
$link           = $_REQUEST['link'];


$delete = ("DELETE FROM op WHERE `op`.`ID` = '" . $idRegistros . "';");

$result_update = mysqli_query($conexion, $delete);
echo '<script>
Swal.fire({
    icon: "success",
    title: "Eliminando OP ' . $op . ' de ' . $nombre . '",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "' . $link . '";
});
</script>';

$conexion = null;








//header("location:index.php");
?>