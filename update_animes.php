<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

// Que los errores de MySQL lancen excepciones en vez de ignorarse en silencio
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/** Codifica un valor de PHP para insertarlo de forma segura dentro de un <script>. */
function js($valor)
{
    return json_encode($valor, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function alerta($titulo, $texto, $tipo, $redireccion)
{
    echo '
    <script>
        Swal.fire({
            title: ' . js($titulo) . ',
            text: ' . js($texto) . ',
            icon: ' . js($tipo) . ',
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = ' . js($redireccion) . ';
        });
    </script>';
}

/**
 * Devuelve el Num del periodo (temporada + año). Si no existe, lo crea.
 * Se llama UNA sola vez por ejecución: es el mismo valor para todos los animes.
 *
 * El INSERT ... ON DUPLICATE KEY UPDATE solo actúa como protección extra cuando
 * existe el índice UNIQUE (Temporada, Ano). Sin ese índice funciona como un INSERT normal.
 */
function obtenerNumHorario(mysqli $conexion, string $tempo, int $ano): int
{
    $stmt = mysqli_prepare($conexion, "SELECT Num FROM num_horario WHERE Temporada = ? AND Ano = ? ORDER BY Num ASC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "si", $tempo, $ano);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $num);
    $existe = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($existe) {
        return (int)$num;
    }

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO num_horario (Temporada, Ano) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE Num = LAST_INSERT_ID(Num)"
    );
    mysqli_stmt_bind_param($stmt, "si", $tempo, $ano);
    mysqli_stmt_execute($stmt);
    $num = (int)mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);

    return $num;
}

/**
 * Asegura que el anime tenga horario en el periodo actual.
 * Si no lo tiene, hereda Día y Duración de su último horario histórico.
 * Devuelve un texto con lo que hizo (para el log en pantalla).
 */
function asegurarHorario(mysqli $conexion, int $id_anime, int $num_horario, string $tempo): string
{
    // 1) ¿Ya tiene horario en este periodo?
    $dia = null;
    $duracion = null;
    $stmt = mysqli_prepare($conexion, "SELECT Dia, Duracion FROM horario WHERE ID_Anime = ? AND num_horario = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $id_anime, $num_horario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dia, $duracion);
    $existe = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($existe) {
        return "ya tenía horario en este periodo (Día: $dia, Duración: $duracion)";
    }

    // 2) Buscar su último horario histórico, sin importar el periodo
    $dia = null;
    $duracion = null;
    $stmt = mysqli_prepare($conexion, "SELECT Dia, Duracion FROM horario WHERE ID_Anime = ? ORDER BY num_horario DESC, id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id_anime);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dia, $duracion);
    $hayHistorial = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($hayHistorial) {
        $origen = "heredado del historial";
    } else {
        $dia = "Indefinido";
        $duracion = "00:24:00";
        $origen = "sin historial, valores por defecto";
    }

    // 3) Insertar el horario del periodo actual
    $stmt = mysqli_prepare($conexion, "INSERT INTO horario (ID_Anime, Temporada, Dia, Duracion, num_horario) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssi", $id_anime, $tempo, $dia, $duracion, $num_horario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return "horario creado, $origen (Día: $dia, Duración: $duracion)";
}

// ---------------------------------------------------------------------------
// Temporada actual
// ---------------------------------------------------------------------------
$mes = date("F");
$ano = (int)date("Y");

$mapa_temporadas = [
    'January'   => 'Invierno',
    'February'  => 'Invierno',
    'March'     => 'Invierno',
    'April'     => 'Primavera',
    'May'       => 'Primavera',
    'June'      => 'Primavera',
    'July'      => 'Verano',
    'August'    => 'Verano',
    'September' => 'Verano',
    'October'   => 'Otoño',
    'November'  => 'Otoño',
    'December'  => 'Otoño',
];
$tempo = $mapa_temporadas[$mes] ?? 'Desconocido';

// ---------------------------------------------------------------------------
// Proceso principal
// ---------------------------------------------------------------------------
try {
    // num_horario se resuelve UNA vez, antes del bucle
    $num_horario = obtenerNumHorario($conexion, $tempo, $ano);
    echo "Periodo: " . htmlspecialchars($tempo, ENT_QUOTES, 'UTF-8') . " $ano (num_horario = $num_horario)<br><br>";

    $result = mysqli_query($conexion, "SELECT id, Nombre FROM anime WHERE Estado = 'Emision' ORDER BY id ASC");

    $upd = mysqli_prepare($conexion, "UPDATE anime SET Ano = ?, Temporada = ? WHERE id = ?");

    $conteo = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['id'];

        mysqli_stmt_bind_param($upd, "isi", $ano, $tempo, $id);
        mysqli_stmt_execute($upd);

        $detalle = asegurarHorario($conexion, $id, $num_horario, $tempo);

        echo "[$id] " . htmlspecialchars($row['Nombre'], ENT_QUOTES, 'UTF-8') . ": " . htmlspecialchars($detalle, ENT_QUOTES, 'UTF-8') . "<br>";
        $conteo++;
    }
    mysqli_stmt_close($upd);

    echo "<br>Total de animes en emisión: $conteo<br>";

    alerta(
        '¡Actualización Exitosa!',
        sprintf('Se actualizaron exitosamente %d animes en emisión a la temporada %s %d.', $conteo, $tempo, $ano),
        'success',
        'index.php'
    );
} catch (Throwable $e) {
    alerta(
        '¡Error de Ejecución!',
        'No se pudo actualizar los animes. Error: ' . $e->getMessage(),
        'error',
        'index.php'
    );
    exit();
}