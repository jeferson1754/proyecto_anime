<!--comment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$nombre = $_POST['nombre'];
$temps = $_POST['temps'];
$dias = $_POST['dias'];
$duracion = $_POST['duracion'];
$tempo = $_POST['temporada'];
$link = $_POST['link'];

// Mostrar los datos recibidos
echo "Nombre: $nombre <br>";
echo "Temps: $temps <br>";
echo "Días: $dias <br>";
echo "Duración: $duracion <br>";
echo "Temporada: $tempo <br>";
echo "Link: $link <br>";

// Consultar si el anime ya está en el horario
$sql3 = "SELECT * FROM `horario` WHERE Nombre='$nombre' AND num_horario='$tempo'";
$horario = mysqli_query($conexion, $sql3);

if (mysqli_num_rows($horario) == 0) {
    // El anime no está en el horario, así que se crea
    echo "No existe el anime en el horario, así que lo creo:<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insertar registro en la tabla de horario
        $sql = "INSERT INTO `horario` (`Nombre`, `Dia`, `Duracion`, `num_horario`)
                VALUES ('$nombre' '$temps', '$dias', '$duracion', '$tempo')";
        $conn->exec($sql);
        echo "Query ejecutada: $sql <br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo "Error al ejecutar la consulta: " . $e->getMessage() . "<br>";
    }

    // Mostrar mensaje de éxito
    echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . ' en Horarios",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
        </script>';
} else {
    // El anime ya está en el horario
    echo "El anime ya existe en el horario, así que no se hace nada<br>";
    
    // Mostrar mensaje de advertencia
    echo '<script>
            Swal.fire({
                icon: "warning",
                title: "El registro de ' . $nombre . ' ya está repetido en el Horario ' . $tempo . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
        </script>';
}
?>

