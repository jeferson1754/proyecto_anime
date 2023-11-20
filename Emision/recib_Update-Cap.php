<!--!-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_REQUEST['id'];
$nombre         = $_REQUEST['nombre'];
$vistos         = $_REQUEST['vistos'];
$caps           = $_REQUEST['capitulos'];
$accion         = $_REQUEST['accion'];
$link           = $_REQUEST['link'];

$sql = ("SELECT * FROM `emision` WHERE Capitulos < Totales and Nombre='$nombre';");
$sql1 = ("SELECT (Totales-Capitulos) FROM `emision` where Nombre='$nombre';");


$emision      = mysqli_query($conexion, $sql);
$validar      = mysqli_query($conexion, $sql1);



while ($rows = mysqli_fetch_array($validar)) {


    //UPDATE `emision` SET `Capitulos` = '1' WHERE `emision`.`ID` = 19;
    //UPDATE `emision` SET `Capitulos`=Capitulos+1 WHERE Nombre="Dragon Ball";
    /*
    echo $idRegistros;
    echo "<br>";
    echo $nombre;
    echo "<br>";
    echo $vistos;
    echo "<br>";
    echo $sql;
    echo "<br>";
    echo $sql1;
    echo "<br>";
    echo $caps;
    echo "<br>";

    */
    if ($vistos <= $rows[0]) {
        echo "capitulos permitidos: " . $rows[0] . "<br>";
        echo "Esta bien  ";
        echo "<br>";
        echo "" . $rows[0] . "<=" . $vistos . "";
        echo "<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE emision SET Capitulos ='" . $caps . "'+'" . $vistos . "' WHERE Nombre='" . $nombre . "' AND Capitulos < Totales;";
            $conn->exec($sql);
            $last_id1 = $conn->lastInsertId();
            echo $sql;
            echo "<br>";
            echo 'ultimo anime insertado ' . $last_id1;
            echo "<br>";
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando Capitulos  de ' . $nombre . ' en Emision",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } catch (PDOException $e) {
            $conn = null;
        }
    } else {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Los Capitulos Ingresados  de ' . $nombre . ' Superan el Total Permitido",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
        echo "capitulos permitidos: " . $rows[0] . "<br>";
        echo "" . $rows[0] . "<=" . $vistos . "";
        echo "<br>";
    }
}



//$result_update = mysqli_query($conexion, $update);

//header("location:index.php");
