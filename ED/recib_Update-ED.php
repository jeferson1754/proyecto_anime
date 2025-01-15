<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

// Obtener los datos del formulario
$idRegistros = $_REQUEST['id'];
$idAnime = $_REQUEST['anime'];
$cancion = $_REQUEST['cancion'];
$enlace = $_REQUEST['enlace'];
$iframe = $_REQUEST['iframe'];
$ed = $_REQUEST['ed'];
$estado = $_REQUEST['estado'];
$mix = $_REQUEST['mix'];
$nombre = $_REQUEST['nombre'];
$temp = $_REQUEST['temp'];
$ano = $_REQUEST['ano'];
$estado_link = $_REQUEST['estado_link'];
$link = $_REQUEST['link'];
$autor = $_REQUEST['autor'];


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Invertir el mapeo para buscar por valor
    $temporadas_invertidas = array_flip($temporadas);

    // Obtener el número correspondiente a la temporada
    $tempor = $temporadas_invertidas[$temp] ?? null;

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
    $sql = "UPDATE `ed` SET
            `Cancion` = :cancion,
            `Link` = :enlace,
            `Link_Iframe` = :iframe,
            `Estado` = :estado,
            `ID_Anime` = :idAnime,
            `Temporada` = :tempor,
            `Estado_Link` = :estado_link,
            `Ano` = :ano,
            `ID_Autor` = :autores,
            `Ending` = :ending,
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
    $stmt->bindParam(':ending', $ed);
    $stmt->bindParam(':mix', $mix);
    $stmt->bindParam(':idRegistros', $idRegistros);
    $stmt->execute();

    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}
$sql = "
    -- Actualizar el conteo de canciones
    UPDATE autor 
    SET Canciones = (
        SELECT COUNT(*) FROM op WHERE op.ID_Autor = autor.ID
    ) + (
        SELECT COUNT(*) FROM ed WHERE ed.ID_Autor = autor.ID
    );

    -- Actualizar Copia_Autor a 'SI' si cumple con la condición
    UPDATE autor SET Copia_Autor = 'SI' 
    WHERE (Canciones + Canciones_Musica) >= 5 AND ID != 1;
";

$result = $conexion->multi_query($sql);

if ($result) {
    do {
        // Verificar si hay más resultados o errores
        if ($conexion->more_results()) {
            $conexion->next_result();
        }
    } while ($conexion->more_results());
    echo "Actualización completada con éxito.";
} else {
    echo "Error en la actualización: " . $conexion->error;
}



// Mensaje de éxito con SweetAlert
echo '<script>
    Swal.fire({
        icon: "success",
        title: "Actualizando ED ' . $ed . ' de ' . $nombre . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
</script>';
?>