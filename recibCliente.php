<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include('bd.php');

$id         = $_REQUEST['id'];
$nombre     = $_REQUEST['anime'];
$estado     = $_REQUEST['estado'];
$tempo       = $_REQUEST['temp'];
$fecha      = $_REQUEST['fecha'];
$dia_emision = $_REQUEST['dias'];
$link       = $_REQUEST['link'];

// Traducir el número de temporada a texto


// Consultar si el anime ya existe
$sql_anime_exist = "SELECT * FROM `anime` WHERE Nombre='$nombre'";
$result_anime_exist = mysqli_query($conexion, $sql_anime_exist);

// Obtener el valor máximo de mix
$sql_mix = "SELECT * FROM mix ORDER BY ID DESC LIMIT 1";
$result_mix = $conexion->query($sql_mix);
$mix_row = $result_mix->fetch_assoc();
$mix = $mix_row['ID'];

// Obtener el valor máximo de mix_ed
$sql_mix_ed = "SELECT * FROM mix_ed ORDER BY ID DESC LIMIT 1";
$result_mix_ed = $conexion->query($sql_mix_ed);
$mix_ed_row = $result_mix_ed->fetch_assoc();
$mix2 = $mix_ed_row['ID'];


$sql_horario_exist = "SELECT * FROM `num_horario` WHERE Temporada='$tempo' AND Ano='$fecha'";
$result_horario_exist = mysqli_query($conexion, $sql_horario_exist);
if (mysqli_num_rows($result_horario_exist) == 0) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_insert_horario = "INSERT INTO num_horario (`Temporada`, `Ano`) VALUES ('$tempo', '$fecha')";
        $conn->exec($sql_insert_horario);
        $num_horario = $conn->lastInsertId();
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
} else {
    $row = mysqli_fetch_assoc($result_horario_exist);
    $num_horario = $row['Num'];
}

// Si el anime no existe, crear registros
if (mysqli_num_rows($result_anime_exist) == 0) {
    // Determinar qué tipo de registro se va a crear según el estad

    // Insertar en la tabla anime
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_insert_anime = "INSERT INTO anime (`id`, `Nombre`, `Estado`, `Ano`, `Temporada`) VALUES ('$id', '$nombre', '$estado','$fecha', '$tempo')";
        $conn->exec($sql_insert_anime);
        $last_id_anime = $conn->lastInsertId();
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }


    switch ($estado) {
        case "Emision":

            // Insertar en la tabla emision
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_emision = "INSERT INTO emision (`ID_Anime`, `Capitulos`, `Totales`, `Dia`, `Duracion`) VALUES ('$last_id_anime', '1', '12', '$dia_emision', '00:24:00')";
                $conn->exec($sql_insert_emision);
                $last_id_emision = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }


            // Insertar en la tabla horario
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_horario = "INSERT INTO `horario` (`ID_Anime`, `Dia`, `Duracion`, `num_horario`) VALUES ('$last_id_anime', '$dia_emision', '00:24:00', '$num_horario')";
                $conn->exec($sql_insert_horario);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de  ' . $nombre . ' en Anime y en Emision",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';

            break;

        case "Finalizado":

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de  ' . $nombre . ' en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';

            break;

        case "Pausado":

            echo "Num_Horario " . $num_horario . "<br>";


            // Insertar en la tabla emision
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_emision = "INSERT INTO emision (`ID_Anime`, `Capitulos`, `Totales`, `Dia`, `Duracion`) VALUES ('$last_id_anime', '1', '12', '$dia_emision', '00:24:00')";
                $conn->exec($sql_insert_emision);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }


            // Insertar en la tabla horario
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_horario = "INSERT INTO `horario` (`ID_Anime`, `Dia`, `Duracion`, `num_horario`) VALUES ('$last_id_anime', '$dia_emision', '00:24:00', '$num_horario')";
                $conn->exec($sql_insert_horario);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }


            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de  ' . $nombre . ' en Anime y en Emision",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';

            die;

        case "Pendiente":
            // Insertar en la tabla pendientes
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_pendientes = "INSERT INTO pendientes (`ID_Anime`, `Tipo`, `Vistos`, `Total`,`Pendientes`) VALUES ('$last_id_anime', 'Anime', '1', '12', '11')";
                $conn->exec($sql_insert_pendientes);
                $last_id_pendientes = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de  ' . $nombre . ' en Anime y en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';

            break;

        default:
            // No hacer nada si el estado no es reconocido
            break;
    }


    // Eliminar el registro de id_anime
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_delete_id_anime = "DELETE FROM `id_anime` WHERE `ID` = '$id'";
        $conn->exec($sql_delete_id_anime);
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    // Insertar en la tabla op si es necesario
    if (isset($_REQUEST["OP"])) {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql_insert_op = "INSERT INTO op (`ID_Anime`, `Opening`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) VALUES ('$last_id_anime', '1', '$fecha', '$tempo', 'Faltante', '$mix', 'Faltante', NOW())";
            $conn->exec($sql_insert_op);
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }
    }

    // Insertar en la tabla ed si es necesario
    if (isset($_REQUEST["ED"])) {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql_insert_ed = "INSERT INTO ed (`ID_Anime`, `Ending`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) VALUES ('$last_id_anime', '1', '$fecha', '$tempo', 'Faltante', '$mix2', 'Faltante', NOW())";
            $conn->exec($sql_insert_ed);
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }
    }
} else {
    // Si el anime ya existe, mostrar mensaje de error
    echo "Anime ya existe";
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Registro de ' . $nombre . ' Existe en Anime",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';
}

echo $link;

?>