<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_GET['id']) && isset($_GET['nombre'])) {
    $id_anime = urldecode($_GET['id']);
    $nombre_anime = urldecode($_GET['nombre']);
    $temporada = urldecode($_GET['temporada']) ?? null;
}

require '../bd.php';

// Array con los títulos de los encabezados
$titulos = array("Historia", "Música", "Animación", "Desarrollo", "Final");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones -<?php echo htmlspecialchars($nombre_anime . " " . $temporada); ?></title>
    <link rel="stylesheet" href="../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .anime-image {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .rating-box {
            width: 100%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 10px;
        }

        .rating-box header {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #444;
        }

        .stars {
            display: flex;
            gap: 5px;
        }

        .fa-star {
            font-size: 1.5em;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s;
        }

        .fa-star.active {
            color: #ffcc00;
        }

        .rating-text {
            margin-top: 5px;
            font-size: 1em;
            color: #333;
        }

        .buttons-container {
            position: absolute;
            right: 20px;
            top: 20px;
            display: flex;
            gap: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .clear-button {
            background-color: #dc3545;
        }

        .clear-button:hover {
            background-color: #a71d2a;
        }

        @media (max-width: 576px) {
            .rating-box .stars {
                gap: 12px !important;
            }
        }
    </style>
</head>

<body>
    <h2>Califica: <?php echo htmlspecialchars($nombre_anime . " " . $temporada); ?></h2>

    <div class="container">
        <?php
        // Array para almacenar las calificaciones
        $calificaciones = array();

        // Consulta SQL para obtener las calificaciones desde la base de datos
        $sql = "SELECT Calificacion_1, Calificacion_2, Calificacion_3, Calificacion_4, Calificacion_5, Link_Imagen 
                FROM `calificaciones` WHERE ID_Anime=$id_anime AND Temporadas='$temporada'";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<img class="anime-image" src="' . htmlspecialchars($row["Link_Imagen"]) . '" alt="Imagen Anime">';
            for ($i = 1; $i <= 5; $i++) {
                $column_name = "Calificacion_" . $i;
                $calificaciones[] = $row[$column_name];
            }
        } else {
            $calificaciones = array_fill(0, 5, 0);
        }

        // Mostrar las cajas de calificación
        foreach ($titulos as $index => $titulo) {
            echo '
            <div class="rating-box">
                <header>' . htmlspecialchars($titulo) . '</header>
                <div class="stars rating-stars-' . $index . '"></div>
                <div class="rating-text rating-value-' . $index . '">
                    <span class="product-rating-value">' . htmlspecialchars($calificaciones[$index]) . '</span>
                </div>
            </div>';
        }
        ?>

        <form id="starValuesForm" method="post" action="guardar_datos_stars.php">
            <br>
            <br>
            <input type="hidden" id="starValuesInput" name="starValues">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_anime); ?>">
            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($temporada); ?>">
            <button type="button" class="clear-button" id="clearRatings">Borrar selección</button>
            <button type="submit">Guardar Calificacion</button>

        </form>

    </div>

    <script>
        function setRating(num, starsList, ratingElement, index, starValues) {
            starsList.innerHTML = '';
            starValues[index] = [];

            for (let i = 0; i < 5; i++) {
                const star = document.createElement("i");
                star.classList.add("fa-solid", "fa-star");
                if (i < num) {
                    star.classList.add("active");
                    starValues[index][i] = 1;
                } else {
                    starValues[index][i] = 0;
                }
                star.addEventListener("click", function() {
                    setRating(i + 1, starsList, ratingElement, index, starValues);
                    actualizarStarValuesInput(starValues);
                });
                starsList.appendChild(star);
            }

            ratingElement.textContent = `${num}`;
        }

        let calificaciones = [<?php echo implode(',', $calificaciones); ?>];
        let starValues = [];

        calificaciones.forEach((calificacion, index) => {
            const starsList = document.querySelector(`.rating-stars-${index}`);
            const ratingElement = document.querySelector(`.rating-value-${index}`);
            setRating(calificacion, starsList, ratingElement, index, starValues);
        });

        function actualizarStarValuesInput(starValues) {
            document.getElementById("starValuesInput").value = JSON.stringify(starValues);
        }

        actualizarStarValuesInput(starValues);

        document.getElementById("clearRatings").addEventListener("click", function() {
            calificaciones.forEach((_, index) => {
                const starsList = document.querySelector(`.rating-stars-${index}`);
                const ratingElement = document.querySelector(`.rating-value-${index}`);
                setRating(0, starsList, ratingElement, index, starValues);
            });
            actualizarStarValuesInput(starValues);
        });
    </script>
</body>

</html>