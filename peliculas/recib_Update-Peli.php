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

$sqlPelicula = "SELECT * FROM `peliculas` WHERE ID='$idRegistros'";
$sqlPendientes = "SELECT * FROM `pendientes` WHERE ID_Pendientes='$idPendientes' AND ID_Pendientes > 1";
$sqlNombre = "SELECT * FROM `peliculas` WHERE Nombre='$nombre'";

$resultadoPelicula = mysqli_query($conexion, $sqlPelicula);
$resultadoPendientes = mysqli_query($conexion, $sqlPendientes);
$resultadoNombre = mysqli_query($conexion, $sqlNombre);

if (mysqli_num_rows($resultadoPelicula) == 0) {
    mostrarError("No se puede editar porque $nombre no existe en Peliculas");
} else {
    echo "Existe en Peliculas<br>";
    if ($estado == "Finalizado") {
        if (mysqli_num_rows($resultadoPendientes) == 0) {
            echo $estado . "<br>";
            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE peliculas SET 
                    Nombre ='$nombre',
                    Ano ='$fecha',
                    Estado ='$estado',
                    ID_Pendientes ='$idPendientes'
                    WHERE ID='$idRegistros'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql . "<br>";
                echo 'Último anime insertado: ' . $last_id1 . "<br>";
                mostrarExito("Actualizando registro de $nombre en Peliculas");
            } catch (PDOException $e) {
                mostrarError("Nombre Repetido $nombre");
            }
        } else {
            echo $estado . "<br>";
            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE peliculas SET 
                    Nombre ='$nombre',
                    Ano ='$fecha',
                    Estado ='$estado',
                    ID_Pendientes ='1'
                    WHERE ID='$idRegistros'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql . "<br>";
                echo 'Último anime insertado: ' . $last_id1 . "<br>";
                mostrarExito("Actualizando registro de $nombre en Peliculas");
            } catch (PDOException $e) {
                mostrarError("Nombre Repetido $nombre");
            }

            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "DELETE FROM pendientes WHERE ID_Pendientes ='$idPendientes'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql . "<br>";
            } catch (PDOException $e) {
                echo $e . "<br>";
            }

            mostrarExito("Actualizando registro de $nombre en Peliculas");
        }
    } else if ($estado == "Pendiente") {
        echo $estado . "<br>";
        if (mysqli_num_rows($resultadoPendientes) == 0) {
            echo "No Existe en Pendientes<br>";
            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE peliculas SET 
                    Nombre ='$nombre',
                    Ano ='$fecha',
                    Estado ='$estado'
                    WHERE ID='$idRegistros'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql . "<br>";
            } catch (PDOException $e) {
                mostrarError("Nombre Repetido $nombre");
            }

            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "INSERT INTO pendientes (`Nombre`, `Tipo`, `Vistos`, `Total`) 
                VALUES ('$nombre', 'Pelicula', '0', '1')";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql . "<br>";
            } catch (PDOException $e) {
                echo $e . "<br>";
            }

            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
                $conn->exec($sql);
                echo $sql . "<br>";
            } catch (PDOException $e) {
                echo $e . "<br>";
            }

            mostrarExito("Actualizando registro de $nombre en Peliculas");
        } else {
            echo "Existe en Pendientes<br>";
            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE pendientes SET 
                    Nombre ='$nombre',
                    Vistos ='0',
                    Total ='1',
                    Tipo ='Pelicula'
                    WHERE ID_Pendientes='$idPendientes'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql . "<br>";
                mostrarExito("Actualizando registro de $nombre en Peliculas");
            } catch (PDOException $e) {
                mostrarError("Nombre Repetido $nombre en Pendientes");
            }

            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE peliculas SET 
                    Nombre ='$nombre',
                    Ano ='$fecha',
                    Estado ='$estado',
                    ID_Pendientes ='$idPendientes'
                    WHERE ID='$idRegistros'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql . "<br>";
                mostrarExito("Actualizando registro de $nombre en Peliculas");
            } catch (PDOException $e) {
                mostrarError("Nombre Repetido $nombre");
            }

            try {
                $conn = conectarPDO($servidor, $basededatos, $usuario, $password);
                $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > -1";
                $conn->exec($sql);
                echo $sql . "<br>";
            } catch (PDOException $e) {
                echo $e . "<br>";
            }
        }
    }
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
