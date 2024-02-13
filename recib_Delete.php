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
        $sql_delete = "DELETE anime.*,emision.*
                       FROM anime JOIN emision ON anime.id_Emision=emision.ID_Emision
                       WHERE anime.id='$idRegistros'";
        break;

    case "Finalizado":
        $sql_delete = "DELETE FROM anime WHERE anime.id='$idRegistros'";
        break;

    case "Pendiente":
        $sql_delete = "DELETE anime.*,pendientes.*
                       FROM anime JOIN pendientes ON anime.id_Pendientes=pendientes.ID_Pendientes
                       WHERE anime.id='$idRegistros'";
        break;

    default:
        $sql_update = "INSERT INTO id_anime (`ID`) VALUES ('$idRegistros')";
        $sql_update .= "; UPDATE anime SET id_Emision=NULL,id_Pendientes=NULL,Id_Temporada=NULL WHERE id='$idRegistros'";
        $sql_delete = "DELETE anime.*,emision.*
                       FROM anime JOIN emision ON anime.id_Emision=emision.ID_Emision
                       WHERE anime.id='$idRegistros'";
}

// Ejecutar las consultas
if (isset($sql_delete)) {
    $result_delete = mysqli_query($conexion, $sql_delete);
}
if (isset($sql_update)) {
    $result_update = mysqli_multi_query($conexion, $sql_update);
}

// Redireccionar
if (isset($_POST['accion'])) {
    if ($_POST['accion'] == "nuevo_mix") {
        $table = ($_POST['accion'] == "nuevo_mix") ? "mix" : "mix_ed";
        $sql_insert = "INSERT INTO `$table` (`ID`) VALUES (NULL)";
        mysqli_query($conexion, $sql_insert);
        $mensaje = ($_POST['accion'] == "nuevo_mix") ? "Nuevo Mix en Openings" : "Nuevo Mix en Endings";
    }
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

