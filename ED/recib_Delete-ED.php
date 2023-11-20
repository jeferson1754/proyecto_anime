<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_REQUEST['id'];
$nombre         = $_REQUEST['anime'];
$ed             = $_REQUEST['ed'];
$link           = $_REQUEST['link'];

$delete = ("DELETE FROM ed WHERE `ed`.`ID` = '" . $idRegistros . "';");

$result_update = mysqli_query($conexion, $delete);
echo '<script>
Swal.fire({
    icon: "success",
    title: "Eliminando ED ' . $ed . ' de ' . $nombre . '",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "' . $link . '";
});
</script>';

$conexion = null;








//header("location:index.php");
?>