<!---->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idRegistros    = $_POST['id'] ?? null;
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

    // Preparar las consultas usando Prepared Statements
    $sql = "SELECT * FROM anime WHERE id_Emision = ?";
    $sql2 = "SELECT * FROM horario WHERE Nombre = ?";
    $sql3 = "SELECT * FROM emision WHERE Dia = ? AND Posicion = ?";

    // Preparar las sentencias
    $stmt_anime = $conexion->prepare($sql);
    $stmt_horario = $conexion->prepare($sql2);
    $stmt_conteo = $conexion->prepare($sql3);

    // Enlazar los parámetros
    $stmt_anime->bind_param('s', $idRegistros);
    $stmt_horario->bind_param('s', $nombre);
    $stmt_conteo->bind_param('ss', $dias, $posicion);

    // Ejecutar las consultas
    $stmt_anime->execute();
    $result_anime = $stmt_anime->get_result();

    $stmt_horario->execute();
    $result_horario = $stmt_horario->get_result();

    $stmt_conteo->execute();
    $result_conteo = $stmt_conteo->get_result();

    // Depuración de las consultas (opcional)
    echo $stmt_horario->error . "<br>";
    echo $stmt_conteo->error . "<br>";

    // Procesar resultados
    while ($fila = $result_anime->fetch_assoc()) {
        $temps = $fila["Id_Temporada"];
        $fecha = $fila["Ano"];
        $IdAnime = $fila["id"];
    }

    // Obtener el nombre de la temporada
    $tempo = isset($temporadas[$temps]) ? $temporadas[$temps] : "Desconocida";


    $sql = "
    SELECT 'opening' AS tipo, COUNT(*) AS total FROM `op` WHERE ID_Anime = ?
    UNION ALL
    SELECT 'ending' AS tipo, COUNT(*) AS total FROM `ed` WHERE ID_Anime = ?
";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $IdAnime, $IdAnime);
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
            $sql = "INSERT INTO horario (Nombre, Dia, Duracion, Temporada, Ano) 
                VALUES (:nombre, :dias, :duracion, :tempo, :fecha)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre'   => $nombre,
                ':dias'     => $dias,
                ':duracion' => $duracion,
                ':tempo'    => $tempo,
                ':fecha'    => $fecha,
            ]);
            echo "Nuevo horario insertado: $nombre<br>";
        } else {
            // Actualizar horario existente
            $sql = "UPDATE horario 
                SET Dia = :dias, Duracion = :duracion 
                WHERE Nombre = :nombre 
                ORDER BY ID DESC 
                LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':dias'     => $dias,
                ':duracion' => $duracion,
                ':nombre'   => $nombre,
            ]);
            echo "Horario actualizado: $nombre<br>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    try {
        // Crear la conexión PDO
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si el conteo existe o la posición es igual a 0
        if (mysqli_num_rows($result_conteo) == 0 || $posicion == 0) {
            // Actualizar la posición
            $sql = "UPDATE emision SET Posicion = :posicion WHERE ID_Emision = :idEmision";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':posicion'  => $posicion,
                ':idEmision' => $idRegistros,
            ]);
        } else {
            $alertTitle = '¡Error!';
            $alertText = 'Posición N° ' . $posicion . ' Repetida o Errónea';
            $alertType = 'error';
            $redireccion = "window.location='javascript:history.back()'";
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
        $alertText = 'No se puede editar ' . $nombre . ' porque no existe en anime';
        $alertType = 'error';
        $redireccion = "window.location='javascript:history.back()'";
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
                    Emision = '$estado',
                    Dia = '$dias',
                    Duracion = '$duracion'
                    WHERE ID_Emision = '$idRegistros'";
                    executeQuery($conn, $sqlEmision);

                    $sqlAnime = "UPDATE anime SET 
                    Estado = '$estado'
                    WHERE id_Emision = '$idRegistros'";
                    executeQuery($conn, $sqlAnime);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Actualizando registro en Emisión de ' . $nombre . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
                    break;

                case "Pausado":
                    echo "Anime Pausado<br>";

                    $sqlPausado = "UPDATE emision SET 
                    Capitulos = '$caps',
                    Faltantes = $caps + $faltantes,
                    Totales = '$total',
                    Dia = '$dias',
                    Emision = '$estado',
                    Duracion = '$duracion'
                    WHERE ID_Emision = '$idRegistros'";
                    executeQuery($conn, $sqlPausado);

                    $sqlAnimePausado = "UPDATE anime SET 
                    Estado = '$estado'
                    WHERE id_Emision = '$idRegistros'";
                    executeQuery($conn, $sqlAnimePausado);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Actualizando registro en Emisión de ' . $nombre . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
                    break;

                case "Pendiente":
                    echo "Anime Pendiente<br>";

                    $sqlPendientes = "INSERT INTO pendientes (Nombre, Tipo, Vistos, Total, Estado_Link) 
                    VALUES ('$nombre', 'Anime', '$caps', '$total', 'Faltante')";
                    executeQuery($conn, $sqlPendientes);

                    $last_id1 = $conn->lastInsertId();
                    echo 'Último anime insertado: ' . $last_id1 . "<br>";

                    $sqlUpdatePendientes = "UPDATE pendientes SET 
                    Pendientes = (Total - Vistos) 
                    WHERE Vistos > 0";
                    executeQuery($conn, $sqlUpdatePendientes);

                    $sqlDeleteEmision = "DELETE FROM emision 
                    WHERE ID_Emision = '$idRegistros'";
                    executeQuery($conn, $sqlDeleteEmision);

                    $sqlUpdateAnime = "UPDATE anime SET 
                    Estado = 'Pendiente',
                    id_Emision = 1,
                    id_Pendientes = '$last_id1'
                    WHERE id = '$IdAnime'";
                    executeQuery($conn, $sqlUpdateAnime);

                    $alertTitle = 'Edicion Exitosa!';
                    $alertText = 'Creando registro en Pendientes, Eliminando en Emisión y Actualizando en Anime de ' . $nombre . '';
                    $alertType = 'success';
                    $redireccion = "window.location='$link'";
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

    echo $redireccion;
}
?>