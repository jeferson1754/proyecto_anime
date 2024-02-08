<!--comment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$ID = $_POST['id'];
$nombre = $_POST['nombre'];
$dias = $_POST['dias'];
$duracion = $_POST['duracion'];
$link = $_POST['link'];

// Mostrar los datos recibidos
echo "ID: $ID <br>";
echo "Nombre: $nombre <br>";
echo "Días: $dias <br>";
echo "Duración: $duracion <br>";
echo "Link: $link <br>";

try {
    // Establecer conexión con la base de datos
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ejecutar la consulta de actualización
    $sql = "UPDATE `horario` SET `Nombre`='$nombre', `Dia`='$dias', `Duracion`='$duracion' WHERE ID='$ID'";
    $conn->exec($sql);
    echo "Consulta ejecutada: $sql <br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo "Error al ejecutar la consulta: " . $e->getMessage() . "<br>";
}

// Mostrar mensaje de éxito
echo '<script>
        Swal.fire({
            icon: "success",
            title: "Actualizando registro de ' . $nombre . ' en Horarios",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
?>