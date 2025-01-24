<!--comment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

// Variables
$idRegistros    = $_POST['id'];
$nombre         = $_POST['anime'];
$temps          = $_POST['temps'];
$peli           = $_POST['peli'];
$spin           = $_POST['spin'];
$estado         = $_POST['estado'];
$fecha          = $_POST['fecha'];
$tempo           = $_POST['temp'];
$link           = $_POST['link'];
$op             = $_POST['op'];
$ed             = $_POST['ed'];
$op1             = $_POST['op_total'];
$ed1             = $_POST['ed_total'];

function Swal($icon, $title, $location)
{
    echo '<script>
    Swal.fire({
        icon: "' . $icon . '",
        title: "' . $title . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $location . '";
    });
    </script>';
}

function InfoSwal($title, $location)
{
    echo '<script>
    Swal.fire({
        icon: "info",
        title: "' . $title . '",
        showCancelButton: true,
        confirmButtonText: "Quiero ocuparlos",
        cancelButtonText: "No quiero ocuparlos"
      }).then((result) => {
        if (result.isConfirmed) {
            window.location = "' . $location . '";
        } else if (result.isDenied) {
          Swal.fire("Changes are not saved", "", "info");
        }
      });
      </script>';
}


// Consulta preparada para obtener los datos del anime
$sql = "SELECT * FROM `anime` WHERE id=?";
$stmt1 = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt1, "i", $idRegistros);
mysqli_stmt_execute($stmt1);
$anime = mysqli_stmt_get_result($stmt1);

// Consulta preparada para obtener los datos de la emisión
$sql1 = "SELECT * FROM `emision` WHERE ID_Anime=? ";
$stmt2 = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt2, "i", $idRegistros);
mysqli_stmt_execute($stmt2);
$emision = mysqli_stmt_get_result($stmt2);

// Consulta preparada para obtener los datos de los pendientes
$sql2 = "SELECT * FROM `pendientes` WHERE ID_Anime=?";
$stmt3 = mysqli_prepare($conexion, $sql2);
mysqli_stmt_bind_param($stmt3, "i", $idRegistros);
mysqli_stmt_execute($stmt3);
$pendientes = mysqli_stmt_get_result($stmt3);

// Consulta preparada para obtener el número de horario
$sql4 = "SELECT * FROM `num_horario` WHERE Temporada=? AND Ano=?";
$stmt4 = mysqli_prepare($conexion, $sql4);
mysqli_stmt_bind_param($stmt4, "si", $tempo, $fecha);
mysqli_stmt_execute($stmt4);
$num = mysqli_stmt_get_result($stmt4);

//IF PARA SACAR VALOR DE NUM_HORARIO
$num_horario = "";
if ($num && mysqli_num_rows($num) > 0) {
    $valores = mysqli_fetch_array($num);
    $num_horario = $valores[0];
}

// Consulta preparada para obtener los eliminados de emisión
$sql5 = "SELECT * FROM `eliminados_emision` WHERE ID_Anime=? LIMIT 1";
$stmt5 = mysqli_prepare($conexion, $sql5);
mysqli_stmt_bind_param($stmt5, "i", $id_Registros);
mysqli_stmt_execute($stmt5);
$eliminados_emision = mysqli_stmt_get_result($stmt5);

//IF PARA SACAR VALOR ID ELIMINADOS EMISION
$id_eliminados_emision = null;
if ($eliminados_emision && mysqli_num_rows($eliminados_emision) > 0) {
    $mostrar = mysqli_fetch_array($eliminados_emision);
    $id_eliminados_emision = $mostrar['ID'];
} else {
    $id_eliminados_emision = "0";
}

