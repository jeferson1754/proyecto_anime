<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$fecha_actual = date('Y-m-d');

$idRegistros    = $_REQUEST['id'];

$accion         = $_REQUEST['accion'];
$link           = $_REQUEST['link'];

// Realizar la conexión a la base de datos una sola vez
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener datos de emision
$sql1 = "SELECT * FROM emision WHERE ID_Emision = :idRegistros";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute(['idRegistros' => $idRegistros]);
$emision = $stmt1->fetch(PDO::FETCH_ASSOC);

// Obtener datos de anime
$sql2 = "SELECT * FROM anime WHERE id_Emision = :idRegistros";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute(['idRegistros' => $idRegistros]);
$anime = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($anime) {
    $id_anime = $anime['id'];
    $temporadas = $anime['Temporadas'];
    $nombre         = $anime['Anime'];
}

// Preparar datos para eliminar emision
$dato1 = $emision['ID_Emision'];
$dato2 = $emision['Emision'];
$dato3 = $emision['Nombre'];
$dato4 = $emision['Capitulos'];
$dato5 = $emision['Totales'];
$dato6 = $emision['Dia'];
$dato8 = $emision['Posicion'];
$dato10 = $emision['Duracion'];

// Actualizar estado de anime a "Finalizado"
try {
    $sql = "UPDATE anime SET estado = 'Finalizado', ID_Emision = 1 WHERE ID_Emision = :idRegistros";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idRegistros' => $idRegistros]);
} catch (PDOException $e) {
    echo "Error actualizando el anime: " . $e->getMessage();
}

// Insertar emision en eliminados_emision
try {
    $sql = "INSERT INTO eliminados_emision (ID_Emision, Estado, Nombre, Capitulos, Totales, Dia, Duracion, Fecha) 
            VALUES (:dato1, :dato2, :dato3, :dato4, :dato5, :dato6, :dato10, :fecha_actual)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'dato1' => $dato1,
        'dato2' => $dato2,
        'dato3' => $dato3,
        'dato4' => $dato4,
        'dato5' => $dato5,
        'dato6' => $dato6,
        'dato10' => $dato10,
        'fecha_actual' => $fecha_actual
    ]);
} catch (PDOException $e) {
    echo "Error al insertar en eliminados_emision: " . $e->getMessage();
}

// Eliminar emision
try {
    $sql = "DELETE FROM emision WHERE ID_Emision = :idRegistros";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idRegistros' => $idRegistros]);
} catch (PDOException $e) {
    echo "Error eliminando emision: " . $e->getMessage();
}

// Eliminar registros antiguos de eliminados_emision
try {
    $sql = "DELETE FROM eliminados_emision WHERE Fecha < DATE_SUB(NOW(), INTERVAL 5 MONTH)";
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Error limpiando eliminados_emision: " . $e->getMessage();
}

// Lógica para Calificar
if (isset($_POST['Calificar_Ahora'])) {
    header("location:../Calificaciones/editar_stars.php?id=$id_anime&nombre=$nombre&temporada=$temporadas");
} else if (isset($_POST['Calificar_Luego'])) {
    try {
        $sql = "INSERT INTO calificaciones (ID_Anime, Temporadas) VALUES (:id_anime, :temporadas)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_anime' => $id_anime,
            'temporadas' => $temporadas
        ]);
        echo "Calificación insertada correctamente.";
    } catch (PDOException $e) {
        echo "Error al insertar calificación: " . $e->getMessage();
    }


    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Actualizando Estado de ' . $nombre . '  a Finalizado y Creando Calificación en Pendiente",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
} else {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Actualizando Estado de ' . $nombre . '  a Finalizado",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
    </script>';
}

?>