<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_GET['id']) && isset($_GET['nombre'])) {
    $id_anime = urldecode($_GET['id']);
    $nombre_anime = urldecode($_GET['nombre']);
    //echo "Los datos recibidos son: ID: " . $id_anime . ", Nombre: " . $nombre_anime;
}


require 'bd.php';

// Array con los títulos de los encabezados
$titulos = array("Historia", "Musica", "Animacion", "Desarrollo", "Final");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Multiple Star Ratings in HTML CSS & JavaScript</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <?php
    include 'cabecera.php';
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>
    <?php

    echo "<h2 style='text-align:center'>" . $nombre_anime . "</h2>";

    // Array para almacenar las calificaciones
    $calificaciones = array();



    // Consulta SQL para obtener las calificaciones desde la base de datos
    $sql = "SELECT Calificacion_1, Calificacion_2, Calificacion_3, Calificacion_4, Calificacion_5 FROM `calificaciones` WHERE ID_Anime=$id_anime"; // Ajusta el ID según tu estructura de base de datos
    //echo $sql;
    $result = $conexion->query($sql);

    // Obtener y almacenar las calificaciones en el array
    if ($result->num_rows > 0) {
        // Obtener la primera fila (solo debería haber una fila si estás buscando un ID específico)
        $row = $result->fetch_assoc();

        // Iterar sobre las columnas y agregar las calificaciones al array
        for ($i = 1; $i <= 5; $i++) {
            $column_name = "Calificacion_" . $i;
            $calificaciones[] = $row[$column_name];
        }
    } else {
        for ($i = 1; $i <= 5; $i++) {
            $column_name = "Calificacion_" . $i;
            $calificaciones[] = 0;
        }
    }



    // Mostrar cajas de calificación para cada calificación
    for ($i = 0; $i < count($calificaciones); $i++) {
        echo '
        <div class="rating-box">
            <header>' . $titulos[$i] . '</header>
            <div class="stars rating-stars-' . $i . '"></div>
            <div class="rating-text rating-value-' . $i . '"><span class="product-rating-value">' . $calificaciones[$i] . '</span></div>
        </div>';
    }

    ?>


    <h2 class="text-center">Guardar Datos</h2>
    <form id="starValuesForm" method="post" action="guardar_datos_stars.php">
        <input type="hidden" id="starValuesInput" name="starValues">

        <input type="hidden" name="id" value=" <?php echo $id_anime ?>">
        <div>
            <button class=" btn btn-secondary centrar" style="  margin-bottom:50px;" type="submit">Enviar</button>
        </div>

    </form>





    <script>
        // Función para establecer la calificación según el número
        function setRating(num, starsList, ratingElement, index, starValues) {
            starsList.innerHTML = ''; // Limpiar las estrellas existentes
            starValues[index] = []; // Inicializar el array de valores de estrellas para este índice

            for (let i = 0; i < 5; i++) {
                const star = document.createElement("i");
                star.classList.add("fa-solid", "fa-star");
                if (i < num) {
                    star.classList.add("active");
                    starValues[index][i] = 1; // Marcar la estrella como activa (valor 1)
                } else {
                    starValues[index][i] = 0; // Marcar la estrella como inactiva (valor 0)
                }
                star.addEventListener("click", function() {
                    const newRating = i + 1;
                    setRating(newRating, starsList, ratingElement, index, starValues);
                    actualizarStarValuesDisplay(starValues); // Actualizar el display de los valores de estrellas
                });
                starsList.appendChild(star); // Agregar las nuevas estrellas
            }

            ratingElement.textContent = `${num}`; // Mostrar la calificación
        }

        // Calificaciones obtenidas de PHP
        let calificaciones = [<?php echo implode(',', $calificaciones); ?>];
        let starValues = [];

        // Llamar a la función setRating para cada calificación
        calificaciones.forEach((calificacion, index) => {
            const starsList = document.querySelector(`.rating-stars-${index}`);
            const ratingElement = document.querySelector(`.rating-value-${index}`);
            starValues[index] = [];
            setRating(calificacion, starsList, ratingElement, index, starValues);
        });
        /* 
        // Función para actualizar el contenido del div con el contenido de starValues
        function actualizarStarValuesDisplay(starValues) {
            const starValuesDisplay = document.getElementById("starValuesDisplay");
            starValuesDisplay.textContent = JSON.stringify(starValues, null, 2); // Convertir a JSON con formato
        }
        */
        // Función para actualizar el campo oculto con los valores de starValues
        function actualizarStarValuesInput(starValues) {
            const starValuesInput = document.getElementById("starValuesInput");
            starValuesInput.value = JSON.stringify(starValues);
        }

        // Escuchar el evento de clic en las estrellas para actualizar el campo oculto
        document.addEventListener("click", function(event) {
            const starValuesInput = document.getElementById("starValuesInput");
            if (event.target.classList.contains("fa-star")) {
                actualizarStarValuesInput(starValues);
            }
        });

        // Llamar a la función inicialmente para actualizar el campo oculto con los valores de starValues
        actualizarStarValuesInput(starValues);
    </script>

</body>

</html>