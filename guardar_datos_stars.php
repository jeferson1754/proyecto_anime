<?php

require 'bd.php';

// Obtener los valores de starValues del formulario
$starValuesJSON = $_POST['starValues'];
$id_anime       = $_POST['id'];

// Decodificar el JSON para obtener un array PHP
$starValues = json_decode($starValuesJSON, true);

// Inicializar un array para almacenar las sumas de cada conjunto de estrellas
$sums = [];

// Recorrer el array $starValues y calcular la suma de cada conjunto de estrellas
foreach ($starValues as $index => $values) {
    // Calcular la suma de los valores en el conjunto actual
    ${"calificacion_" . ($index + 1)} = array_sum($values);
}

// Ahora tienes variables PHP llamadas $calificacion_1, $calificacion_2, etc.
// Puedes usar estas variables como desees en tu script PHP
echo "Suma de calificacion_estrellas_1: $calificacion_1<br>";
echo "Suma de calificacion_estrellas_2: $calificacion_2<br>";
echo "Suma de calificacion_estrellas_3: $calificacion_3<br>";
echo "Suma de calificacion_estrellas_4: $calificacion_4<br>";
echo "Suma de calificacion_estrellas_5: $calificacion_5<br>";


// Consulta SQL para obtener las calificaciones desde la base de datos
$sql = "SELECT * FROM `calificaciones` WHERE ID_Anime=$id_anime"; // Ajusta el ID según tu estructura de base de datos
echo $sql;
$result = $conexion->query($sql);

// Obtener y almacenar las calificaciones en el array
if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    $id_calificacion = $row['ID'];
    echo $id_calificacion . "<br>";
    // Obtener la primera fila (solo debería haber una fila si estás buscando un ID específico)
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE calificaciones SET Calificacion_1='" . $calificacion_1 . "', Calificacion_2='" . $calificacion_2 . "', Calificacion_3='" . $calificacion_3 . "', Calificacion_4='" . $calificacion_4 . "', Calificacion_5='" . $calificacion_5 . "' where ID='" . $id_calificacion . "';";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
} else {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO calificaciones (`ID_Anime`, `Calificacion_1`, `Calificacion_2`, `Calificacion_3`, `Calificacion_4`, `Calificacion_5`) VALUES (:id_anime, :calificacion_1, :calificacion_2, :calificacion_3, :calificacion_4, :calificacion_5)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_anime', $id_anime);
        $stmt->bindParam(':calificacion_1', $calificacion_1);
        $stmt->bindParam(':calificacion_2', $calificacion_2);
        $stmt->bindParam(':calificacion_3', $calificacion_3);
        $stmt->bindParam(':calificacion_4', $calificacion_4);
        $stmt->bindParam(':calificacion_5', $calificacion_5);
        $stmt->execute();

        //echo $sql . "<br>";

        // Obtener el ID insertado
        $id_calificacion = $conn->lastInsertId();
        echo "El ID insertado es: " . $id_calificacion . "<br>";

        $conn = null;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $conn = null;
    }
}

//SACA EL PROMEDIO
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE calificaciones 
            SET Promedio = (
                SELECT COALESCE(ROUND(SUM(Calificacion_1 + Calificacion_2 + Calificacion_3 + Calificacion_4 + Calificacion_5) / 5, 1), 0)  
            FROM calificaciones WHERE ID='" . $id_calificacion . "'
            )
            WHERE ID='" . $id_calificacion . "';";
    $conn->exec($sql);
    echo $sql . "<br>";
    $conn = null;
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
    $conn = null;
}

header('Location: index.php');
exit();
