<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$nombre  = $_REQUEST['nombre'];
$caps    = $_REQUEST['caps'];
$total   = $_REQUEST['total'];
$enlace  = $_REQUEST['enlace'];
$link    = $_REQUEST['link'];
$tipo    = $_REQUEST['tipo'];

if ($enlace == "") {
    $estado = "Faltante";
} else {
    $estado = "Correcto";
}



$sql2 = ("SELECT * FROM `pendientes`where Nombre='$nombre';");
echo $sql2 . "<br>";
$pendientes = mysqli_query($conexion, $sql2);


if (mysqli_num_rows($pendientes) == 0) {


    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO pendientes (`Nombre`, `Tipo`, `Vistos`, `Total`,`Link`,`Estado_Link`) 
            VALUES ( '" . $nombre . "','" . $tipo . "','" . $caps . "','" . $total . "','" . $enlace . "','" . $estado . "')";
        $conn->exec($sql);
        $last_id1 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id1;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre . ' en Pendiente",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';

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
        title: "Registro de ' . $nombre . ' Existe en Pendiente",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';

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
}
