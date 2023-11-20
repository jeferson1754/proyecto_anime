<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$id         = $_REQUEST['id'];
$nombre     = $_REQUEST['nombre'];
$estado     = $_REQUEST['estado'];
$fecha      = $_REQUEST['fecha'];

$sql = ("SELECT * FROM `peliculas` where Nombre='$nombre';");
$sql2 = ("SELECT * FROM `pendientes`where Nombre='$nombre';");
$peli      = mysqli_query($conexion, $sql);
$pendientes = mysqli_query($conexion, $sql2);




if (mysqli_num_rows($peli) == 0) {

    echo "Anime En Peliculas";
    echo "<br>";
    echo $estado;
    echo "<br>";
    if ($estado == "Finalizado") {
        echo "Anime En Finalizado";
        echo "<br>";
        echo $estado;
        echo "<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `peliculas`(`ID`,`Nombre`, `Ano`, `Estado`, `ID_Pendientes`) VALUES
             ( '" . $id . "','" . $nombre . "','" . $fecha . "','" . $estado . "',1);";

            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_peliculas`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre . ' en Peliculas",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "../peliculas.php";
        });
        </script>';
    } else if ($estado == "Pendiente") {
        echo "Anime En Pendiente";
        echo "<br>";
        echo $estado;


        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //INSERT INTO `pendientes`(`ID`, `Nombre`, `Vistos`, `Total`, `Pendientes`, `Link`)
            $sql = "INSERT INTO pendientes (`Nombre`, `Tipo`, `Vistos`, `Total`) 
            VALUES ( '" . $nombre . "','Pelicula','0','1')";
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
            $sql = "INSERT INTO `peliculas`(`ID`,`Nombre`, `Ano`, `Estado`, `ID_Pendientes`) VALUES
             ( '" . $id . "','" . $nombre . "','" . $fecha . "','" . $estado . "','" . $last_id1 . "')";

            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) where Vistos >-1;";
            $conn->exec($sql);
            $last_id3 = $conn->lastInsertId();
            echo $sql;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_peliculas`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre . ' en Pendiente y Peliculas",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "../peliculas.php";
        });
        </script>';
    }
} else {
echo"Ya existe en Peliculas";

    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Registro de ' . $nombre . ' Existe en Peliculas",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "../peliculas.php";
    });
    </script>';
}
