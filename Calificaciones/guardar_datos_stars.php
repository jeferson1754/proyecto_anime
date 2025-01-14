<?php

require '../bd.php';

// Obtener los valores del formulario
$starValuesJSON = $_POST['starValues'];
$id_anime = $_POST['id'];

// Decodificar el JSON para obtener un array PHP
$starValues = json_decode($starValuesJSON, true);

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
    $stmt = $conn->prepare("SELECT ID FROM calificaciones WHERE ID_Anime = :id_anime");
    $stmt->bindParam(':id_anime', $id_anime, PDO::PARAM_INT);
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
        $stmt->bindParam(':calificacion_1', $calificaciones[1], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_2', $calificaciones[2], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_3', $calificaciones[3], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_4', $calificaciones[4], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_5', $calificaciones[5], PDO::PARAM_INT);
        $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Insertar nueva calificación si no existe
        $sql = "INSERT INTO calificaciones (ID_Anime, Calificacion_1, Calificacion_2, Calificacion_3, Calificacion_4, Calificacion_5) 
                VALUES (:id_anime, :calificacion_1, :calificacion_2, :calificacion_3, :calificacion_4, :calificacion_5)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_anime', $id_anime, PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_1', $calificaciones[1], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_2', $calificaciones[2], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_3', $calificaciones[3], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_4', $calificaciones[4], PDO::PARAM_INT);
        $stmt->bindParam(':calificacion_5', $calificaciones[5], PDO::PARAM_INT);
        $stmt->execute();

        // Obtener el ID insertado
        $id_calificacion = $conn->lastInsertId();
    }

    // Actualizar el promedio
    $sql = "UPDATE calificaciones 
            SET Promedio = ROUND((Calificacion_1 + Calificacion_2 + Calificacion_3 + Calificacion_4 + Calificacion_5) / 5, 1)
            WHERE ID = :id_calificacion";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

header('Location: index.php');
exit();
