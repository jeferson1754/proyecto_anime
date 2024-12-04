<!--cpmment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

// Variables
$idRegistros    = $_POST['id'];
$idEmision      = $_POST['emision'];
$idPendientes   = $_POST['pendientes'];
$nombre         = $_POST['anime'];
$temps          = $_POST['temps'];
$peli           = $_POST['peli'];
$spin           = $_POST['spin'];
$estado         = $_POST['estado'];
$fecha          = $_POST['fecha'];
$temp           = $_POST['temp'];
$link           = $_POST['link'];
$op             = $_POST['op'];
$ed             = $_POST['ed'];

$nombre_temps = $nombre . ' ' . $temps;

// Mapeo de valores de temporada
$temporadas = [
    1 => "Invierno",
    2 => "Primavera",
    3 => "Verano",
    4 => "Otoño",
    5 => "Desconocida"
];

// Obtener el nombre de la temporada
$tempo = isset($temporadas[$temp]) ? $temporadas[$temp] : "Desconocida";

// Consulta preparada para obtener los datos del anime
$sql = "SELECT * FROM `anime` WHERE id=?";
$stmt1 = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt1, "i", $idRegistros);
mysqli_stmt_execute($stmt1);
$anime = mysqli_stmt_get_result($stmt1);

// Consulta preparada para obtener los datos de la emisión
$sql1 = "SELECT * FROM `emision` WHERE ID_Emision=? AND ID_Emision > 1";
$stmt2 = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt2, "i", $idEmision);
mysqli_stmt_execute($stmt2);
$emision = mysqli_stmt_get_result($stmt2);

// Consulta preparada para obtener los datos de los pendientes
$sql2 = "SELECT * FROM `pendientes` WHERE ID_Pendientes=? AND ID_Pendientes > 1";
$stmt3 = mysqli_prepare($conexion, $sql2);
mysqli_stmt_bind_param($stmt3, "i", $idPendientes);
mysqli_stmt_execute($stmt3);
$pendientes = mysqli_stmt_get_result($stmt3);

// Consulta preparada para obtener el número de horario
$sql4 = "SELECT * FROM `num_horario` WHERE Temporada=? AND Ano=?";
$stmt4 = mysqli_prepare($conexion, $sql4);
mysqli_stmt_bind_param($stmt4, "si", $tempo, $fecha);
mysqli_stmt_execute($stmt4);
$num = mysqli_stmt_get_result($stmt4);

//IF PARA SACAR VALOR DE NUM_HORARIO
$num_horario = null;
if ($num && mysqli_num_rows($num) > 0) {
    $valores = mysqli_fetch_array($num);
    $num_horario = $valores[0];
}

