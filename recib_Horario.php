<!--comment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

$nombre         = $_POST['nombre'];
$temps          = $_POST['temps'];
$dias           = $_POST['dias'];
$duracion       = $_POST['duracion'];
$tempo          = $_POST['temporada'];
$link           = $_POST['link'];


echo $nombre . "<br>";
echo $temps . "<br>";
echo $dias . "<br>";
echo $duracion . "<br>";
echo $tempo . "<br>";
echo $link . "<br>";


$sql3 = ("SELECT * FROM `horario` where Nombre='$nombre' AND num_horario='$tempo' ;");
$horario    = mysqli_query($conexion, $sql3);

if (mysqli_num_rows($horario) == 0) {
    echo $sql3;
    echo "<br>";
    echo "No existe el anime en el horario,asi que lo creo:<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`num_horario`)
            VALUES ( '" . $nombre . "" . $temps . "', '" . $dias . "', '" . $duracion . "','" . $tempo . "')";
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
            title: "Creando registro  de ' . $nombre . ' en Horarios",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
} else {
    echo $sql3 . "<br>";
    echo "Si existe el anime en el horario,asi que nada:<br>Demas <br>";

    echo '<script>
        Swal.fire({
            icon: "warning",
            title: "El registro  de ' . $nombre . ' en ya esta repetido en el Horario ' . $tempo . '",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
}
