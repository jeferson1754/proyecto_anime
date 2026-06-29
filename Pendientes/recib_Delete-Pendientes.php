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

    // --- CORRECCIÓN: Definir y EJECUTAR la actualización de la configuración ---
    if ($tipo == "Anime") {
        // Si el que estás borrando es un Anime:
        $sqlConfig = "UPDATE configuracion_pendientes  SET valor = 'Ova y Otros' WHERE clave = 'proximo_primero'";
    } else {
        // Si el que estás borrando es una OVA:
        $sqlConfig = "UPDATE configuracion_pendientes  SET valor = 'Anime' WHERE clave = 'proximo_primero'";
    }
    // Ejecutamos la consulta de configuración para que el cambio impacte en la Base de Datos
    $conn->exec($sqlConfig);
    // ---------------------------------------------------------------------------

    $stmtPeli = $conn->prepare("SELECT COUNT(*) FROM `peliculas` WHERE ID_Pendientes = ?");
    $stmtPeli->execute([$idRegistros]);
    $existePeli = $stmtPeli->fetchColumn() > 0;

    // --- NUEVA VALIDACIÓN: Contar cuántos pendientes QUEDAN de este mismo anime (excluyendo el que estamos borrando) ---
    $stmtRestantes = $conn->prepare("SELECT COUNT(*) FROM `pendientes` WHERE ID_Anime = ? AND ID != ?");
    $stmtRestantes->execute([$id_anime, $idRegistros]);
    $quentanPendientes = $stmtRestantes->fetchColumn() > 0; // Será true si aún quedan otros registros
    // ------------------------------------------------------------------------------------------------------------------

    // 5. Flujo Lógico Principal (Decisión de Estados con la nueva validación)
    $mensajeSweet = "Eliminando " . htmlspecialchars($nombre) . " de Pendientes";

    if (!$existeAnime) {
        if (!$existePeli) {
            // Caso A: No hay anime maestro ni película vinculada -> Solo borrar pendiente
        } else {
            // Caso B: Es una película independiente vinculada
            if (!$quentanPendientes) {
                // Desvinculamos el ID_Pendientes poniéndolo en NULL y la marcamos como Finalizado
                $stmtUpdPeli = $conn->prepare("UPDATE peliculas SET estado = 'Finalizado', ID_Pendientes = NULL WHERE ID_Pendientes = ?");
                $stmtUpdPeli->execute([$idRegistros]);
                $mensajeSweet = "¡Último elemento! Película " . htmlspecialchars($nombre) . " marcada como Finalizada";
            } else {
                // Si quedan más cosas, solo desvinculamos este pendiente específico
                $stmtUpdPeli = $conn->prepare("UPDATE peliculas SET ID_Pendientes = NULL WHERE ID_Pendientes = ?");
                $stmtUpdPeli->execute([$idRegistros]);
                $mensajeSweet = "Eliminado de la lista. Aún te quedan otros elementos de este título por ver.";
            }
        }
    } else {
        // Caso C: Existe el anime maestro
        // SÓLO si no quedan más pendientes de este ID_Anime, pasamos el Anime Maestro a Finalizado
        if (!$quentanPendientes) {
            $stmtUpdAnime = $conn->prepare("UPDATE anime SET Estado = 'Finalizado' WHERE id = ?");
            $stmtUpdAnime->execute([$id_anime]);
            $mensajeSweet = "¡Serie Completada! " . htmlspecialchars($nombre) . " ha sido marcado como Finalizado";
        } else {
            $mensajeSweet = "Eliminado de la lista. Aún tienes otras temporadas/OVAs pendientes de esta serie.";
        }
    }

    // 6. El borrado del pendiente actual ocurre SIEMPRE al final de las evaluaciones
    $stmtDelete = $conn->prepare("DELETE FROM `pendientes` WHERE ID = ?");
    $stmtDelete->execute([$idRegistros]);


    // 7. Lógica del Siguiente Pendiente Automático ("Viendo = SI")
    $ordenTipos = [
        "Anime" => "Ova y Otros",
        "Ova y Otros" => "Pelicula",
        "Pelicula" => "Anime"
    ];

    $siguienteTipo = $ordenTipos[$tipo] ?? "Anime";

    $sqlSiguiente = "SELECT ID FROM pendientes WHERE tipo = ? GROUP BY Pendientes ORDER BY Pendientes ASC LIMIT 1";
    $stmtSig = $conn->prepare($sqlSiguiente);

    $stmtSig->execute([$siguienteTipo]);
    $id_Pendiente = $stmtSig->fetchColumn();

    if (!$id_Pendiente) {
        $siguienteTipoAlterno = $ordenTipos[$siguienteTipo];
        $stmtSig->execute([$siguienteTipoAlterno]);
        $id_Pendiente = $stmtSig->fetchColumn();
    }

    if ($id_Pendiente) {
        $stmtViendo = $conn->prepare("UPDATE pendientes SET Viendo = 'SI' WHERE ID = ?");
        $stmtViendo->execute([$id_Pendiente]);
    }


    // 8. Renderizado del aviso de éxito con SweetAlert2 personalizado según el caso
    echo '<script>
    Swal.fire({
        icon: "success",
        title: "' . $mensajeSweet . '",
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