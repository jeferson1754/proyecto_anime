<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>
<?php
include('bd.php');

$idRegistros = $_REQUEST['id'];
$estado = $_REQUEST['estado'];
$nombre = $_REQUEST['nombre'];
$link = $_REQUEST['link'];

// Consultas de eliminación y actualización
switch ($estado) {
    case "Emision":
    case "Pausado":
        $sql_update = "INSERT INTO id_anime (`ID`) VALUES ('$idRegistros');";
        $sql_delete = "DELETE anime.*,emision.*
                       FROM anime JOIN emision ON anime.id = emision.ID_Anime
                       WHERE anime.id='$idRegistros'";
        break;

    case "Finalizado":
        $sql_update = "INSERT INTO id_anime (`ID`) VALUES ('$idRegistros');";
        $sql_delete = "DELETE FROM anime WHERE anime.id='$idRegistros'";
        break;

    case "Pendiente":
        $sql_update = "INSERT INTO id_anime (`ID`) VALUES ('$idRegistros');";
        $sql_delete = "DELETE anime.*,pendientes.*
                       FROM anime JOIN pendientes ON anime.id = pendientes.ID_Anime
                       WHERE anime.id='$idRegistros'";

        break;

    default:
        $sql_update = "INSERT INTO id_anime (`ID`) VALUES ('$idRegistros');";
        $sql_delete = "DELETE anime.*,emision.*
                       FROM anime JOIN emision ON anime.id = emision.ID_Anime
                       WHERE anime.id='$idRegistros'";
}

// Ejecutar las consultas
if (isset($sql_delete)) {
    $result_delete = mysqli_query($conexion, $sql_delete);
}
if (isset($sql_update)) {
    $result_update = mysqli_multi_query($conexion, $sql_update);
}

if (isset($mensaje)) {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando ' . $mensaje . '",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "../Anime 2.0/' . $link . '";
        });
        </script>';
} else {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "' . $nombre . ' Eliminado Exitosamente de Anime",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
}
?>