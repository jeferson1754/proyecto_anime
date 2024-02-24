<!--coment-->

<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$idRegistros = $_REQUEST['id'];
$idAnime = $_REQUEST['anime'];
$cancion = $_REQUEST['cancion'];
$enlace = $_REQUEST['enlace'];
$iframe = $_REQUEST['iframe'];
$op = $_REQUEST['op'];
$estado = $_REQUEST['estado'];
$mix = $_REQUEST['mix'];
$nombre = $_REQUEST['nombre'];
$temp = $_REQUEST['temp'];
$ano = $_REQUEST['ano'];
$estado_link = $_REQUEST['estado_link'];
$link = $_REQUEST['link'];
$autor = $_REQUEST['autor'];
$ocultar = isset($_REQUEST['ocultar']) ? $_REQUEST['ocultar'] : null;

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar el estado de mostrar/ocultar
    $mostrar = $ocultar ? 'NO' : 'SI';
    $sql = "UPDATE `op` SET `mostrar` = :mostrar WHERE `ID` = :idRegistros";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':mostrar', $mostrar);
    $stmt->bindParam(':idRegistros', $idRegistros);
    $stmt->execute();

    // Convertir temporada a número
    switch ($temp) {
        case 'Invierno':
            $tempor = 1;
            break;
        case 'Primavera':
            $tempor = 2;
            break;
        case 'Verano':
            $tempor = 3;
            break;
        case 'Otoño':
            $tempor = 4;
            break;
        case 'Desconocida':
            $tempor = 5;
            break;
        default:
            $tempor = $temp;
            break;
    }

    // Verificar si el autor existe
    $stmt = $conn->prepare("SELECT * FROM `autor` WHERE Autor = :autor");
    $stmt->bindParam(':autor', $autor);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $autores = $row['ID'];
    } else {
        // Insertar nuevo autor si no existe
        $stmt = $conn->prepare("INSERT INTO `autor` (`Autor`) VALUES (:autor)");
        $stmt->bindParam(':autor', $autor);
        $stmt->execute();
        $autores = $conn->lastInsertId();
    }

    // Actualizar la OP en la base de datos
    $sql = "UPDATE `op` SET
            `Cancion` = :cancion,
            `Link` = :enlace,
            `Link_Iframe` = :iframe,
            `Estado` = :estado,
            `ID_Anime` = :idAnime,
            `Temporada` = :tempor,
            `Estado_Link` = :estado_link,
            `Ano` = :ano,
            `ID_Autor` = :autores,
            `Mix` = :mix
            WHERE `ID` = :idRegistros";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cancion', $cancion);
    $stmt->bindParam(':enlace', $enlace);
    $stmt->bindParam(':iframe', $iframe);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':idAnime', $idAnime);
    $stmt->bindParam(':tempor', $tempor);
    $stmt->bindParam(':estado_link', $estado_link);
    $stmt->bindParam(':ano', $ano);
    $stmt->bindParam(':autores', $autores);
    $stmt->bindParam(':mix', $mix);
    $stmt->bindParam(':idRegistros', $idRegistros);
    $stmt->execute();

    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

// Actualizar los datos del registro de autor
$sql2 = "UPDATE autor SET Canciones = (SELECT COUNT(*) FROM op WHERE op.ID_Autor = autor.ID) + (SELECT COUNT(*) FROM ed WHERE ed.ID_Autor= autor.ID);";
$result2 = $conexion->query($sql2);

// Mensaje de éxito con SweetAlert
echo '<script>
    Swal.fire({
        icon: "success",
        title: "Actualizando OP ' . $op . ' de ' . $nombre . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
</script>';
?>