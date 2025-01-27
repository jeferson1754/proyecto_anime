<!--coment-->

<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_POST['id'];
$nombre         = $_POST['name'];
$nombre_aviso         = $_POST['nombre_aviso'];
$caps           = $_POST['caps'];
$total          = $_POST['total'];
$enlace           = $_POST['enlace'];
$link           = $_POST['link'];
$estado         = $_POST['estado'];
$tipo           = $_POST['tipo'];
$id_anime = $_POST['anime'] ?? NULL;


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE pendientes SET 
    Temporada ='" . $nombre . "',
    Vistos ='" . $caps . "',
    ID_Anime ='" . $id_anime . "',
    Total ='" . $total . "',
    Link ='" . $enlace . "',
    Tipo ='" . $tipo . "',
    Estado_Link ='" . $estado . "'
    WHERE ID='" . $idRegistros . "'";
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
    $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos)";
    $conn->exec($sql);
    echo $sql;
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre_aviso . ' en Pendientes ",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
