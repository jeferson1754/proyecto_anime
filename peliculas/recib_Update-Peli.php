<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

$idRegistros = $_POST['id'];
$idPendientes = $_POST['pendi'];
$nombre = $_REQUEST['nombre'];
$estado = $_REQUEST['estado'];
$fecha = $_REQUEST['fecha'];

try {
    // Conexión PDO para todas las consultas
    $conn = conectarPDO($servidor, $basededatos, $usuario, $password);

    // Consultas necesarias
    $sqlPelicula = "SELECT * FROM `peliculas` WHERE ID='$idRegistros'";
    $sqlPendientes = "SELECT * FROM `pendientes` WHERE ID_Pendientes='$idPendientes' AND ID_Pendientes > 1";
    $sqlNombre = "SELECT * FROM `peliculas` WHERE Nombre='$nombre'";

    $resultadoPelicula = $conn->query($sqlPelicula);
    $resultadoPendientes = $conn->query($sqlPendientes);
    $resultadoNombre = $conn->query($sqlNombre);

    if ($resultadoPelicula->rowCount() == 0) {
        mostrarError("No se puede editar porque $nombre no existe en Peliculas");
    } else {
        echo "Existe en Peliculas<br>";

        // Comienza transacción
        $conn->beginTransaction();

        // Lógica de estado 'Finalizado'
        if ($estado == "Finalizado") {
            if ($resultadoPendientes->rowCount() == 0) {
                actualizarPelicula($conn, $idRegistros, $nombre, $fecha, $estado, $idPendientes);
            } else {
                actualizarPelicula($conn, $idRegistros, $nombre, $fecha, $estado, 1);
                eliminarPendiente($conn, $idPendientes);
            }
        }
        // Lógica de estado 'Pendiente'
        else if ($estado == "Pendiente") {
            if ($resultadoPendientes->rowCount() == 0) {
                insertarPendiente($conn, $nombre);
                actualizarPelicula($conn, $idRegistros, $nombre, $fecha, $estado);
            } else {
                actualizarPendiente($conn, $idPendientes, $nombre);
                actualizarPelicula($conn, $idRegistros, $nombre, $fecha, $estado, $idPendientes);
            }
        }

        // Confirmar la transacción
        $conn->commit();
        mostrarExito("Actualizando registro de $nombre en Peliculas");
    }
} catch (PDOException $e) {
    $conn->rollBack();
    mostrarError("Error al procesar la operación: " . $e->getMessage());
}

// Funciones de manejo de base de datos

function actualizarPelicula($conn, $idRegistros, $nombre, $fecha, $estado, $idPendientes = null)
{
    $sql = "UPDATE peliculas SET 
            Nombre = :nombre,
            Ano = :fecha,
            Estado = :estado" .
        ($idPendientes !== null ? ", ID_Pendientes = :idPendientes" : "") .
        " WHERE ID = :idRegistros";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':fecha' => $fecha,
        ':estado' => $estado,
        ':idRegistros' => $idRegistros,
        ':idPendientes' => $idPendientes
    ]);
}

function eliminarPendiente($conn, $idPendientes)
{
    $sql = "DELETE FROM pendientes WHERE ID_Pendientes = :idPendientes";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':idPendientes' => $idPendientes]);
}

function insertarPendiente($conn, $nombre)
{
    $sql = "INSERT INTO pendientes (Nombre, Tipo, Vistos, Total) VALUES (:nombre, 'Pelicula', 0, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nombre' => $nombre]);

    // Actualiza el campo de pendientes
    $sqlUpdate = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
    $conn->exec($sqlUpdate);
}

function actualizarPendiente($conn, $idPendientes, $nombre)
{
    $sql = "UPDATE pendientes SET 
            Nombre = :nombre,
            Vistos = 0,
            Total = 1,
            Tipo = 'Pelicula'
            WHERE ID_Pendientes = :idPendientes";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':nombre' => $nombre, ':idPendientes' => $idPendientes]);

    // Actualiza el campo de pendientes
    $sqlUpdate = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
    $conn->exec($sqlUpdate);
}

function conectarPDO($servidor, $basededatos, $usuario, $password)
{
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function mostrarExito($mensaje)
{
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "' . $mensaje . '",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "./";
        });
        </script>';
}

function mostrarError($mensaje)
{
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "' . $mensaje . '",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "./";
        });
        </script>';
}
?>