<?php

require '../bd.php';
if (isset($_GET['id'])) {
    // Obtener el valor de id_deudor
    $id = $_GET['id'];
    // Aquí puedes usar $id_deudor como necesites en tu código
} else {
    echo "No se ha proporcionado el ID del OP.";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <title>Copia al Portapapeles</title>
</head>

<style>
    .div1 {
        text-align: center;
    }

    .buttons-container {
        display: flex;
        justify-content: center;
        /* Centrar horizontalmente */
    }

    .buttons-container button {
        width: auto;
        height: auto;
        margin: 0 5px;
        margin-bottom: 20px;
        /* Espacio entre los botones */
    }

    i {
        font-size: 40px;
    }
</style>

<body>
    <!-- Contenido HTML -->
    <?php
    // Consulta SQL para obtener los últimos 5 registros desde la base de datos
    $consulta = "SELECT * FROM `op` where ID='$id' ORDER BY `ID` DESC";
    $resultados = $conexion->query($consulta);

    if ($resultados->num_rows > 0) {
        // Iterar sobre los resultados
        while ($row = $resultados->fetch_assoc()) {
            // Obtener el título y los dos textos de la base de datos
            $cancion = $row["Cancion"] ?? ""; // Asegúrate de que el nombre de la columna sea "Cancion"
            $texto1 = $row["Nombre"] ?? ""; // Asegúrate de que el nombre de la columna sea "Nombre"
            $texto2 = $row["Opening"] ?? ""; // Asegúrate de que el nombre de la columna sea "Opening"

            // Imprimir el título y los botones para copiar los textos al portapapeles
            echo "<div class='buttons-container'>";
            echo '<button title="Copiar Titulo" onclick="copyToClipboard(\'' . $cancion . '\')"><i class="fa-solid fa-music"></i></button>';

            $sql1 = "SELECT autor.Autor, op.ID, ((SELECT COUNT(*) FROM op WHERE op.ID_Autor = autor.ID) + (SELECT COUNT(*) FROM ed WHERE ed.ID_Autor = autor.ID)) AS Repeticiones FROM autor JOIN op ON autor.ID = op.ID_Autor WHERE op.ID = '$id' AND autor.Autor != '' HAVING Repeticiones > 3;";
            $result1 = $conexion->query($sql1);

            if ($result1->num_rows > 0) {
                while ($fila = $result1->fetch_assoc()) {
                    // Acceder a los valores de las columnas
                    $autor = $fila["Autor"];
                    $repeticiones = $fila["Repeticiones"];

                    echo '<button title="Copiar Artista" onclick="copyToClipboard(\'' . $autor . '\')"><i class="fa-solid fa-user"></i></button>';
                }
            } else {
                echo '<button title="Copiar Artista" onclick="copyToClipboard(\'' . $texto1 . ' OP ' . $texto2 . '\')"><i class="fa-solid fa-user"></i></button>';
            }

            echo '<button title="Copiar Album" onclick="copyToClipboard(\'' . $texto1 . '\')"><i class="fa-solid fa-compact-disc"></i></button>';
            echo "</div>"; // Cierre del div 'buttons-container'
        }
    } else {
        echo "No se encontraron resultados en la base de datos.";
    }

    ?>

    <!-- Definir la función para copiar texto al portapapeles en JavaScript -->
    <script>
        function copyToClipboard(text) {
            var textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            alert("Texto copiado al portapapeles: " + text);
        }
    </script>



</body>

</html>