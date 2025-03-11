<?php

require '../bd.php';

// Obtener los valores del formulario
$starValuesJSON = $_POST['starValues'];
$id_anime = $_POST['id'];
$nombre = $_POST['nombre'];

// Decodificar el JSON para obtener un array PHP
$starValues = json_decode($starValuesJSON, true);

echo $nombre . "<br>";
// Inicializar un array para almacenar las sumas de cada conjunto de estrellas
$calificaciones = [];
foreach ($starValues as $index => $values) {
    // Calcular la suma de los valores en el conjunto actual
    $calificaciones[$index + 1] = array_sum($values);
}

// Mostrar las sumas (si es necesario para depuración)
foreach ($calificaciones as $key => $value) {
    echo "Suma de calificacion_estrellas_$key: $value<br>";
}

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar la calificación existente
    $stmt = $conn->prepare("SELECT ID FROM calificaciones WHERE ID_Anime = :id_anime AND Temporadas = :nombre_anime");
    $stmt->bindParam(':id_anime', $id_anime, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_anime', $nombre, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Actualizar las calificaciones si ya existe
        $id_calificacion = $row['ID'];
        $sql = "UPDATE calificaciones 
                SET Calificacion_1 = :calificacion_1, 
                    Calificacion_2 = :calificacion_2, 
                    Calificacion_3 = :calificacion_3, 
                    Calificacion_4 = :calificacion_4, 
                    Calificacion_5 = :calificacion_5
                WHERE ID = :id_calificacion";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':calificacion_1', $calificaciones[1], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_2', $calificaciones[2], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_3', $calificaciones[3], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_4', $calificaciones[4], PDO::PARAM_INT);

        // Verificar si la calificación 5 es nula o 0 y asignar null si es el caso
        $calificacion_5 = (isset($calificaciones[5]) && $calificaciones[5] !== 0) ? $calificaciones[5] : null;
        $stmt->bindValue(':calificacion_5', $calificacion_5, PDO::PARAM_INT);

        $stmt->bindValue(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Insertar nueva calificación si no existe
        $sql = "INSERT INTO calificaciones 
        (ID_Anime, Temporadas, Calificacion_1, Calificacion_2, Calificacion_3, Calificacion_4, Calificacion_5) 
        VALUES (:id_anime, :nombre_anime, :calificacion_1, :calificacion_2, :calificacion_3, :calificacion_4, :calificacion_5)";


        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_anime', $id_anime, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_anime', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':calificacion_1', $calificaciones[1], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_2', $calificaciones[2], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_3', $calificaciones[3], PDO::PARAM_INT);
        $stmt->bindValue(':calificacion_4', $calificaciones[4], PDO::PARAM_INT);

        // Verificar si la calificación 5 es nula o 0 y asignar null si es el caso
        $calificacion_5 = (isset($calificaciones[5]) && $calificaciones[5] !== 0) ? $calificaciones[5] : 0;
        $stmt->bindValue(':calificacion_5', $calificacion_5, PDO::PARAM_INT);

        $stmt->execute();

        // Obtener el ID insertado
        $id_calificacion = $conn->lastInsertId();
    }

    // Filtrar las calificaciones que no sean nulas ni ceros
    $calificaciones_validas = array_filter($calificaciones, function ($valor) {
        return $valor !== null && $valor !== 0;  // Filtra nulos y ceros
    });

    // Calcular la suma de las calificaciones válidas
    $suma_calificaciones = array_sum($calificaciones_validas);

    // Contar cuántas calificaciones válidas existen
    $total_calificaciones_validas = count($calificaciones_validas);

    // Si hay calificaciones válidas, calcular el promedio
    if ($total_calificaciones_validas > 0) {
        $promedio = round($suma_calificaciones / $total_calificaciones_validas, 1);
    } else {
        $promedio = 0;  // Si no hay calificaciones válidas, el promedio es 0
    }

    // Actualizar el promedio en la base de datos
    $sql = "UPDATE calificaciones 
        SET Promedio = :promedio
        WHERE ID = :id_calificacion";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':promedio', $promedio, PDO::PARAM_STR);
    $stmt->bindValue(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php');
    exit();

} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

