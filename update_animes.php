<header>
    <!---->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</header>

<?php
include 'bd.php';

function alerta($alertTitle, $alertText, $alertType, $redireccion)
{

    echo '
    <script>
        Swal.fire({
            title: "' . $alertTitle . '",
            text: "' . $alertText . '",
            icon: "' . $alertType . '",
            confirmButtonText: "OK"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . $redireccion . '";
            }
        });
    </script>';
}

function verificarEstadoAnime($id_anime, $conexion, $servidor, $basededatos, $usuario, $password, $tempo, $num_tempo, $fecha)
{
    // Obtener el estado del anime
    $query_estado = "SELECT Nombre, Temporadas, Estado FROM `anime` WHERE id = ?";
    $stmt_estado = mysqli_prepare($conexion, $query_estado);

    // Verificar si la preparación de la consulta ha fallado
    if ($stmt_estado === false) {
        // Mostrar el error de la preparación
        die('Error al preparar la consulta: ' . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt_estado, "i", $id_anime);
    mysqli_stmt_execute($stmt_estado);

    // --- CORRECCIÓN AQUÍ: Orden alineado con el SELECT (Nombre, Temporadas, Estado) ---
    mysqli_stmt_bind_result($stmt_estado, $nombre_anime, $temporada, $estado);
    mysqli_stmt_fetch($stmt_estado);
    mysqli_stmt_close($stmt_estado);

    $nombre_temps = !empty($temporada) ? $nombre_anime . ' ' . $temporada : $nombre_anime;

    // Concatenar con un espacio entre el nombre y la temporada

    $query_emision = "UPDATE `anime` SET Ano = ?, Temporada = ? WHERE id = ?";
    $stmt_emision = mysqli_prepare($conexion, $query_emision);

    // Verificar si la preparación de la consulta ha fallado
    if ($stmt_emision === false) {
        // Mostrar el error de la preparación

        die('Error al preparar la consulta: ' . mysqli_error($conexion));
    }

    // Vincular los parámetros a la consulta
    mysqli_stmt_bind_param($stmt_emision, "isi", $fecha, $tempo, $id_anime);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt_emision)) {
        echo "Actualización exitosa.<br>";
    } else {
        echo "Actualizando el anime con los siguientes datos: Año = '.$fecha.', Temporada = $tempo, Anime ID = $id_anime";
        echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
    }

    // Si el estado no es "Finalizado", realizar las acciones
    if ($estado == "Emision") {
        echo "Estado: $estado<br>";
        echo "Nombre del Anime: $nombre_anime<br>";
        echo "Temporada: $temporada<br>";
        echo "Nombres: $nombre_temps<br>";
        // Verificar si el horario existe
        $query_num = "SELECT * FROM `num_horario` WHERE Temporada = ? AND Ano = ?";
        $stmt_num = mysqli_prepare($conexion, $query_num);
        mysqli_stmt_bind_param($stmt_num, "si", $tempo, $fecha);
        mysqli_stmt_execute($stmt_num);
        $num = mysqli_stmt_get_result($stmt_num);

        $query_temp = "SELECT * FROM `num_horario` WHERE Temporada =" . $tempo . " AND Ano =" . $fecha . "";

        echo $query_temp . "<br>";

        // Si no existe, se crea un nuevo horario
        if (mysqli_num_rows($num) == 0) {
            echo "Horario No Existe<br> Hay que crearlo y buscar el num horario<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO num_horario (`Temporada`, `Ano`) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$tempo, $fecha]);
                $num_horario = $conn->lastInsertId();
                echo "Num_ Horario: $num_horario <br>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage() . "<br>";
            } finally {
                $conn = null;
            }
        } else {
            echo "Horario Existe<br> No Hacer Nada<br>";
            $sql4 = "SELECT Num FROM `num_horario` WHERE Temporada = ? AND Ano = ?";
            $stmt = mysqli_prepare($conexion, $sql4);
            mysqli_stmt_bind_param($stmt, "si", $tempo, $fecha);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            $num_horario = 0;
            while ($valores = mysqli_fetch_array($query)) {
                $num_horario = $valores['Num'];
            }
            mysqli_stmt_close($stmt);
        }

        try {
            // Creamos la conexión para ESTE anime individual
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Buscar si ya existe el horario EXACTO para este periodo/temporada actual
            $sql6 = "SELECT * FROM `horario` WHERE ID_Anime = :id_anime AND num_horario = :num_horario ORDER BY `num_horario` DESC LIMIT 1";
            $stmt = $conn->prepare($sql6);
            $stmt->execute([':id_anime' => $id_anime, ':num_horario' => $num_horario]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                // CAMINO A: Ya existe el registro en esta temporada actual
                $dia = $info['Dia'];
                $duracion = $info['Duracion'];
                echo "<span style='color: blue;'>[EXISTE]</span> Horario encontrado para el periodo actual del Anime ID $id_anime (Día: $dia, Duración: $duracion)<br>";
            } else {
                // CAMINO B: No existe registro para esta temporada. ¡Vamos a rescatar su último historial!
                echo "<span style='color: orange;'>[NO EXISTE EN ESTE PERIODO]</span> Buscando historial antiguo para Anime ID $id_anime... ";

                // 2. Segunda búsqueda: Buscamos su último registro histórico SIN importar el num_horario
                $sqlHistorial = "SELECT Dia, Duracion FROM `horario` WHERE ID_Anime = :id_anime ORDER BY `num_horario` DESC, `id` DESC LIMIT 1";
                $stmtHistorial = $conn->prepare($sqlHistorial);
                $stmtHistorial->execute([':id_anime' => $id_anime]);
                $historial = $stmtHistorial->fetch(PDO::FETCH_ASSOC);

                if ($historial) {
                    // Si encontramos una temporada pasada, heredamos sus datos
                    $dia = $historial['Dia'];
                    $duracion = $historial['Duracion'];
                    echo "<span style='color: #8e44ad;'>[HISTORIAL ENCONTRADO]</span> Heredando del pasado (Día: $dia, Duración: $duracion)... ";
                } else {
                    // Si el anime es completamente nuevo en la base de datos y no tiene historial, usamos los valores por defecto
                    $dia = "Indefinido";
                    $duracion = "00:24:00";
                    echo "<span style='color: #7f8c8d;'>[SIN HISTORIAL]</span> Usando valores por defecto... ";
                }

                // 3. Insertar el nuevo registro con los datos rescatados (o por defecto si fue el caso)
                $sqlInsert = "INSERT INTO `horario` (`ID_Anime`, `Temporada`, `Dia`, `Duracion`, `num_horario`) VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->execute([$id_anime, $temporada, $dia, $duracion, $num_horario]);

                echo "<span style='color: green;'>¡Insertado con éxito en el periodo $num_horario!</span><br>";
            }
        } catch (PDOException $e) {
            echo "<span style='color: red;'>[ERROR en ID $id_anime]:</span> " . $e->getMessage() . "<br>";
        } finally {
            $conn = null;
        }

        echo "Si existe el anime en el horario, así que nada:<br>Demas <br>";
    }
}




