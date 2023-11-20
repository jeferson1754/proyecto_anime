<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros = $_REQUEST['id'];
$estado = $_REQUEST['estado'];
echo $estado;

$delete1 = ("DELETE peliculas.*,pendientes.*
FROM peliculas JOIN pendientes ON peliculas.ID_Pendientes=pendientes.ID_Pendientes
WHERE peliculas.ID='" . $idRegistros . "'
");

$delete2 = ("DELETE FROM peliculas 
WHERE `peliculas`.`ID` = '" . $idRegistros . "'
");

$update = ("INSERT INTO id_peliculas (`ID`) VALUES
('" . $idRegistros . "');
");

if ($estado == "Finalizado") {
    //echo $update;

    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete2);
    echo '<script>
Swal.fire({
    icon: "success",
    title: "Eliminado de Pelicula",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "../peliculas.php";
});
</script>';
    $conexion = null;
} else if ($estado == "Pendiente") {
    //echo $update;
    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete1);
    echo '<script>
Swal.fire({
    icon: "success",
    title: "Eliminado de Pelicula y Pendiente",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "../peliculas.php";
});
</script>';
    $conexion = null;
}














//header("location:index.php");
?>