// Consulta preparada para obtener los eliminados de emisión
$sql5 = "SELECT * FROM `eliminados_emision` WHERE Nombre=? LIMIT 1";
$stmt5 = mysqli_prepare($conexion, $sql5);
mysqli_stmt_bind_param($stmt5, "s", $nombre_temps);
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
        $sql4 = "SELECT Num FROM `num_horario` WHERE Temporada=? AND Ano=?";
        $stmt = mysqli_prepare($conexion, $sql4);
        mysqli_stmt_bind_param($stmt, "si", $tempo, $fecha);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
        while ($valores = mysqli_fetch_array($query)) {
            $num_horario = $valores['Num'];
        }
    }

    $sql3 = "SELECT * FROM `horario` WHERE Nombre=? AND num_horario=?";
    $stmt = mysqli_prepare($conexion, $sql3);
    mysqli_stmt_bind_param($stmt, "si", $nombre_temps, $num_horario);
    mysqli_stmt_execute($stmt);
    $horario = mysqli_stmt_get_result($stmt);

    $dia = "";
    $duracion = "";

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql6 = "SELECT * FROM `horario` WHERE Nombre LIKE ? ORDER BY `num_horario` DESC LIMIT 1";
        $stmt = $conn->prepare($sql6);
        $stmt->execute(["%$nombre%"]);

        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info) {
            $dia = $info['Dia'];
            $duracion = $info['Duracion'];
        } else {
            echo "No se encontraron resultados";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }


    echo $sql6 . "<br>";

    if ($dia == NULL or $duracion == NULL) {
        $dia = "Indefinido";
        $duracion = "00:24:00";
    }

    if (mysqli_num_rows($horario) == 0) {
        echo "No existe el anime en el horario, así que lo creo:<br>";
        try {
            $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`, `num_horario`) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre_temps, $dia, $duracion, $num_horario]);
            echo $sql . "<br>Demas<br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo $sql3 . "<br>";
        echo "Si existe el anime en el horario, así que nada:<br>Demas <br>";
    }
    echo $sql3;
    echo "<br>";
} else {
    echo "No hacer nada esta finalizado:<br>Demas <br>";
}


// Obtener el total de openings para el anime
$sql_opening = "SELECT COUNT(*) AS total FROM `op` WHERE ID_Anime=?";
$stmt_opening = $conexion->prepare($sql_opening);
$stmt_opening->bind_param("i", $idRegistros);
$stmt_opening->execute();
$result_opening = $stmt_opening->get_result();
$op1 = $result_opening->fetch_assoc()['total'];

// Obtener el total de endings para el anime
$sql_ending = "SELECT COUNT(*) AS total FROM `ed` WHERE ID_Anime=?";
$stmt_ending = $conexion->prepare($sql_ending);
$stmt_ending->bind_param("i", $idRegistros);
$stmt_ending->execute();
$result_ending = $stmt_ending->get_result();
$ed1 = $result_ending->fetch_assoc()['total'];



/*
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
echo $idEmision . "<br>";
echo $idPendientes . "<br>OP-" . $op . "<br>OP1-" . $op1 . "<br>ED-" . $ed . "<br>ED1-" . $ed1 . "<br>";
echo $mix . "<br>";
echo $mix2 . "<br>";
echo $link . "<br> Temporada: " . $tempo . "<br> ID: " . $temp . "<br>";
echo "Num_Horario:" . $num_horario . "<br>";


try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($op > $op1) {
        echo "OP Mayor a Resultado<br>";
        $sql_op = "INSERT INTO op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`, `Fecha_Ingreso`) 
        VALUES(:nombre, :idRegistros, :op, :fecha, :temp, 'Faltante', :mix, NOW())";
        $stmt_op = $conn->prepare($sql_op);
        $stmt_op->bindParam(':nombre', $nombre_temps, PDO::PARAM_STR);
        $stmt_op->bindParam(':idRegistros', $idRegistros, PDO::PARAM_INT);
        $stmt_op->bindParam(':op', $op, PDO::PARAM_INT);
        $stmt_op->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt_op->bindParam(':temp', $temp, PDO::PARAM_STR);
        $stmt_op->bindParam(':mix', $mix, PDO::PARAM_STR);
        $stmt_op->execute();
    } else {
        echo "Openings Iguales<br>";
    }

    if ($ed > $ed1) {
        echo "ED Mayor a Resultado<br>";
        $sql_ed = "INSERT INTO ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`, `Fecha_Ingreso`) 
        VALUES(:nombre, :idRegistros, :ed, :fecha, :temp, 'Faltante', :mix, NOW())";
        $stmt_ed = $conn->prepare($sql_ed);
        $stmt_ed->bindParam(':nombre', $nombre_temps, PDO::PARAM_STR);
        $stmt_ed->bindParam(':idRegistros', $idRegistros, PDO::PARAM_INT);
        $stmt_ed->bindParam(':ed', $ed, PDO::PARAM_INT);
        $stmt_ed->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt_ed->bindParam(':temp', $temp, PDO::PARAM_STR);
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

*/

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



if ($estado == "Emision" or $estado == "Pausado") {
    echo "Estado en Emision: $estado<br>";
    $ID_Pendientes = 1;
    if (mysqli_num_rows($emision) == 0) {
        if ($id_eliminados_emision != 0) {
            echo "Existe en Eliminados_Emision: $id_eliminados_emision";
            InfoSwal('El anime ' . $nombre_temps . ' tiene registros en Eliminados de Emision', './update_eliminados_emision.php?variable=' . urlencode($id_eliminados_emision) . '');
            $ID_emision = 1;
        } else {
            Swal('success', 'Creando registro de ' . $nombre_temps . ' en Emision y Actualizando en Anime', $link);
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                VALUES ( '" . $estado . "','" . $nombre_temps . "','1','12','" . $dia . "','" . $duracion . "')";
                $conn->exec($sql);
                $ID_emision = $conn->lastInsertId();
                echo $sql . "<br>";
                echo 'ID Emision:' . $ID_emision . "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
            }
        }
    } else {
        Swal('success', 'Actualizando registro de ' . $nombre_temps . ' en Emision y en Anime', $link);

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE emision SET Nombre ='" . $nombre . " " . $temps . "',Emision='" . $estado . "'
            WHERE ID_Emision='" . $idEmision . "'";
            $conn->exec($sql);
            $ID_emision = $idEmision;
            echo $sql . "<br>";
            echo 'ID Emision:' . $ID_emision . "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
    }
} else if ($estado == "Pendiente") {
    echo "Estado en Pendiente: $estado<br>";
    $ID_emision = 1;
    if (mysqli_num_rows($pendientes) == 0) {
        Swal('success', 'Creando registro de ' . $nombre_temps . ' en Pendientes y Actualizando en Anime', $link);
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO pendientes (`Nombre`,`Tipo`, `Vistos`, `Total`) 
            VALUES ( '" . $nombre_temps . "','Anime','1','12')";
            $conn->exec($sql);
            $ID_Pendientes = $conn->lastInsertId();
            echo $sql;
            echo 'ID Pendientes: ' . $ID_Pendientes;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
    } else {
        Swal('success', 'Actualizando registro de ' . $nombre_temps . ' en Pendientes y en Anime', $link);
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE pendientes SET Nombre ='" . $nombre_temps . "', Tipo='Anime'
            WHERE ID_Pendientes='" . $idPendientes . "'";
            $conn->exec($sql);
            $ID_Pendientes = $idPendientes;
            echo $sql;
            echo 'ID Pendientes: ' . $ID_Pendientes;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            echo $sql;
            $conn = null;
            echo $e;
            echo "<br>";
        }
    }

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) WHERE Vistos > 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo $stmt->rowCount() . " pendientes actualizados.";
    } catch (PDOException $e) {
        echo $e;
    } finally {
        $conn = null;
    }
} else if ($estado == "Finalizado") {
    echo "Estado en Finalizado: $estado<br>";
    $ID_Pendientes = 1;
    $ID_emision = 1;
    Swal('success', 'Actualizando registro de ' . $nombre_temps . ' en Anime', $link);
}

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE anime SET 
    Anime ='" . $nombre . "',
    Temporadas ='" . $temps . "',
    Peliculas ='" . $peli . "',
    Spin_Off ='" . $spin . "',
    Estado ='" . $estado . "',
   `id_Pendientes`='" . $ID_Pendientes . "',
   `id_Emision`='" . $ID_emision . "',
    Ano ='" . $fecha . "',
    Id_Temporada ='" . $temp . "'
    WHERE id='" . $idRegistros . "'";
    $conn->exec($sql);
    $ID_Anime = $idRegistros;
    echo $sql . "<br>";
    echo 'ID anime actualizado: ' . $ID_Anime . "<br>";
    $conn = null;

    InfoSwal('El anime ' . $nombre_temps . ' tiene registros en Eliminados de Emision', './update_eliminados_emision.php?variable=' . urlencode($id_eliminados_emision) . '');
           
} catch (PDOException $e) {
    $conn = null;
    echo $e;
}

include('pruebas.php');

//exit();
