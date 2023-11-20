<!---->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$fecha_actual = date('Y-m-d');

$idRegistros = $_REQUEST['id'];
$nombre         = $_REQUEST['nombre'];
$accion         = $_REQUEST['accion'];
$link         = $_REQUEST['link'];



$sql = ("SELECT * FROM emision WHERE ID_Emision='$idRegistros';");
echo $sql . "<br>";

$consulta      = mysqli_query($conexion, $sql);

$sql = ("SELECT * FROM emision WHERE ID_Emision='$idRegistros';");
echo $sql . "<br>";

$consulta      = mysqli_query($conexion, $sql);



//Saca la ultima fecha registrada
while ($mostrar = mysqli_fetch_array($consulta)) {

    $dato1 = $mostrar['ID_Emision'];
    $dato2 = $mostrar['Emision'];
    $dato3 = $mostrar['Nombre'];
    $dato4 = $mostrar['Capitulos'];
    $dato5 = $mostrar['Totales'];
    $dato6 = $mostrar['Dia'];
    $dato8 = $mostrar['Posicion'];
    $dato10 = $mostrar['Duracion'];
}

echo $dato1;
echo "<br>";
echo $dato2;
echo "<br>";
echo $dato3;
echo "<br>";
echo $dato4;
echo "<br>";
echo $dato5;
echo "<br>";
echo $dato6;
echo "<br>";
echo $dato8;
echo "<br>";
echo $dato10;
echo "<br>";
echo $idRegistros;
echo "<br>";


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE anime set estado='Finalizado',ID_Emision='1' where ID_Emision='" . $idRegistros . "'";
    $conn->exec($sql);
    $last_id1 = $conn->lastInsertId();
    echo $sql;
    echo 'ultimo anime insertado ' . $last_id1;
    echo "<br>";
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO eliminados_emision (`ID_Emision`,`Estado`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`,`Fecha`)
    VALUES ( '$dato1','$dato2','$dato3','$dato4','$dato5','$dato6','$dato10','$fecha_actual')";
    $conn->exec($sql);
    echo $sql;
    echo "<br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
}


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM `emision` 
    WHERE ID_Emision='" . $idRegistros . "'";
    $conn->exec($sql);
    $last_id2 = $conn->lastInsertId();
    echo $sql;
    echo 'ultimo anime insertado ' . $last_id2;
    echo "<br>";
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM eliminados_emision WHERE Fecha < DATE_SUB(NOW(), INTERVAL 3 MONTH);";
    $conn->exec($sql);
    echo $sql;
    echo "<br>";
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}




echo '<script>
Swal.fire({
    icon: "success",
    title: "Actualizando Estado de ' . $nombre . '  a Finalizado",
    confirmButtonText: "OK"
}).then(function() {
    window.location = "' . $link . '";
});
</script>';





//header("location:index.php");
?>