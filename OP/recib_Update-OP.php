<!--coment-->

<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_REQUEST['id'];
$idAnime        = $_REQUEST['anime'];
$cancion        = $_REQUEST['cancion'];
$enlace         = $_REQUEST['enlace'];
$iframe         = $_REQUEST['iframe'];
$op             = $_REQUEST['op'];
$estado         = $_REQUEST['estado'];
$mix            = $_REQUEST['mix'];
$nombre         = $_REQUEST['nombre'];
$temp           = $_REQUEST['temp'];
$ano            = $_REQUEST['ano'];
$estado_link    = $_REQUEST['estado_link'];
$link           = $_REQUEST['link'];
$autor          = $_REQUEST['autor'];


if (isset($_REQUEST["ocultar"])) {
    echo "Ocultar_Verdadero";
    echo "<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE `op` SET `mostrar` = 'NO' WHERE `op`.`ID` = $idRegistros;";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
} else {
    echo "Ocultar_Falso";
    echo "<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE `op` SET `mostrar` = 'SI' WHERE `op`.`ID` = $idRegistros;";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
}

echo "Temporada: " . $temp;
echo "<br>";
echo "Opening " . $op;
echo "<br>";
echo $estado;
echo "<br>";
echo $nombre;
echo "<br>";
echo $estado_link;
echo "<br>";
echo $autor;
echo "<br>";


$tempor = "";

if ($temp == "Invierno") {
    $tempor = "1";
} else if ($temp == "Primavera") {
    $tempor = "2";
} else if ($temp == "Verano") {
    $tempor = "3";
} else if ($temp == "Otoño") {
    $tempor = "4";
} else if($temp =="Desconocida"){
     $tempor = "5";
}else{
    $tempor=$temp;
}

echo $tempor . "<br>";
echo "openings.php?nombre= <br>";
echo $link . "<br>";

$opening = $conexion->query("SELECT * FROM `autor` where Autor='$autor';");

while ($valores = mysqli_fetch_array($opening)) {
    $autor1 = $valores['Autor'];
    $id_autores = $valores['ID'];
    echo $autor1 . "<br>";
}

if ($autor == $autor1) {
    $autores = $id_autores;
} else {

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `autor` (`ID`, `Autor`)
 VALUES ( NULL ,'" . $autor . "')";
        $conn->exec($sql);
        $autores = $conn->lastInsertId();
        echo $sql . "<br>";
        echo 'ID_Autor: ' . $autores;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
    echo $sql;
}



try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE op SET
            Cancion ='" . $cancion . "',
            Link ='" . $enlace . "',
            Link_Iframe ='" . $iframe . "',
            Estado ='" . $estado . "',
            ID_Anime ='" . $idAnime . "',
            Temporada ='" . $tempor . "',
            Estado_Link ='" . $estado_link . "',
            Ano ='" . $ano . "',
            ID_Autor ='" . $autores . "',
            Mix ='" . $mix . "'
            WHERE ID='" . $idRegistros . "'";
    $conn->exec($sql);
    echo $sql;
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $sql;
    echo $e;
}



echo '<script>
    Swal.fire({
        icon: "success",
        title: "Actualizando OP ' . $op . ' de ' . $nombre . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';
