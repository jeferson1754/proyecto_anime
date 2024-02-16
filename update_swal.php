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
    $sql = "SELECT * FROM `eliminados_emision` WHERE ID_Emision='$id' LIMIT 1";
    $eliminados_emision = mysqli_query($conexion, $sql);

    echo "Existe en eliminados emision<br>";

    // Inicializar variables
    $datos_emision = array();

    while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
        $datos_emision[] = $mostrar;
    }

    echo "ELIMINADOS_EMISION<BR>";

    foreach ($datos_emision as $dato) {
        echo $dato['ID_Emision'] . "<br>";
        echo $dato['Estado'] . "<br>";
        echo $dato['Nombre'] . "<br>";
        echo $dato['Capitulos'] . "<br>";
        echo $dato['Totales'] . "<br>";
        echo $dato['Dia'] . "<br>";
        echo $dato['Duracion'] . "<br>";

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO emision (`ID_Emision`, `Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
        VALUES ('{$dato['ID_Emision']}', '{$dato['Estado']}', '{$dato['Nombre']}', '{$dato['Capitulos']}', '{$dato['Totales']}', '{$dato['Dia']}', '{$dato['Duracion']}')";
            $conn->exec($sql);
            $last_id1 = $dato['ID_Emision'];
            echo $sql;
            echo 'ultimo anime insertado ' . $last_id1;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `eliminados_emision` where ID_Emision='{$dato['ID_Emision']}'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $dato['Nombre'] . '  en Emision y Actualizando en Anime",
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
