<?php
// Incluir el archivo de conexión a la base de datos
require 'bd.php';

// Configurar la respuesta como JSON para que sea fácil de manejar en el frontend
header('Content-Type: application/json');

// Solo procesar peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
    exit;
}

// Inicializar un array para los errores
$errores = [];

// Validar el ID del anime principal
$animePrincipalId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($animePrincipalId === false || $animePrincipalId <= 0) {
    $errores[] = "El ID del anime principal no es válido.";
}

// Obtener y validar el array de relaciones
$relacionesEnviadas = $_POST['relaciones'][$animePrincipalId] ?? [];
$tiposRelacionValidos = ['Spin off', 'Precuela', 'Secuela'];
$relaciones_limpias = [];
foreach ($relacionesEnviadas as $relacionString) {
    $partes = explode('|', $relacionString);

    // 1. Validar que la cadena tenga 2 partes después de explotar
    if (count($partes) !== 2) {
        $errores[] = "Formato de relación incorrecto.";
        continue;
    }

    // 2. Validar y sanitizar el ID de destino
    $idDestino = filter_var($partes[0], FILTER_VALIDATE_INT);
    if ($idDestino === false || $idDestino <= 0) {
        $errores[] = "El ID de destino no es un número válido.";
        continue;
    }

    // 3. Sanitizar y validar el tipo de relación
    // **CAMBIO AQUÍ: Reemplazar filter_var con strip_tags() o htmlspecialchars()**
    $tipoRelacion = strip_tags($partes[1]);

    // Y luego, validamos si el tipo de relación está en nuestra lista de tipos válidos
    if (empty($tipoRelacion) || !in_array($tipoRelacion, $tiposRelacionValidos)) {
        $errores[] = "El tipo de relación no es válido.";
        continue;
    }

    // Si llegamos aquí, los datos son válidos y limpios
    $relaciones_limpias[] = [
        'id_destino' => $idDestino,
        'tipo_relacion' => $tipoRelacion
    ];
}

// Si hay errores, enviar una respuesta con los errores
if (!empty($errores)) {
    echo json_encode(['success' => false, 'message' => 'Errores de validación', 'errors' => $errores]);
    exit;
}

try {
    $connect->beginTransaction();

    // 1. Eliminar todas las relaciones existentes para este anime
    // 1. Eliminar todas las relaciones en las que el anime actual es el ORIGEN
    $stmt_delete_origen = $connect->prepare("
        DELETE FROM anime_relaciones
        WHERE id_anime_origen = :id_principal
    ");
    $stmt_delete_origen->bindParam(':id_principal', $animePrincipalId, PDO::PARAM_INT);
    $stmt_delete_origen->execute();

    // 2. Insertar las nuevas relaciones
    if (!empty($relaciones_limpias)) {
        $stmt_insert = $connect->prepare("
            INSERT INTO anime_relaciones (id_anime_origen, id_anime_destino, tipo_relacion)
            VALUES (:id_origen, :id_destino, :tipo_relacion)
        ");

        foreach ($relaciones_limpias as $relacion) {
            $stmt_insert->bindParam(':id_origen', $animePrincipalId, PDO::PARAM_INT);
            $stmt_insert->bindParam(':id_destino', $relacion['id_destino'], PDO::PARAM_INT);
            $stmt_insert->bindParam(':tipo_relacion', $relacion['tipo_relacion']);
            $stmt_insert->execute();
        }
    }

    $connect->commit();
    echo json_encode(['success' => true, 'message' => 'Relaciones actualizadas con éxito.']);
} catch (PDOException $e) {
    $connect->rollBack();
    error_log("Error al actualizar relaciones: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al actualizar las relaciones en la base de datos.']);
}
