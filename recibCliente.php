<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include('bd.php');

// Función auxiliar para mostrar alertas SweetAlert2 de forma segura
function mostrarAlerta($icon, $title, $link)
{
    echo '<script>
    Swal.fire({
        icon: ' . json_encode($icon) . ',
        title: ' . json_encode($title) . ',
        confirmButtonText: "OK"
    }).then(function() {
        window.location = ' . json_encode($link) . ';
    });
    </script>';
    exit();
}

// 1. Validar que se haya enviado por POST y que los campos obligatorios no vengan vacíos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$id                 = trim($_POST['id'] ?? '');
$nombre             = trim($_POST['anime'] ?? '');
$estado             = trim($_POST['estado'] ?? '');
$tempo              = trim($_POST['temp'] ?? '');
$fecha              = trim($_POST['fecha'] ?? '');
$dia_emision        = trim($_POST['dias'] ?? '');
$link               = trim($_POST['link'] ?? 'index.php');
$tipo_anime         = trim($_POST['tipo_anime'] ?? '');
$id_anime_principal = trim($_POST['id_anime_principal'] ?? '');

// Evitar la creación de registros fantasma si el nombre o estado vienen vacíos
if ($nombre === '' || $estado === '') {
    mostrarAlerta('error', 'El nombre y el estado del anime son obligatorios', $link);
}

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Consultar si el anime ya existe
    $stmt_exist = $conn->prepare("SELECT COUNT(*) FROM `anime` WHERE Nombre = ?");
    $stmt_exist->execute([$nombre]);
    if ($stmt_exist->fetchColumn() > 0) {
        mostrarAlerta('error', "Registro de $nombre ya existe en Anime", $link);
    }

    // 3. Obtener el valor máximo de mix y mix_ed
    $mix = $conn->query("SELECT ID FROM mix ORDER BY ID DESC LIMIT 1")->fetchColumn();
    $mix2 = $conn->query("SELECT ID FROM mix_ed ORDER BY ID DESC LIMIT 1")->fetchColumn();

    // Iniciar transacción para garantizar integridad entre tablas
    $conn->beginTransaction();

    // 4. Verificar o crear num_horario
    $stmt_horario = $conn->prepare("SELECT Num FROM `num_horario` WHERE Temporada = ? AND Ano = ? LIMIT 1");
    $stmt_horario->execute([$tempo, $fecha]);
    $num_horario = $stmt_horario->fetchColumn();

    if ($num_horario === false) {
        $stmt_insert_num = $conn->prepare("INSERT INTO num_horario (`Temporada`, `Ano`) VALUES (?, ?)");
        $stmt_insert_num->execute([$tempo, $fecha]);
        $num_horario = $conn->lastInsertId();
    }

    // 5. Insertar en la tabla anime
    // Si $id viene vacío o 0, pasamos null para que MySQL use el AUTO_INCREMENT correctamente
    $id_insertar = ($id !== '' && $id !== '0') ? (int)$id : null;
    $ano_insertar = ($fecha !== '') ? (int)$fecha : null;

    $stmt_anime = $conn->prepare(
        "INSERT INTO anime (`id`, `Nombre`, `Estado`, `Ano`, `Temporada`, `TipoAnime`) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt_anime->execute([$id_insertar, $nombre, $estado, $ano_insertar, $tempo, $tipo_anime]);

    $last_id_anime = $conn->lastInsertId();
    if (!$last_id_anime && $id_insertar !== null) {
        $last_id_anime = $id_insertar;
    }

    // 6. Insertar en tablas secundarias según el estado
    $mensaje_exito = "Creando registro de $nombre en Anime";

    switch ($estado) {
        case "Emision":
        case "Pausado":
            $stmt_emision = $conn->prepare(
                "INSERT INTO emision (`ID_Anime`, `Capitulos`, `Totales`, `Dia`, `Duracion`) 
                 VALUES (?, '1', '12', ?, '00:24:00')"
            );
            $stmt_emision->execute([$last_id_anime, $dia_emision]);

            $stmt_hor = $conn->prepare(
                "INSERT INTO `horario` (`ID_Anime`, `Dia`, `Duracion`, `num_horario`) 
                 VALUES (?, ?, '00:24:00', ?)"
            );
            $stmt_hor->execute([$last_id_anime, $dia_emision, $num_horario]);

            $mensaje_exito = "Creando registro de $nombre en Anime y en Emision";
            break;

        case "Pendiente":
            $stmt_pend = $conn->prepare(
                "INSERT INTO pendientes (`ID_Anime`, `Tipo`, `Vistos`, `Total`, `Pendientes`) 
                 VALUES (?, 'Anime', '1', '12', '11')"
            );
            $stmt_pend->execute([$last_id_anime]);

            $mensaje_exito = "Creando registro de $nombre en Anime y en Pendientes";
            break;

        case "Finalizado":
        case "Viendo":
        default:
            // Solo crea el registro principal en anime
            break;
    }

    // 7. Insertar relación si se seleccionó un anime principal
    if ($id_anime_principal !== '') {
        $stmt_rel = $conn->prepare(
            "INSERT INTO anime_relaciones (`id_anime_origen`, `id_anime_destino`, `tipo_relacion`) 
             VALUES (?, ?, ?)"
        );
        $stmt_rel->execute([$last_id_anime, $id_anime_principal, $tipo_anime]);
    }

    // 8. Eliminar el ID disponible de id_anime
    if ($id_insertar !== null) {
        $stmt_del = $conn->prepare("DELETE FROM `id_anime` WHERE `ID` = ?");
        $stmt_del->execute([$id_insertar]);
    }

    // 9. Insertar en op si fue marcado
    if (isset($_POST["OP"])) {
        $stmt_op = $conn->prepare(
            "INSERT INTO op (`ID_Anime`, `Opening`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) 
             VALUES (?, '1', ?, ?, 'Faltante', ?, 'Faltante', NOW())"
        );
        $stmt_op->execute([$last_id_anime, $ano_insertar, $tempo, $mix]);
    }

    // 10. Insertar en ed si fue marcado
    if (isset($_POST["ED"])) {
        $stmt_ed = $conn->prepare(
            "INSERT INTO ed (`ID_Anime`, `Ending`, `Ano`, `Temporada_Emision`, `Estado`, `Mix`, `Estado_Link`, `Fecha_Ingreso`) 
             VALUES (?, '1', ?, ?, 'Faltante', ?, 'Faltante', NOW())"
        );
        $stmt_ed->execute([$last_id_anime, $ano_insertar, $tempo, $mix2]);
    }

    // Confirmar todos los cambios en la base de datos
    $conn->commit();
    $conn = null;

    mostrarAlerta('success', $mensaje_exito, $link);

} catch (PDOException $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    $conn = null;
    mostrarAlerta('error', 'Error al guardar en la base de datos: ' . $e->getMessage(), $link);
}
?>