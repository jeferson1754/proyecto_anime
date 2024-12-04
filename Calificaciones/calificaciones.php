<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Caliificaciones de Animes</title>
    <link rel="stylesheet" href="../css/stars.css?<?php echo time(); ?>" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <style>
        /* Agrega estilos CSS aquí si es necesario */
    </style>
</head>

<body>


    <?php
    require '../bd.php';
    // Consulta SQL para seleccionar los primeros 100 registros de la tabla anime a.ID_Calificacion = c.ID LIMIT 100
    //$sql = "SELECT anime.*, ROUND(calificaciones.Promedio / 5) AS promedio FROM anime INNER JOIN calificaciones ON anime.ID_Calificacion = calificaciones.ID LIMIT 100;";

    $sql = "SELECT anime.Anime, calificaciones.* FROM `calificaciones` INNER JOIN anime ON calificaciones.ID_Anime=anime.id WHERE calificaciones.Promedio > 0 ORDER BY `calificaciones`.`Promedio` DESC;";

    // Ejecutar la consulta SQL
    $resultado = $conexion->query($sql);

    // Verificar si hay resultados
    if ($resultado->num_rows > 0) {
        // Mostrar los resultados en una tabla
        while ($fila = $resultado->fetch_assoc()) {

    ?>

            <div class="rating-box">
                <img class="imagen" src="<?php echo $fila["Link_Imagen"] ?>">
                <header>
                    <?php
                    if (strlen($fila["Anime"]) > 40) {
                        echo substr($fila["Anime"], 0, 40) . "...";
                    } else {
                        echo $fila["Anime"];
                    }
                    ?>
                </header>

                <div class="stars product-stars">
                    <!-- Estrellas del anime -->
                    <?php

                    $calificacion = $fila["Promedio"];

                    // Establecer el número de estrellas activas según la calificación
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $calificacion) {
                            echo '<i class="fa-solid fa-star active"></i>';
                        } else {
                            echo '<i class="fa-solid fa-star"></i>';
                        }
                    }
                    ?>
                </div>
                <!-- Texto de calificación del anime -->
                <div class="rating-text product-rating">Promedio: <span class="product-rating-value"><?php echo $calificacion ?></span></div>
            </div>

    <?php
        }
    } else {
        echo "No se encontraron resultados.";
    }
    ?>

    <script>
        // Función para establecer la calificación según el número
        function setRating(num, stars, ratingText) {
            const starsList = document.querySelectorAll(stars);
            const ratingElement = document.querySelector(ratingText);

            starsList.forEach((star, index) => {
                if (index < num) {
                    star.classList.add("active");
                } else {
                    star.classList.remove("active");
                }
            });

            ratingElement.textContent = `${num}`;
        }
    </script>
</body>

</html>