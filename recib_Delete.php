<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>
<?php
include('bd.php');
$idRegistros  = $_REQUEST['id'];
$estado       = $_REQUEST['estado'];
$nombre       = $_REQUEST['nombre'];
$link         = $_REQUEST['link'];


$delete1 = ("DELETE anime.*,emision.*
FROM anime JOIN emision ON anime.id_Emision=emision.ID_Emision
WHERE anime.id='" . $idRegistros . "'
");

$delete2 = ("DELETE anime.*,pendientes.*
FROM anime JOIN pendientes ON anime.id_Pendientes=pendientes.ID_Pendientes
WHERE anime.id='" . $idRegistros . "'
");

$delete3 = ("DELETE FROM anime 
WHERE `anime`.`id` = '" . $idRegistros . "'
");

$update = ("INSERT INTO id_anime (`ID`) VALUES
('" . $idRegistros . "');
");


$update2 = ("UPDATE anime SET id_Emision=NULL,id_Pendientes=NULL,Id_Temporada=NULL where 
id='" . $idRegistros . "';
");

$update3 = ("UPDATE op SET ID_Anime=NULL where 
ID_Anime='" . $idRegistros . "';
");

$update4 = ("UPDATE ed SET ID_Anime=NULL where 
ID_Anime='" . $idRegistros . "';
");


if ($estado === "Emision") {

    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete1);
    $conexion = null;
} else if ($estado === "Finalizado") {

    echo $delete3;
    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete3);

    $conexion = null;
} else if ($estado === "Pausado") {

    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete1);
    $conexion = null;
} else if ($estado === "Pendiente") {

    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $delete2);
    $conexion = null;
} else {
    $result_update = mysqli_query($conexion, $update);
    $result_update = mysqli_query($conexion, $update2);
    $result_update = mysqli_query($conexion, $update3);
    $result_update = mysqli_query($conexion, $update4);
    $result_update = mysqli_query($conexion, $delete1);
}


echo "<br>";
echo $estado;
echo "<br>";
echo $nombre;


$link         = $_REQUEST['link'];

echo "<br>";
echo $link;

if (isset($_POST['accion'])) {

    if ($_POST['accion'] == "nuevo_mix") {



        $sql = ("INSERT INTO `mix` (`ID`) VALUES (NULL);");
        echo $sql;
        $result_update = mysqli_query($conexion, $sql);
        $conexion = null;

        echo '<script>
    Swal.fire({
        icon: "success",
        title: "Creando Nuevo Mix en Openings",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "../Anime 2.0/' . $link . '";
    });
    </script>';
    } else {
        $sql = ("INSERT INTO `mix_ed` (`ID`) VALUES (NULL);");
        echo $sql;
        $result_update = mysqli_query($conexion, $sql);
        $conexion = null;

        echo '<script>
    Swal.fire({
        icon: "success",
        title: "Creando Nuevo Mix en Endings",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "../Anime 2.0/' . $link . '";
    });
    </script>';
    }
} else {
    echo "No Funciona";
    //header("location:$link");
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