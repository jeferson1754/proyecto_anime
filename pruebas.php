<?php


function alerta_swal($alertTitle, $alertText, $alertType, $redireccion)
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


// Consulta para obtener el último Mix OP
$sql1 = "
    SELECT mix.ID, COUNT(op.Mix) AS MixCount 
    FROM mix
    LEFT JOIN op ON mix.ID = op.Mix 
    WHERE mix.ID = (SELECT MAX(ID) FROM mix)
    GROUP BY mix.ID 
    ORDER BY mix.ID DESC;
";

$mixes = $conexion->query($sql1);

if ($mixes && $valores = $mixes->fetch_assoc()) {
    $mix = $valores['ID'];
    $mixCount = $valores['MixCount'];
}

// Consulta para obtener el último Mix ED
$sql2 = "
    SELECT mix_ed.ID, COUNT(ed.Mix) AS MixCount 
    FROM mix_ed 
    LEFT JOIN ed ON mix_ed.ID = ed.Mix 
    WHERE mix_ed.ID = (SELECT MAX(ID) FROM mix_ed) 
    GROUP BY mix_ed.ID 
    ORDER BY mix_ed.ID DESC;
";

$mixes_ed = $conexion->query($sql2);

if ($mixes_ed && $valores2 = $mixes_ed->fetch_assoc()) {
    $mix2 = $valores2['ID'];
    $mixCount2 = $valores2['MixCount'];
}

// Función para insertar registros
function insertarRegistro($tabla, $columna, $datos, $conexion)
{
    try {
        // Crear la consulta SQL
        $sql = "INSERT INTO $tabla 
                (`Nombre`, `ID_Anime`, `$columna`, `Ano`, `Temporada`, `Estado`, `Mix`, `Fecha_Ingreso`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        // Depurar la consulta antes de ejecutarla
        echo "Consulta SQL: " . $sql . "<br>";

        // Preparar la consulta
        $stmt = $conexion->prepare($sql);

        // Verificar si la preparación fue exitosa
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }

        // Vincular los parámetros
        $stmt->bind_param(
            "sissssi", // tipos de datos
            $datos['nombre'],
            $datos['idAnime'],
            $datos['op_ed'],
            $datos['ano'],
            $datos['temporada'],
            $datos['estado'],
            $datos['mix']
        );

        // Ejecutar la consulta
        $stmt->execute();
        echo "Registro insertado en $tabla.<br>";
    } catch (Exception $e) {
        // Capturar cualquier error
        echo "Error al insertar en $tabla: " . $e->getMessage() . "<br>";
    }
}


// Validar Mix OP
if ($mixCount < 50) {
    echo "Mix OP ($mixCount) está dentro del límite.<br>";

    if ($op > $op1) {
        echo "OP es mayor que el resultado esperado.<br>";
        insertarRegistro("op", "opening", [
            'nombre' => $nombre,
            'idAnime' => $IdAnime,
            'op_ed' => $op,
            'ano' => $fecha,
            'temporada' => $temps,
            'estado' => 'Faltante',
            'mix' => $mix
        ], $conexion);
    } else {
        echo "OP es igual al resultado.<br>";
    }
} else {
    // Alerta cuando se supera el límite
    $alertTitle = "¡Atención!";
    $alertText = "El límite de Mix OP ha sido superado. Por favor, revisa los registros.";
    $alertType = 'warning';
    $redireccion = "window.location='$link'";

    alerta_swal($alertTitle, $alertText, $alertType, $redireccion);

    exit();
}

// Validar Mix ED
if ($mixCount2 < 30) {
    echo "Mix ED ($mixCount2) está dentro del límite.<br>";

    if ($ed > $ed1) {
        echo "ED es mayor que el resultado esperado.<br>";
        insertarRegistro("ed", "ending", [
            'nombre' => $nombre,
            'idAnime' => $IdAnime,
            'op_ed' => $ed,
            'ano' => $fecha,
            'temporada' => $temps,
            'estado' => 'Faltante',
            'mix' => $mix2
        ], $conexion);
    } else {
        echo "ED es igual al resultado.<br>";
    }
} else {
    // Alerta cuando se supera el límite
    $alertTitle = "¡Atención!";
    $alertText = "El límite de Mix ED ha sido superado. Por favor, revisa los registros.";
    $alertType = 'warning';
    $redireccion = "window.location='$link'";

    alerta_swal($alertTitle, $alertText, $alertType, $redireccion);

    exit();
}


echo "Proceso finalizado.<br>";