//$id_anime = 771;
//$id_anime = 770;

$mes = date("F");
$año = date("Y");
$temporada = [
    'January' => ['Invierno', 1],
    'February' => ['Invierno', 1],
    'March' => ['Invierno', 1],
    'April' => ['Primavera', 2],
    'May' => ['Primavera', 2],
    'June' => ['Primavera', 2],
    'July' => ['Verano', 3],
    'August' => ['Verano', 3],
    'September' => ['Verano', 3],
    'October' => ['Otoño', 4],
    'November' => ['Otoño', 4],
    'December' => ['Otoño', 4]
];

$tempo = $temporada[$mes][0] ?? 'Desconocido';
$num_tempo = $temporada[$mes][1] ?? '5';


$query = "
SELECT * FROM `anime` WHERE `Estado`='Emision'  
ORDER BY `Ano` ASC;
";

$result = mysqli_query($conexion, $query);
if (!$result) {

    // Definir un mensaje de error con el mysqli_error para mostrar el error de la base de datos
    $alertTitle = '¡Error de Ejecucion!';
    $alertText = 'No se pudo actualizar los animes. Por favor, inténtelo de nuevo. Error de base de datos: ' . mysqli_error($conexion);  // Agregar el error de MySQL
    $alertType = 'error';

    // Validar que $link no esté vacío o mal formado
    $redireccion = 'index.php'; // Redirigir a una página predeterminada si $link no es válido

    // Llamar a la función de alerta con los parámetros adecuados
    alerta($alertTitle, $alertText, $alertType, $redireccion);

    // Detener la ejecución del script de manera controlada
    exit(); // Mejor que `die()` ya que `exit()` es más común y semánticamente adecuado

} else {
    echo "Consulta exitosa<br>";
    $conteo = 0;
    // Extraer los IDs de los animes
    $ids_animes = []; // Arreglo para guardar los IDs
    while ($row = mysqli_fetch_assoc($result)) {
        $ids_animes = $row['id']; // Agregar el ID del anime al arreglo
        echo "IDs de los animes en emisión: " . $row['id'] . " " . $row['Estado'] . "<br>";
        $conteo++; // Incrementar el conteo
        verificarEstadoAnime($ids_animes, $conexion, $servidor, $basededatos, $usuario, $password, $tempo, $num_tempo, $año);

        echo "<br><br><br><br><br>";
    }

    echo "Total de Animes en Emision: " . $conteo . "<br>";
    // Definir un mensaje de alerta para la actualización exitosa
    $alertTitle = '¡Actualización Exitosa!';
    $alertText = sprintf(
        'Se actualizaron exitosamente %d animes en emisión a la temporada %s %d.',
        $conteo,
        htmlspecialchars($tempo, ENT_QUOTES, 'UTF-8'), // Escapar valores por seguridad
        (int)$año // Asegurar que el año sea tratado como número
    );
    $alertType = 'success'; // Corregido el typo en "succes"
    $redireccion = 'index.php'; // Página de redirección

    alerta($alertTitle, $alertText, $alertType, $redireccion);

    // Mostrar los IDs de los animes

}
