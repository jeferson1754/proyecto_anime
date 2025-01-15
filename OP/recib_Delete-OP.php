<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';


$link = $_REQUEST['link'];

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


// Si se está enviando el formulario 'Calificar_Ahora', hacer la inserción
if (isset($_POST['mix'])) {
    // Consulta para insertar un nuevo registro en la tabla `mix`
    $sqlInsert = "INSERT INTO `mix`(`ID`) VALUES ('')";
    $stmtInsert = $conexion->prepare($sqlInsert);

    // Ejecutar la consulta
    if (!$stmtInsert->execute()) {
        // Mostrar mensaje de error si falla la inserción
        $alertTitle = '¡Error Mix!';
        $alertText = 'No se puedo crear el mix. Por favor, inténtelo de nuevo.';
        $alertType = 'error';
        $redireccion = htmlspecialchars($link);

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die;
    } else {
        $alertTitle = '¡Nuevo Mix Creado!';
        $alertText = 'El nuevo mix fue creado con exito';
        $alertType = 'success';
        $redireccion = htmlspecialchars($link);

        alerta($alertTitle, $alertText, $alertType, $redireccion);
        die;
    }
} else {
    // Recibir datos del request
    $idRegistros = $_REQUEST['id'] ?? null;
    $nombre = $_REQUEST['anime'] ?? null;
    $op = $_REQUEST['op'] ?? null;

    try {
        // Consulta para eliminar el registro
        $sql = "DELETE FROM op WHERE `ID` = ?";
        $stmt = $conexion->prepare($sql);

        // Vincular parámetros de forma segura
        $stmt->bind_param('i', $idRegistros);

        // Ejecutar la consulta y verificar si se ejecutó correctamente
        if ($stmt->execute()) {
            // Mostrar mensaje de éxito con SweetAlert

            $alertTitle = '¡Opening Eliminado!';
            $alertText = 'Eliminando OP ' . htmlspecialchars($op) . ' de ' . htmlspecialchars($nombre) . '';
            $alertType = 'success';
            $redireccion = htmlspecialchars($link);

            alerta($alertTitle, $alertText, $alertType, $redireccion);
            die;
        } else {
            // En caso de error, mostrar mensaje de error
            $alertTitle = '¡Error al eliminar!';
            $alertText = 'No se pudo eliminar el Opening. Por favor, inténtelo de nuevo.';
            $alertType = 'error';
            $redireccion = htmlspecialchars($link);

            alerta($alertTitle, $alertText, $alertType, $redireccion);
            die;
        }
    } catch (Exception $e) {
        // Manejo de excepciones en caso de error general
        // En caso de error, mostrar mensaje de error
        $alertTitle = '¡Error!';
        $alertText = 'Hubo un problema con la operación. Por favor, inténtelo más tarde.';
        $alertType = 'error';
        $redireccion = htmlspecialchars($link);

        alerta($alertTitle, $alertText, $alertType, $redireccion);

        // También puedes registrar el error en un archivo de log
        error_log($e->getMessage());
        die;
    }
}

?>