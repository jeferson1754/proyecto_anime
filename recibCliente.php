<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include('bd.php');

$id         = $_REQUEST['id'];
$nombre     = $_REQUEST['anime'];
$estado     = $_REQUEST['estado'];
$temp       = $_REQUEST['temp'];
$fecha      = $_REQUEST['fecha'];
$dia_emision = $_REQUEST['dias'];
$link       = $_REQUEST['link'];

// Traducir el número de temporada a texto
switch ($temp) {
    case 1:
        $tempo = "Invierno";
        break;
    case 2:
        $tempo = "Primavera";
        break;
    case 3:
        $tempo = "Verano";
        break;
    case 4:
        $tempo = "Otoño";
        break;
    default:
        $tempo = "Desconocida";
}

// Consultar si el anime ya existe
$sql_anime_exist = "SELECT * FROM `anime` WHERE Anime='$nombre'";
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

// Si el anime no existe, crear registros
if (mysqli_num_rows($result_anime_exist) == 0) {
    // Determinar qué tipo de registro se va a crear según el estado
    switch ($estado) {
        case "Emision":
            // Verificar si existe el horario y crearlo si no
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

            // Insertar en la tabla emision
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_emision = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`) VALUES ('$estado', '$nombre', '1', '12', '$dia_emision', '00:24:00')";
                $conn->exec($sql_insert_emision);
                $last_id_emision = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            // Insertar en la tabla anime
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_anime = "INSERT INTO anime (`id`, `Anime`, `Estado`, `Spin_Off`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`) VALUES ('$id', '$nombre', '$estado', 'NO', '$last_id_emision', 1, '$fecha', '$temp')";
                $conn->exec($sql_insert_anime);
                $last_id_anime = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            // Insertar en la tabla op si es necesario
            if (isset($_REQUEST["OP"])) {
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql_insert_op = "INSERT INTO op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) VALUES ('$nombre', '$last_id_anime', '1', '$fecha', '$temp', 'Faltante', '$mix', 'Faltante', NOW())";
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
                    $sql_insert_ed = "INSERT INTO ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) VALUES ('$nombre', '$last_id_anime', '1', '$fecha', '$temp', 'Faltante', '$mix2', 'Faltante', NOW())";
                    $conn->exec($sql_insert_ed);
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            }

            // Insertar en la tabla horario
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_horario = "INSERT INTO `horario` (`Nombre`, `Dia`, `Duracion`, `num_horario`) VALUES ('$nombre', '$dia_emision', '00:24:00', '$num_horario')";
                $conn->exec($sql_insert_horario);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
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
            // Insertar en la tabla anime
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_anime = "INSERT INTO anime (`id`, `Anime`, `Estado`, `Spin_Off`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`) VALUES ('$id', '$nombre', '$estado', 'NO', 1, 1, '$fecha', '$temp')";
                $conn->exec($sql_insert_anime);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
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
            // Insertar en la tabla emision
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_emision = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`) VALUES ('$estado', '$nombre', '1', '12', 'Indefinido', '00:24:00')";
                $conn->exec($sql_insert_emision);
                $last_id_emision = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            // Insertar en la tabla anime
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_anime = "INSERT INTO anime (`id`, `Anime`, `Estado`, `Spin_Off`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`) VALUES ('$id', '$nombre', '$estado', 'NO', '$last_id_emision', 1, '$fecha', '$temp')";
                $conn->exec($sql_insert_anime);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            // Insertar en la tabla horario
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_horario = "INSERT INTO `horario` (`Nombre`, `Dia`, `Duracion`, `num_horario`) VALUES ('$nombre', 'Indefinido', '00:24:00', '$num_horario')";
                $conn->exec($sql_insert_horario);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
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

        case "Pendiente":
            // Insertar en la tabla pendientes
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_pendientes = "INSERT INTO pendientes (`Nombre`, `Tipo`, `Vistos`, `Total`) VALUES ('$nombre $temps', 'Anime', '1', '12')";
                $conn->exec($sql_insert_pendientes);
                $last_id_pendientes = $conn->lastInsertId();
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }

            // Insertar en la tabla anime
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert_anime = "INSERT INTO anime (`id`, `Anime`, `Spin_Off`, `Estado`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`) VALUES ('$id', '$nombre', 'NO', '$estado', 1, '$last_id_pendientes', '$fecha', '$temp')";
                $conn->exec($sql_insert_anime);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
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

