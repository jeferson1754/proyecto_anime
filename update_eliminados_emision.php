<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';
// Verificar si se ha recibido el parámetro id_deudor en la URL
if (isset($_GET['variable'])) {
    // Obtener el valor de id_deudor
    $id = $_GET['variable'];
    // Aquí puedes usar $id_deudor como necesites en tu código
    $sql = "SELECT * FROM `eliminados_emision` WHERE ID='$id' LIMIT 1";
    $eliminados_emision = mysqli_query($conexion, $sql);

    echo "Existe en eliminados emision<br>";

    // Inicializar variables
    $datos_emision = array();

    while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
        $datos_emision[] = $mostrar;
    }

    echo "ELIMINADOS_EMISION<BR>";

    foreach ($datos_emision as $dato) {
        echo $dato['ID_Anime'] . "<br>";
        echo $dato['Temporada'] . "<br>";
        echo $dato['Capitulos'] . "<br>";
        echo $dato['Totales'] . "<br>";
        echo $dato['Dia'] . "<br>";
        echo $dato['Duracion'] . "<br>";

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO emision (`ID_Anime`, `Temporada`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
        VALUES ('{$dato['ID_Anime']}', '{$dato['Temporada']}', '{$dato['Capitulos']}', '{$dato['Totales']}', '{$dato['Dia']}', '{$dato['Duracion']}')";
            $conn->exec($sql);
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `eliminados_emision` where ID_Anime='{$dato['ID_Anime']}'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }

        $sql2 = "SELECT * FROM `anime` WHERE id='{$dato['ID_Anime']}' LIMIT 1";
        $anime = mysqli_query($conexion, $sql2);

        while ($mostrar = mysqli_fetch_array($anime)) {
            $nombre_anime = $mostrar['Nombre'];
        }

        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre_anime . '  en Emision",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "./";
        });
        </script>';
    }
} else {
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "No se ha proporcionado el ID de Eliminados Emision",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "./";
    });
    </script>';
}
