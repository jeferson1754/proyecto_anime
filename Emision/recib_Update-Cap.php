<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

// Obtener los parámetros de la solicitud
$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['nombre'];
$vistos = $_REQUEST['vistos'];
$caps = $_REQUEST['capitulos'];
$accion = $_REQUEST['accion'];
$link = $_REQUEST['link'];

// Consultas
$sql = "SELECT (Totales - Capitulos) as restantes FROM `emision` WHERE Nombre = ?";
$stmt = $connect->prepare($sql);
$stmt->execute([$nombre]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $capitulosRestantes = $result['restantes'];

    // Validar si los capítulos ingresados son válidos
    if ($vistos <= $capitulosRestantes) {
        try {
            // Conexión PDO para la actualización
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Actualización de capítulos
            $sqlUpdate = "UPDATE emision SET Capitulos = Capitulos + :vistos WHERE Nombre = :nombre AND Capitulos < Totales";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':vistos', $vistos, PDO::PARAM_INT);  // Ligar el valor de los capítulos vistos
            $stmtUpdate->bindValue(':nombre', $nombre, PDO::PARAM_STR);  // Ligar el valor del nombre
            $stmtUpdate->execute();

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando Capitulos de ' . $nombre . ' en Emisión",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "' . $link . '";
                });
            </script>';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Los Capitulos Ingresados de ' . $nombre . ' Superan el Total Permitido",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
        </script>';
        echo "Capítulos permitidos: " . $capitulosRestantes . "<br>";
    }
} else {
    echo "No se encontró la emisión para el nombre proporcionado.";
}
?>