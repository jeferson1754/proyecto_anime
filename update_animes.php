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
    mysqli_stmt_bind_result($stmt_estado, $estado, $nombre_anime, $temporada);
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
    mysqli_stmt_bind_param($stmt_emision, "iii", $fecha, $num_tempo, $id_anime);

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
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Horario Existe<br> No Hacer Nada<br>";
            $sql4 = "SELECT Num FROM `num_horario` WHERE Temporada = ? AND Ano = ?";
            $stmt = mysqli_prepare($conexion, $sql4);
            mysqli_stmt_bind_param($stmt, "si", $tempo, $fecha);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            while ($valores = mysqli_fetch_array($query)) {
                $num_horario = $valores['Num'];
            }
        }


        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql6 = "SELECT * FROM `horario` WHERE ID_Anime LIKE :id_anime ORDER BY `num_horario` DESC LIMIT 1";
            $stmt = $conn->prepare($sql6);
            $stmt->execute([':id_anime' => $id_anime]);

            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                $dia = $info['Dia'];
                $duracion = $info['Duracion'];
            } else {
                $dia = "Indefinido";
                $duracion = "00:24:00";
            }

            if (empty($info)) {

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
                    $stmt->bindValue(1, $id_anime);
                    $stmt->bindValue(2, $temporada);
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
