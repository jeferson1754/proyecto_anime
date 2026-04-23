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
$historia = $_REQUEST['historia'] ?? 0;
$id_anime    = $_REQUEST['anime'] ?? '';

if ($enlace == "" || $enlace == null) {
    $estado = "Faltante";
} else {
    $estado = "Correcto";
}



$sql2 = ("SELECT * FROM `pendientes`where Temporada='$nombre';");
echo $sql2 . "<br>";
$pendientes = mysqli_query($conexion, $sql2);


if (mysqli_num_rows($pendientes) == 0) {


    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO pendientes (`ID_Anime`,`Temporada`, `Tipo`, `Vistos`, `Total`,`Link`,`Estado_Link`, `orden_historia`) 
            VALUES ( '" . $id_anime . "','" . $nombre . "','" . $tipo . "','" . $caps . "','" . $total . "','" . $enlace . "','" . $estado . "','" . $historia . "')";
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
