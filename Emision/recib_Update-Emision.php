<!---->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';

function alerta($alertTitle, $alertText, $alertType, $redireccion)
{

    echo '
 <script>
        Swal.fire({
            title: "' . $alertTitle . '",
            text: "' . $alertText . '",
            html: "' . $alertText . '",
            icon: "' . $alertType . '",
            showCancelButton: false,
            confirmButtonText: "OK",
            closeOnConfirm: false
        }).then(function() {
          ' . $redireccion . '  ; // Redirigir a la página principal
        });
    </script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idRegistros    = $_POST['id'] ?? null;
    $ID_Anime    = $_POST['id_anime'] ?? null;
    $caps           = $_POST['caps'] ?? null;
    $faltantes      = $_POST['faltantes'] ?? null;
    $total          = $_POST['total'] ?? null;
    $dias           = $_POST['dias'] ?? null;
    $nombre         = $_POST['nombre'] ?? null;
    $duracion       = $_POST['duracion'] ?? null;
    $accion         = $_REQUEST['accion'] ?? null;
    $estado         = $_REQUEST['estado'] ?? null;
    $link           = $_REQUEST['link'] ?? null;
    $op             = $_POST['op'] ?? null;
    $ed             = $_POST['ed'] ?? null;
    $posicion       = $_POST['posicion'] ?? null;
    $posicion_antigua       = $_POST['posicion_antigua'] ?? null;
    $nombre_alerta       = $_POST['nombre_alerta'] ?? null;

    $nombreEscapado = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

    echo $nombre;

    // Preparar las consultas usando Prepared Statements
    $sql = "SELECT * FROM anime WHERE ID = ?";
    $sql2 = "SELECT * FROM horario WHERE ID_Anime = ?";
    $sql3 = "SELECT * FROM emision WHERE ID = ? AND Dia = ?";


    // Preparar las sentencias
    $stmt_anime = $conexion->prepare($sql);

    $stmt_conteo = $conexion->prepare($sql3);

    // Enlazar los parámetros
    $stmt_anime->bind_param('i', $ID_Anime);

    $stmt_conteo->bind_param('is', $idRegistros, $dia);

    // Ejecutar las consultas
    $stmt_anime->execute();
    $result_anime = $stmt_anime->get_result();



    $stmt_conteo->execute();
    $result_conteo = $stmt_conteo->get_result();


    echo $stmt_conteo->error . "<br>";

    // Procesar resultados
    while ($fila = $result_anime->fetch_assoc()) {
        $tempo = $fila["Temporada"];
        $fecha = $fila["Ano"];
    }


    $sql4 = "SELECT * FROM `num_horario` WHERE Temporada= ? AND Ano= ?";
    $stmt_num = $conexion->prepare($sql4);
    $stmt_num->bind_param('ss', $tempo, $fecha);
    $stmt_num->execute();
    $result_num = $stmt_num->get_result();

    while ($fila = $result_num->fetch_assoc()) {
        $num = $fila["Num"];
    }
    echo $num;

    $sql2 = "SELECT * FROM horario WHERE ID_Anime = ? AND num_horario = ?";

    $stmt_horario = $conexion->prepare($sql2);
    $stmt_horario->bind_param('ii', $ID_Anime, $num);
    $stmt_horario->execute();
    $result_horario = $stmt_horario->get_result();
    // Depuración de las consultas (opcional)
    echo $stmt_horario->error . "<br>";




    $sql = "
    SELECT 'opening' AS tipo, COUNT(*) AS total FROM `op` WHERE ID_Anime = ?
    UNION ALL
    SELECT 'ending' AS tipo, COUNT(*) AS total FROM `ed` WHERE ID_Anime = ?
";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $ID_Anime, $ID_Anime);
    $stmt->execute();
    $result = $stmt->get_result();

    $op1 = 0;
    $ed1 = 0;

    while ($row = $result->fetch_assoc()) {
        if ($row['tipo'] === 'opening') {
            $op1 = $row['total'];
        } elseif ($row['tipo'] === 'ending') {
            $ed1 = $row['total'];
        }
    }



    include('../pruebas.php');


    try {
        // Crear la conexión PDO
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Comprobar si existe el horario
        if (mysqli_num_rows($result_horario) == 0) {
            // Insertar nuevo horario
            $sql = "INSERT INTO horario (ID_Anime, Temporada, Dia, Duracion, num_horario) 
                VALUES (:id_anime, :temporada, :dias, :duracion, :num)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_anime'   => $ID_Anime,
                ':temporada'  => $nombre,
                ':dias'       => $dias,
                ':duracion'   => $duracion,
                ':num'        => $num
            ]);
            echo "Nuevo horario insertado: $nombreEscapado<br>";
        } else {
            // Actualizar horario existente
            $sql = "UPDATE horario 
                SET Dia = :dias, 
                    Duracion = :duracion,
                    Temporada = :temporada
                WHERE ID_Anime = :id_anime 
                ORDER BY ID DESC 
                LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':dias'       => $dias,
                ':duracion'   => $duracion,
                ':temporada'  => $nombre,
                ':id_anime'   => $ID_Anime
            ]);
            echo "Horario actualizado: $nombreEscapado<br>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    try {
        // Crear la conexión PDO
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($posicion != $posicion_antigua) {
            // Verificar si el conteo existe o la posición es igual a 0
            if (mysqli_num_rows($result_conteo) == 0) {
                // Actualizar la posición
                $sql = "UPDATE emision SET Posicion = :posicion WHERE ID = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':posicion'  => $posicion,
                    ':id' => $idRegistros,
                ]);
            } else {
                $alertTitle = '¡Error!';
                $alertText = 'Posición N° ' . $posicion . ' Repetida o Errónea';
                $alertType = 'error';
                $redireccion = "window.location='javascript:history.back()'";

                alerta($alertTitle, $alertText, $alertType, $redireccion);
                die();
            }
        } else {
            echo "Misma Posicion<br>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    } finally {
        // Cerrar la conexión
        $conn = null;
    }

    function executeQuery($conn, $sql)
    {
        try {
            $conn->exec($sql);
            if (!empty($successMsg)) {
                echo $successMsg . "<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    if (mysqli_num_rows($result_anime) == 0) {
        $alertTitle = '¡Error!';
        $alertText = 'No se puede editar ' . $nombre_alerta . ' porque no existe en anime';
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die();
    } else {
        echo "Existe en Anime<br>";

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            switch ($estado) {
                case "Emision":
                    echo "Anime en Emisión<br>";

                    $sqlEmision = "UPDATE emision SET 
                    Capitulos = '$caps',
                    Faltantes = $caps + $faltantes,
                    Totales = '$total',
                    Dia = '$dias',
                    Duracion = '$duracion'
                    WHERE ID = '$idRegistros'";
                    executeQuery($conn, $sqlEmision);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Actualizando registro en Emisión de ' . $nombre_alerta . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    break;

                case "Pausado":
                    echo "Anime Pausado<br>";

                    $sqlPausado = "UPDATE emision SET 
                    Capitulos = '$caps',
                    Faltantes = $caps + $faltantes,
                    Totales = '$total',
                    Dia = '$dias',
                    Duracion = '$duracion',
                    Posicion ='0'
                    WHERE ID = '$idRegistros'";
                    executeQuery($conn, $sqlPausado);

                    $sqlAnimePausado = "UPDATE anime SET 
                    Estado = '$estado'
                    WHERE id = '$ID_Anime'";
                    executeQuery($conn, $sqlAnimePausado);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Actualizando registro en Emisión de ' . $nombre_alerta . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    break;

                case "Pendiente":
                    echo "Anime Pendiente<br>";

                    $sqlPendientes = "INSERT INTO pendientes (ID_Anime, Temporada, Tipo, Vistos, Total, Estado_Link) 
                    VALUES ('$ID_Anime','$nombre', 'Anime', '$caps', '$total', 'Faltante')";
                    executeQuery($conn, $sqlPendientes);

                    $sqlUpdatePendientes = "UPDATE pendientes SET 
                    Pendientes = (Total - Vistos) 
                    WHERE Vistos > 0";
                    executeQuery($conn, $sqlUpdatePendientes);

                    $sqlDeleteEmision = "DELETE FROM emision 
                    WHERE ID = '$idRegistros'";
                    executeQuery($conn, $sqlDeleteEmision);

                    $sqlUpdateAnime = "UPDATE anime SET 
                    Estado = 'Pendiente'
                    WHERE id = '$ID_Anime'";
                    executeQuery($conn, $sqlUpdateAnime);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Creando registro en Pendientes, Eliminando en Emisión y Actualizando en Anime de ' . $nombre_alerta . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
                    alerta($alertTitle, $alertText, $alertType, $redireccion);
                    break;

                default:
                    echo "Estado no reconocido<br>";
                    break;
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . "<br>";
        } finally {
            $conn = null;
        }
    }
}
?>