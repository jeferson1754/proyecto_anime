<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['nombre'];
$link    = $_REQUEST['link'];

$sql = ("SELECT * FROM `anime` where id_Pendientes='$idRegistros';");

$anime = mysqli_query($conexion, $sql);

$sql1 = ("SELECT * FROM `peliculas` where ID_Pendientes='$idRegistros';");

$anime = mysqli_query($conexion, $sql);
$peli = mysqli_query($conexion, $sql1);

echo $sql;
echo "<br>";

if (mysqli_num_rows($anime) == 0) {

    if (mysqli_num_rows($peli) == 0) {

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `pendientes` 
            WHERE ID_Pendientes='" . $idRegistros . "'";
            $conn->exec($sql);
            $last_id2 = $conn->lastInsertId();
            echo $sql;
            echo 'ultimo anime insertado ' . $last_id2;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        echo '<script>
        Swal.fire({
        icon: "success",
        title: "Eliminando ' . $nombre . ' de Pendientes",
        confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    } else {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE peliculas set estado='Finalizado',ID_Pendientes='1' where ID_Pendientes='" . $idRegistros . "'";
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
            $sql = "DELETE FROM `pendientes` 
            WHERE ID_Pendientes='" . $idRegistros . "'";
            $conn->exec($sql);
            $last_id2 = $conn->lastInsertId();
            echo $sql;
            echo 'ultimo anime insertado ' . $last_id2;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }


        echo '<script>
        Swal.fire({
        icon: "success",
        title: "Actualizando Estado de ' . $nombre . ' a Finalizado",
        confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    }
} else {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE anime set estado='Finalizado',ID_Pendientes='1' where ID_Pendientes='" . $idRegistros . "'";
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
        $sql = "DELETE FROM `pendientes` 
        WHERE ID_Pendientes='" . $idRegistros . "'";
        $conn->exec($sql);
        $last_id2 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id2;
        echo "<br>";
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }


    echo '<script>
    Swal.fire({
    icon: "success",
    title: "Actualizando Estado de ' . $nombre . ' a Finalizado",
    confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';
}






//header("location:index.php");
?>