<!---->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_POST['id'];
$link           = $_REQUEST['link'];
$nombre         = $_REQUEST['nombre'];

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM `calificaciones` WHERE ID='".$idRegistros."'";
    $conn->exec($sql);
    //echo $sql;
    echo "<br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $e;
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "La calificacion de ' . $nombre . 'no se puede eliminar",
                confirmButtonText: "OK"

            }).then(function() {
                window.location = "' . $link . '"; 
            });
            </script>';
}

echo '<script>
            Swal.fire({
                icon: "success",
                title: "Se elimino la Calificacion ' . $nombre . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location =  "' . $link . '"; 
            });
            </script>';
