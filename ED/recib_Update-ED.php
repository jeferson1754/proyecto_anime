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

// Determinar si se debe ocultar el registro
$mostrar = isset($_REQUEST["ocultar"]) ? 'NO' : 'SI';

try {
    // Actualizar el estado de visualización del registro
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE ed SET mostrar = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$mostrar, $idRegistros]);
    $conn = null;
} catch (PDOException $e) {
    echo $e;
}

// Determinar el valor numérico de la temporada
$tempor = ($temp == "Invierno") ? "1" : (($temp == "Primavera") ? "2" : (($temp == "Verano") ? "3" : (($temp == "Otoño") ? "4" : (($temp == "Desconocida") ? "5" : $temp))));

// Obtener el ID del autor
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT ID FROM autor WHERE Autor = ?");
    $stmt->execute([$autor]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $autores = $result ? $result['ID'] : null;
    $conn = null;
} catch (PDOException $e) {
    echo $e;
}

// Si no se encontró el ID del autor, insertarlo en la tabla de autores y obtener su ID
if (!$autores) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO autor (Autor) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$autor]);
        $autores = $conn->lastInsertId();
        $conn = null;
    } catch (PDOException $e) {
        echo $e;
    }
}

try {
    // Actualizar los datos del registro de ending
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE ed SET Cancion = ?, Link = ?, Link_Iframe = ?, Estado = ?, ID_Anime = ?, Temporada = ?, Ending = ?, Estado_Link = ?, Ano = ?, ID_Autor = ?, Mix = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cancion, $enlace, $iframe, $estado, $idAnime, $tempor, $ed, $estado_link, $ano, $autores, $mix, $idRegistros]);
    $conn = null;
} catch (PDOException $e) {
    echo $e;
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
