<!--coment-->

<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

// --- FUNCIÓN PARA GENERAR EL LINK DEL IFRAME ---

$idRegistros = $_REQUEST['id'];
$idAnime = $_REQUEST['anime'];
$cancion = $_REQUEST['cancion'];
$enlace = $_REQUEST['enlace']; // Este es el link normal que ingresas
$op = $_REQUEST['op'];
$estado = $_REQUEST['estado'];
$mix = $_REQUEST['mix'];
$nombre = $_REQUEST['nombre'];
$temp = $_REQUEST['temp'];
$ano = $_REQUEST['ano'];
$estado_link = $_REQUEST['estado_link'];
$link = $_REQUEST['link'];
$autor = $_REQUEST['autor'];


$iframe = convertirEnlaceIframe($enlace);


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (is_numeric($temp) && isset($temporadas[(int)$temp])) {
        $tempor = $temporadas[(int)$temp];
    } else {
        $tempor = $temp;
    }

    // Verificar si el autor existe
    $stmt = $conn->prepare("SELECT * FROM `autor` WHERE Autor = :autor");
    $stmt->bindParam(':autor', $autor);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $autores = $row['ID'];
    } else {
        $stmt = $conn->prepare("INSERT INTO `autor` (`Autor`) VALUES (:autor)");
        $stmt->bindParam(':autor', $autor);
        $stmt->execute();
        $autores = $conn->lastInsertId();
    }

    // Actualizar la OP en la base de datos con el $iframe procesado
    $sql = "UPDATE `op` SET
            `Cancion` = :cancion,
            `Link` = :enlace,
            `Link_Iframe` = :iframe,
            `Estado` = :estado,
            `ID_Anime` = :idAnime,
            `Temporada_Emision` = :tempor,
            `Estado_Link` = :estado_link,
            `Ano` = :ano,
            `ID_Autor` = :autores,
            `Mix` = :mix
            WHERE `ID` = :idRegistros";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cancion', $cancion);
    $stmt->bindParam(':enlace', $enlace);
    $stmt->bindParam(':iframe', $iframe); // Aquí se guarda el link ya convertido
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
    echo "Error en PDO: " . $e->getMessage();
}

// Actualizaciones de conteo de canciones
$sql_count = "
    UPDATE autor 
    SET Canciones = (
        SELECT COUNT(*) FROM op WHERE op.ID_Autor = autor.ID
    ) + (
        SELECT COUNT(*) FROM ed WHERE ed.ID_Autor = autor.ID
    );

    UPDATE autor SET Copia_Autor = 'SI' 
    WHERE (Canciones + Canciones_Musica) >= 5 AND ID != 1;
";

// Nota: Asegúrate de que $conexion esté definida en tu bd.php para mysqli
$result = $conexion->multi_query($sql_count);

if ($result) {
    do {
        if ($conexion->more_results()) {
            $conexion->next_result();
        }
    } while ($conexion->more_results());
}

// Mensaje de éxito
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