<!--comment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

$ID         = $_POST['id'];
$nombre         = $_POST['nombre'];
$dias           = $_POST['dias'];
$duracion       = $_POST['duracion'];
$link           = $_POST['link'];


echo $ID . "<br>";
echo $nombre . "<br>";
echo $dias . "<br>";
echo $duracion . "<br>";
echo $link . "<br>";


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `horario` SET `Nombre`='$nombre',`Dia`='$dias',`Duracion`='$duracion' WHERE ID='$ID';";
    $conn->exec($sql);
    echo $sql . "<br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $e . "<br>" . $sql . "<br>";
}

echo '<script>
        Swal.fire({
            icon: "success",
            title: "Actualizando registro  de ' . $nombre . ' en Horarios",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
