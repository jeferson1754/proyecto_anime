<!--
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>
-->
<?php
include '../bd.php';
$idRegistros    = $_POST['id'];
$caps           = $_POST['caps'];
$total          = $_POST['total'];
$dias           = $_POST['dias'];
$nombre         = $_POST['nombre'];
$duracion       = $_POST['duracion'];
$accion         = $_REQUEST['accion'];
$estado         = $_REQUEST['estado'];
$link           = $_REQUEST['link'];
$op             = $_POST['op'];
$ed             = $_POST['ed'];
$posicion       = $_POST['posicion'];

$sql = ("SELECT * FROM `anime` where id_Emision='$idRegistros';");
$sql2 = ("SELECT * FROM `horario` where Nombre='$nombre';");
$sql3 = "SELECT * FROM `emision` WHERE Dia='$dias' AND Posicion='$posicion';";

$horario    = mysqli_query($conexion, $sql2);
$anime      = mysqli_query($conexion, $sql);
$conteo      = mysqli_query($conexion, $sql3);

echo $sql2 . "<br>";
echo $sql3 . "<br>";

//Ordena los animes en emision de 1,2,3 ,etc... y despues 0
//SELECT * FROM emision where Dia="Sabado" ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;


while ($fila = mysqli_fetch_assoc($anime)) {
    $temps = $fila["Id_Temporada"];
    $fecha = $fila["Ano"];
}

// Mapeo de valores de temporada
$temporadas = [
    1 => "Invierno",
    2 => "Primavera",
    3 => "Verano",
    4 => "Otoño",
    5 => "Desconocida"
];

// Obtener el nombre de la temporada
$tempo = isset($temporadas[$temps]) ? $temporadas[$temps] : "Desconocida";


echo $sql;
echo "<br>";

$sql2 = $conexion->query("SELECT * FROM `anime` where id_Emision='$idRegistros';");

while ($valores = mysqli_fetch_array($sql2)) {
    $IdAnime = $valores["id"];
}

echo $IdAnime;
echo "<br>";

$opening = $conexion->query("SELECT COUNT(*) total FROM `op` where ID_Anime='$IdAnime';");

while ($valores = mysqli_fetch_array($opening)) {
    $op1 = $valores[0];
}

$ending = $conexion->query("SELECT COUNT(*) total FROM `ed` where ID_Anime='$IdAnime';");

while ($valores = mysqli_fetch_array($ending)) {
    $ed1 = $valores[0];
}



include('../pruebas.php');



if (mysqli_num_rows($horario) == 0) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`Temporada`, `Ano`)
            VALUES ( '" . $nombre . "', '" . $dias . "', '" . $duracion . "','" . $tempo . "','" . $fecha . "')";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
} else {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE horario SET 
        Dia='" . $dias . "',
        Duracion='" . $duracion . "'
        WHERE Nombre='" . $nombre . "' ORDER BY `horario`.`ID` DESC limit 1;";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
}

if (mysqli_num_rows($conteo) == 0) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE emision SET 
            Posicion='" . $posicion . "'
            WHERE ID_Emision='" . $idRegistros . "'";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
        echo "<br>";
    }
    echo "El conteo no existe asi que esta bien<br>";
} else {
    echo "El conteo existe asi que nada<br>";
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Posicion N° ' . $posicion . ' Repetido o Erronea ",
        confirmButtonText: "OK"

    }).then(function() {
        window.location = "' . $link . '"; 
    });
    </script>';
}

if ($posicion == 0) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE emision SET 
            Posicion='" . $posicion . "'
            WHERE ID_Emision='" . $idRegistros . "'";
        $conn->exec($sql);
        echo $sql;
        echo "<br>";
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
        echo "<br>";
    }
    echo "El conteo es cero asi que esta bien<br>";
}

if (mysqli_num_rows($anime) == 0) {

    echo '<script>
    Swal.fire({
        icon: "error",
        title: "No se puede editar ' . $nombre . ' porque no existe en anime",
        confirmButtonText: "OK"

    }).then(function() {
        window.location = "' . $link . '"; 
    });
    </script>';
} else {

    echo "Existe en Anime";
    echo "<br>";
    if ($estado == "Emision") {

        echo "Anime en Emision";
        echo "<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE emision SET 
        Capitulos ='" . $caps . "',
        Totales ='" . $total . "',
        Emision='" . $estado . "',
        Dia='" . $dias . "',
        Duracion='" . $duracion . "'
        WHERE ID_Emision='" . $idRegistros . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo "<br>";
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE anime SET 
        Estado='" . $estado . "'
        WHERE id_Emision='" . $idRegistros . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo "<br>";
        }

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro en Emision de ' . $nombre . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location =  "' . $link . '"; 
            });
            </script>';
    } else if ($estado == "Pausado") {
        echo "Anime Pausado";
        echo "<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE emision SET 
        Capitulos ='" . $caps . "',
        Totales ='" . $total . "',
        Dia='" . $dias . "',
        Emision='" . $estado . "',
        Duracion='" . $duracion . "'
        WHERE ID_Emision='" . $idRegistros . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo "<br>";
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE anime SET 
        Estado='" . $estado . "'
        WHERE id_Emision='" . $idRegistros . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo "<br>";
        }

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro en Emision de ' . $nombre . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location =  "' . $link . '"; 
            });
            </script>';
    } else if ($estado == "Pendiente") {
        echo "Anime Pendiente";
        echo "<br>";

        //INSERT EN PENDIENTES
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO pendientes (`Nombre`,`Tipo`, `Vistos`, `Total`,`Estado_Link`) 
            VALUES ( '" . $nombre . "','Anime','" . $caps . "','" . $total . "','Faltante')";
            $conn->exec($sql);
            $last_id1 = $conn->lastInsertId();
            echo $sql;
            echo 'ultimo anime insertado ' . $last_id1;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;

            echo '<script>
            Swal.fire({
                icon: "error",
                title: "' . $nombre . ' -No se puede insertar en pendientes",
                confirmButtonText: "OK"

            }).then(function() {
                window.location = "' . $link . '"; 
            });
            </script>';
        }


        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos) where Vistos > 0;";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        //DELETE EN EMISION
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `emision` where ID_Emision='" . $idRegistros . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $sql;
            echo $e;
            echo "<br>";
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "' . $nombre . ' -No se puede eliminar de emision",
                confirmButtonText: "OK"

            }).then(function() {
                window.location = "' . $link . '"; 
            });
            </script>';
        }

        //ACTUALIZAR ID_PENDIENTES Y ID_EMISION
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE anime SET 
            `Estado`='Pendiente',
           `id_Emision`=1,
           `id_Pendientes`='" . $last_id1 . "'
            WHERE id='" . $IdAnime . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "' . $nombre . ' -No se puede actualizar en anime",
                confirmButtonText: "OK"

            }).then(function() {
                window.location = "' . $link . '"; 
            });
            </script>';
        }

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro en Pendientes, Eliminando en Emision y Actualizando en Anime de ' . $nombre . '",
                confirmButtonText: "OK"
            }).then(function() {
                window.location =  "' . $link . '"; 
            });
            </script>';
    }
}
