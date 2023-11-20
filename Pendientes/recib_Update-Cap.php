<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_REQUEST['id'];
$nombre         = $_REQUEST['nombre'];
$vistos         = $_REQUEST['vistos'];
$caps         = $_REQUEST['capitulos'];
$link    = $_REQUEST['link'];


$sql = ("SELECT * FROM `pendientes` WHERE Vistos < Total and Nombre='$nombre';");
$sql1 = ("SELECT (Total-Vistos) FROM `pendientes` where Nombre='$nombre';");

$emision      = mysqli_query($conexion, $sql);
$validar      = mysqli_query($conexion, $sql1);

echo $sql;
echo "<br>";
echo $sql1;

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
            $sql = "UPDATE pendientes SET Vistos ='" . $caps . "'+'" . $vistos . "' WHERE Nombre='" . $nombre . "' AND Vistos < Total;";
            $conn->exec($sql);
            $last_id1 = $conn->lastInsertId();
            echo $sql;
            echo "<br>";
            echo 'ultimo anime insertado ' . $last_id1;
            echo "<br>";
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando Capitulos de ' . $nombre . ' en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos)";
            $conn->exec($sql);
            $last_id2 = $conn->lastInsertId();
            echo $sql;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
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
