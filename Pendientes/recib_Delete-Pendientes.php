<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros = $_REQUEST['id'];
$nombre = $_REQUEST['nombre'];
$link    = $_REQUEST['link'];
$tipo   = $_REQUEST['tipo'];

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

// Determinar el siguiente tipo
switch ($tipo) {
    case "Anime":
        $siguiente = "Ova y Otros";
        break;
    case "Ova y Otros":
        $siguiente = "Pelicula";
        break;
    default:
        $siguiente = "Anime";
        break;
}

// Preparar la consulta
$sql2 = "SELECT COUNT(*) as cantidad, ID_Pendientes 
         FROM pendientes 
         WHERE tipo = ? 
         GROUP BY Pendientes 
         ORDER BY Pendientes ASC 
         LIMIT 1";

// Ejecutar la consulta
$stmt = $conexion->prepare($sql2);
$stmt->bind_param("s", $siguiente);
$stmt->execute();
$result = $stmt->get_result();

// Obtener resultados
$cantidad = 0;
$id_Pendiente = null;

if ($mostrar = $result->fetch_assoc()) {
    $cantidad = $mostrar['cantidad'];
    $id_Pendiente = $mostrar['ID_Pendientes'];
}

echo $cantidad . "<br>" . $id_Pendiente;

// Si no hay registros, buscar en el siguiente tipo
if ($cantidad == 0) {
    switch ($siguiente) {
        case "Anime":
            $next = "Ova y Otros";
            break;
        case "Ova y Otros":
            $next = "Pelicula";
            break;
        default:
            $next = "Anime";
            break;
    }

    // Repetir la consulta para el siguiente tipo
    $stmt->bind_param("s", $next);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($mostrar = $result->fetch_assoc()) {
        $id_Pendiente = $mostrar['ID_Pendientes'];
    }
}

echo $id_Pendiente . "<br>";

try {
    // Conexión con PDO para la actualización
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar consulta de actualización
    $sql = "UPDATE pendientes SET Viendo = 'SI' WHERE ID_Pendientes = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_Pendiente]);

    echo $sql . "<br>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}





//header("location:index.php");
?>