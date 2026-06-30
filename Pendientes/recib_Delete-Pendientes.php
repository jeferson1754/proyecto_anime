<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
// 1. Configuración e Inclusión
include '../bd.php';

// 2. Sanitización básica de datos de entrada
$idRegistros = $_REQUEST['id'] ?? null;
$id_anime    = $_REQUEST['id_anime'] ?? null;
$nombre      = $_REQUEST['nombre'] ?? '';
$link        = $_REQUEST['link'] ?? 'index.php';
$tipo        = $_REQUEST['tipo'] ?? '';

if (!$idRegistros) {
    die("Error: ID de registro no proporcionado.");
}

try {
    // 3. Crear una ÚNICA conexión PDO
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. Verificaciones previas utilizando consultas preparadas
    $stmtAnime = $conn->prepare("SELECT COUNT(*) FROM `anime` WHERE ID = ?");
    $stmtAnime->execute([$id_anime]);
    $existeAnime = $stmtAnime->fetchColumn() > 0;

    // --- CORRECCIÓN LOGICA: Solo actualiza si se especificó el tipo ---
    if (!empty($tipo)) {
        if ($tipo == "Anime") {
            $sqlConfig = "UPDATE configuracion_pendientes SET valor = 'Ova y Otros' WHERE clave = 'proximo_primero'";
        } else {
            $sqlConfig = "UPDATE configuracion_pendientes SET valor = 'Anime' WHERE clave = 'proximo_primero'";
        }
        $conn->exec($sqlConfig);
    }
    // ---------------------------------------------------------------------------

    $stmtPeli = $conn->prepare("SELECT COUNT(*) FROM `peliculas` WHERE ID_Anime = ?");
    $stmtPeli->execute([$id_anime]);
    $existePeli = $stmtPeli->fetchColumn() > 0;

    // --- NUEVA VALIDACIÓN: Contar cuántos pendientes QUEDAN de este mismo anime (excluyendo el que estamos borrando) ---
    $stmtRestantes = $conn->prepare("SELECT COUNT(*) FROM `pendientes` WHERE ID_Anime = ? AND ID != ?");
    $stmtRestantes->execute([$id_anime, $idRegistros]);
    $quedanPendientes = $stmtRestantes->fetchColumn() > 0; // true o false

    // 5. Flujo Lógico Principal (Decisión de Estados)

    // Valor por defecto para el mensaje
    $mensajeSweet = "Eliminando " . htmlspecialchars($nombre) . " de Pendientes";

    if (!$existeAnime) {
        if (!$existePeli) {
            // Caso A: No hay anime maestro ni película vinculada -> Solo borrar pendiente
            $mensajeSweet = "Eliminado correctamente de la lista temporal.";
        }
    } else {
        // Caso C: Existe el anime maestro

        // CAMBIO AQUÍ: Evaluamos lógicamente si quedan pendientes O si existe película
        $tieneElementosActivos = ($quedanPendientes || $existePeli);

        if (!$tieneElementosActivos) {
            // SÓLO si NO quedan pendientes Y NO existen películas asociadas
            $stmtUpdAnime = $conn->prepare("UPDATE anime SET Estado = 'Finalizado' WHERE id = ?");
            $stmtUpdAnime->execute([$id_anime]);
            $mensajeSweet = "¡Anime Completado! " . htmlspecialchars($nombre) . " ha sido marcado como Finalizado";
        } else {
            // Si todavía queda algo (otra temporada en pendientes o una película en su tabla)
            $mensajeSweet = "Eliminado de la lista. Aún tienes otras temporadas, OVAs o películas pendientes de esta serie.";
        }
    }

    // 6. El borrado del pendiente actual ocurre SIEMPRE al final de las evaluaciones
    $stmtDelete = $conn->prepare("DELETE FROM `pendientes` WHERE ID = ?");
    $stmtDelete->execute([$idRegistros]);

    // 7. Renderizado del aviso de éxito con SweetAlert2 personalizado según el caso
    echo '<script>
    Swal.fire({
        icon: "success",
        title: ' . json_encode($mensajeSweet) . ',
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';
} catch (PDOException $e) {
    echo "¡Error en la base de datos!: " . $e->getMessage();
} finally {
    $conn = null;
}
?>