try {
    // Conexión a la base de datos usando PDO
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta 1: Buscar horario por temporada y número de horario
    $sql3 = "SELECT * FROM `horario` WHERE Temporada = :temporada AND num_horario = :num_horario";
    $stmt = $conn->prepare($sql3);
    $stmt->execute([
        ':temporada' => $temps,
        ':num_horario' => $num_horario,
    ]);

    $horario = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


if ($estado != "Finalizado") {

    if (mysqli_num_rows($num) == 0) {
        echo "Horario No Existe<br> Hay que crearlo y buscar el num horario<br> ";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO num_horario (`Temporada`, `Ano`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$tempo, $fecha]);
            $num_horario = $conn->lastInsertId();
            echo "Num_ Horario: $num_horario <br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Horario Existe<br> No Hacer Nada<br> ";
    }


    try {

        $sql6 = "SELECT * FROM `horario` WHERE ID_Anime LIKE :id_anime ORDER BY `num_horario` DESC LIMIT 1";
        $stmt = $conn->prepare($sql6);
        $stmt->execute([':id_anime' => $id_Registros]);

        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info) {
            $dia = $info['Dia'];
            $duracion = $info['Duracion'];
        } else {
            $dia = "Indefinido";
            $duracion = "00:24:00";
        }

        if (empty($horario)) {

            echo "No existe el anime en el horario, así que lo creo:<br>";
            // Consulta 2: Buscar horario por nombre si no hay resultados previos


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Consulta SQL para insertar datos
                $sql = "INSERT INTO `horario`( `ID_Anime`,`Temporada`, `Dia`, `Duracion`, `num_horario`) VALUES (?, ?, ?, ?, ?)";

                // Preparar la consulta
                $stmt = $conn->prepare($sql);

                // Vincular los parámetros de forma individual
                $stmt->bindValue(1, $id_Registros);
                $stmt->bindValue(2, $temps);
                $stmt->bindValue(3, $dia);
                $stmt->bindValue(4, $duracion);
                $stmt->bindValue(5, $num_horario);

                // Ejecutar la consulta
                $stmt->execute();

                echo $sql . "<br>Demas<br>";
            } catch (PDOException $e) {
                // Captura cualquier error y lo muestra
                echo "Error: " . $e->getMessage();
            }
        }
        // Puedes usar $dia y $duracion aquí si es necesario
        echo "Día: $dia, Duración: $duracion";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo "Si existe el anime en el horario, así que nada:<br>Demas <br>";

    echo $sql3;
    echo "<br>";
} else {
    echo "No hacer nada esta finalizado:<br>Demas <br>";
}


// Obtener el mix más reciente
$sql_mix = "SELECT * FROM mix ORDER BY ID DESC LIMIT 1";
$result_mix = $conexion->query($sql_mix);
$mix = $result_mix->fetch_assoc()['ID'];

// Obtener el mix de ending más reciente
$sql_mix_ed = "SELECT * FROM mix_ed ORDER BY ID DESC LIMIT 1";
$result_mix_ed = $conexion->query($sql_mix_ed);
$mix2 = $result_mix_ed->fetch_assoc()['ID'];

// Mostrar información para depuración
echo $sql1 . "<br>";
echo $sql2 . "<br>";
echo $sql4 . "<br>";
echo $sql5 . "<br>";
echo "OP-" . $op . "<br>OP1-" . $op1 . "<br>ED-" . $ed . "<br>ED1-" . $ed1 . "<br>";
echo $mix . "<br>";
echo $mix2 . "<br>";
echo $link . "<br> Temporada: " . $tempo . "<br> ";
echo "Num_Horario:" . $num_horario . "<br>";


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($op > $op1) {
        echo "OP Mayor a Resultado<br>";
        $sql_op = "INSERT INTO op (`Temporada`, `ID_Anime`, `Opening`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Fecha_Ingreso`) 
        VALUES(:nombre, :idRegistros, :op, :fecha, :temp, 'Faltante', :mix, NOW())";
        $stmt_op = $conn->prepare(query: $sql_op);
        $stmt_op->bindParam(':nombre', $temps, PDO::PARAM_STR);
        $stmt_op->bindParam(':idRegistros', $idRegistros, PDO::PARAM_INT);
        $stmt_op->bindParam(':op', $op, PDO::PARAM_INT);
        $stmt_op->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt_op->bindParam(':temp', $tempo, PDO::PARAM_STR);
        $stmt_op->bindParam(':mix', $mix, PDO::PARAM_STR);
        $stmt_op->execute();
    } else {
        echo "Openings Iguales<br>";
    }

    if ($ed > $ed1) {
        echo "ED Mayor a Resultado<br>";
        $sql_ed = "INSERT INTO ed (`Temporada`, `ID_Anime`, `Ending`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Fecha_Ingreso`) 
        VALUES(:nombre, :idRegistros, :ed, :fecha, :temp, 'Faltante', :mix, NOW())";
        $stmt_ed = $conn->prepare($sql_ed);
        $stmt_ed->bindParam(':nombre', $temps, PDO::PARAM_STR);
        $stmt_ed->bindParam(':idRegistros', $idRegistros, PDO::PARAM_INT);
        $stmt_ed->bindParam(':ed', $ed, PDO::PARAM_INT);
        $stmt_ed->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt_ed->bindParam(':temp', $tempo, PDO::PARAM_STR);
        $stmt_ed->bindParam(':mix', $mix2, PDO::PARAM_STR);
        $stmt_ed->execute();
    } else {
        echo "Endings Iguales<br>";
    }

    $conn = null;
} catch (PDOException $e) {
    echo $e;
}
echo "<br>";




