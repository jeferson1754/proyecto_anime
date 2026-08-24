<?php
include '../../bd.php'; // Ajusta la ruta a tu conexión de BD

$nombre_anime = $_GET['nombre_anime'] ?? null;
$episodio     = $_GET['episodio'] ?? null;

if ($nombre_anime && $episodio) {
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Actualizamos los capítulos vistos/descargados en la tabla emision
        $sql = "UPDATE emision 
                SET No_Descargados = :episodio
                WHERE ID_Anime = (
                    SELECT anime.id 
                    FROM anime 
                    INNER JOIN emision AS e ON anime.id = e.ID_Anime 
                    WHERE CONCAT(anime.Nombre, ' ', e.Temporada) = :nombre 
                    LIMIT 1
                )";
                            
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':episodio' => $episodio,
            ':nombre'   => $nombre_anime
        ]);

        echo "SUCCESS: Anime '$nombre_anime' actualizado al episodio $episodio.";
    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    } finally {
        $conn = null;
    }
} else {
    echo "ERROR: Faltan parámetros (nombre_anime o episodio).";
}
?>