if ($estado == "Emision" or $estado == "Pausado") {
    echo "Estado en Emision: $estado<br>";
    if (mysqli_num_rows($emision) == 0) {
        if ($id_eliminados_emision != 0) {
            echo "Existe en Eliminados_Emision: $id_eliminados_emision";
            InfoSwal('El anime ' . $nombre_temps . ' tiene registros en Eliminados de Emision', './update_eliminados_emision.php?variable=' . urlencode($id_eliminados_emision) . '');
            $ID_emision = 1;
        } else {

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO emision (`ID_Anime`, `Temporada`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                VALUES ( '" . $idRegistros . "','" . $temps . "','1','12','" . $dia . "','" . $duracion . "')";
                $conn->exec($sql);
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }
            Swal('success', 'Creando registro de ' . $nombre . ' en Emision y Actualizando en Anime', $link);
        }
    } else {
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE emision SET Temporada ='" . $temps . "'
            WHERE ID_Anime='" . $idRegistros . "'";
            $conn->exec($sql);
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        Swal('success', 'Actualizando registro de ' . $nombre . ' en Emision y en Anime', $link);
    }
} else if ($estado == "Pendiente") {
    echo "Estado en Pendiente: $estado<br>";
    if (mysqli_num_rows($pendientes) == 0) {

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO pendientes (`ID_Anime`,`Temporada`,`Tipo`, `Vistos`, `Total`,`Pendientes`) 
            VALUES ( '" . $idRegistros . "', '" . $temps . "','Anime','1','12','11')";
            $conn->exec($sql);
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        Swal('success', 'Creando registro de ' . $nombre . ' en Pendientes y Actualizando en Anime', $link);
    } else {

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE pendientes SET Temporada ='" . $temps . "'
            WHERE ID_Anime='" . $idRegistros . "'";
            $conn->exec($sql);
            $conn = null;
        } catch (PDOException $e) {
            echo $sql;
            $conn = null;
            echo $e;
            echo "<br>";
        }

        Swal('success', 'Actualizando registro de ' . $nombre . ' en Pendientes y en Anime', $link);
    }
} else if ($estado == "Finalizado") {
    echo "Estado en Finalizado: $estado<br>";
    Swal('success', 'Actualizando registro de ' . $nombre . ' en Anime', $link);
}

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE anime SET 
    Nombre ='" . $nombre . "',
    Temporadas ='" . $temps . "',
    Peliculas ='" . $peli . "',
    Spin_Off ='" . $spin . "',
    Estado ='" . $estado . "',
    Ano ='" . $fecha . "',
    Temporada ='" . $tempo . "'
    WHERE id='" . $idRegistros . "'";
    $conn->exec($sql);
    $ID_Anime = $idRegistros;
    echo $sql . "<br>";
    echo 'ID anime actualizado: ' . $ID_Anime . "<br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

exit();
