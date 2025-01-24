<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calificaciones de Animes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

</head>
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a34a;
        --text-color: #2c3e50;
        --background-color: #ecf0f1;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        padding-bottom: 2rem;
    }

    .page-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
        font-size: 1.75rem;
    }

    .page-header .lead {
        font-size: 1rem;
        padding: 0 1rem;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }

        .page-header .lead {
            font-size: 0.9rem;
        }
    }

    .controls-section,
    .rating-box {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .rating-box {
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .rating-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .anime-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        padding: 0.5rem;
    }

    @media (min-width: 576px) {
        .anime-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
    }

    .rating-box .imagen,
    .no-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    @media (min-width: 576px) {

        .rating-box .imagen,
        .no-image {
            height: 300px;
        }
    }

    .rating-box header {
        padding: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        min-height: 3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (min-width: 576px) {
        .rating-box header {
            padding: 1rem;
            font-size: 1.1rem;
            min-height: 3.6rem;
        }
    }

    .stars {
        padding: 0.5rem;
        display: flex;
        gap: 0.2rem;
        justify-content: center;
    }

    .fa-star {
        color: #ddd;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .fa-star.active {
        color: #ffd700;
    }

    @media (min-width: 576px) {
        .stars {
            gap: 0.3rem;
        }

        .fa-star {
            font-size: 1.2rem;
        }
    }

    .rating-text {
        padding: 0.5rem;
        text-align: center;
        font-weight: 500;
        color: var(--primary-color);
        border-top: 1px solid #eee;
        margin-top: auto;
        font-size: 0.9rem;
    }

    @media (min-width: 576px) {
        .rating-text {
            padding: 0.5rem 1rem 1rem;
            font-size: 1rem;
        }
    }

    .product-rating-value {
        color: var(--accent-color);
        font-weight: 600;
    }

    .search-box {
        max-width: 100%;
        margin: 0 auto 1rem;
        padding: 0 1rem;
    }

    .search-box input {
        border: 2px solid var(--primary-color);
        border-radius: 25px;
        padding: 0.6rem 1rem;
        font-size: 1rem;
        width: 100%;
        transition: box-shadow 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.2);
    }

    @media (min-width: 576px) {
        .search-box {
            max-width: 500px;
        }

        .search-box input {
            padding: 0.8rem 1.5rem;
            font-size: 1.1rem;
        }
    }

    .filters {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
        padding: 0 1rem;
    }

    .filter-select {
        padding: 0.5rem;
        border: 2px solid var(--primary-color);
        border-radius: 5px;
        background: white;
        color: var(--primary-color);
        font-weight: 500;
        cursor: pointer;
        font-size: 0.9rem;
    }

    @media (min-width: 576px) {
        .filter-select {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
    }

    .container {
        padding: 0.5rem;
    }

    @media (min-width: 576px) {
        .container {
            padding: 1rem;
        }
    }

    .no-image {
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 2rem;
    }

    @media (min-width: 576px) {
        .no-image {
            font-size: 3rem;
        }
    }

    .seasons-container {
        padding: 10px;
        background-color: #f1f1f1;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .season-item {
        padding: 5px 0;
        font-size: 0.9rem;
        color: #333;
    }

    .season-item:not(:last-child) {
        border-bottom: 1px solid #ddd;
    }

</style>

<body>
    <!-- El resto del HTML permanece igual -->
    <!-- Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="text-center mb-3">Calificaciones de Animes</h1>
            <p class="text-center mb-0 lead">Descubre y explora los mejores animes con sus calificaciones</p>
        </div>
    </header>

    <!-- Controls Section -->
    <div class="container">
        <!-- Anime Grid -->
        <div class="anime-grid">
            <?php
            require '../bd.php';

            $sql = "SELECT 
            anime.Nombre as Anime, 
            AVG(calificaciones.Promedio) AS PromedioGeneral, 
            calificaciones.* 
        FROM calificaciones 
        INNER JOIN anime 
        ON calificaciones.ID_Anime = anime.id 
        WHERE calificaciones.Promedio > 0 
       GROUP BY calificaciones.ID_Anime
        ORDER BY PromedioGeneral DESC;";

            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
            ?>
                    <div class="rating-box">
                        <?php
                        $id_anime = $fila['ID_Anime'];

                        // Consulta SQL para obtener las imágenes relacionadas con el ID_Anime
                        $imageQuery = "SELECT DISTINCT Link_Imagen 
                                       FROM calificaciones 
                                       WHERE ID_Anime = $id_anime";

                        $result = $conexion->query($imageQuery);

                        // Verificar si hay resultados
                        if ($result->num_rows > 0) {
                            $images = [];
                            // Recorrer los resultados y almacenar las imágenes
                            while ($row = $result->fetch_assoc()) {
                                if (!empty($row['Link_Imagen'])) {
                                    $images[] = htmlspecialchars($row['Link_Imagen'], ENT_QUOTES, 'UTF-8'); // Sanitizar el link y agregar al array
                                }
                            }

                            // Solo mostrar el carrusel si hay más de una imagen
                            if (count($images) > 1) {
                        ?>
                                <!-- Componente Carrusel de Bootstrap -->
                                <div id="animeCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php
                                        $isActive = true; // Variable para marcar el primer elemento como activo

                                        // Recorrer las imágenes y agregar cada una al carrusel
                                        foreach ($images as $imageLink) {
                                        ?>
                                            <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
                                                <img src="<?php echo $imageLink; ?>" alt="Imagen de <?php echo $id_anime; ?>" class="imagen d-block w-100" style="height: 400px; object-fit: cover;">
                                            </div>
                                        <?php
                                            $isActive = false; // Desactivar "active" para los siguientes elementos
                                        }
                                        ?>
                                    </div>

                                    <!-- Controles del Carrusel -->
                                    <button class="carousel-control-prev" type="button" data-bs-target="#animeCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#animeCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                        <?php
                            } else {
                                // Si hay solo una imagen, mostrar la imagen sin carrusel
                                if (count($images) == 1) {
                                    echo "<img class='imagen' src='" . $images[0] . "' alt='Imagen de {$id_anime}'>";
                                } else {
                                    echo "     
                                     <div class='no-image'>
                                        <i class='fas fa-film'></i>
                                        ID:{$fila['ID_Anime']}
                                    </div>";
                                }
                            }
                        } else {
                            echo "     
                            <div class='no-image'>
                               <i class='fas fa-film'></i>
                               ID:{$fila['ID_Anime']}
                           </div>";
                        }
                        ?>





                        <header>
                            <?php
                            if (strlen($fila["Anime"]) > 35) {
                                echo substr($fila["Anime"], 0, length: 35) . "...";
                            } else {
                                echo $fila["Anime"];
                            }
                            ?>
                        </header>

                        <div class="stars product-stars">
                            <?php
                            $calificacion = $fila["PromedioGeneral"];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $calificacion) {
                                    echo '<i class="fa-solid fa-star active"></i>';
                                } else {
                                    echo '<i class="fa-solid fa-star"></i>';
                                }
                            }
                            ?>
                        </div>

                        <div class="rating-text product-rating">
                            Promedio General: <span class="product-rating-value"><?php echo number_format($calificacion, 2) ?></span>
                        </div>





                        <?php
                        /// Obtener los promedios por temporada de este anime
                        $sql_temporada = "SELECT Temporadas, AVG(Promedio) AS promedio_temporada FROM calificaciones WHERE ID_Anime = " . $fila['ID_Anime'] . " GROUP BY Temporadas ORDER BY `calificaciones`.`ID` ASC";
                        $resultado_temporada = $conexion->query(query: $sql_temporada);

                        if ($resultado_temporada->num_rows > 1) {
                        ?>

                            <!-- Botón para mostrar promedios por temporada -->
                            <button class="btn btn-info mt-2" onclick="toggleSeasons(<?php echo $fila['ID_Anime']; ?>)">
                                Ver Temporadas
                                <i class="fas fa-chevron-down" id="arrow-<?php echo $fila['ID_Anime']; ?>"></i> <!-- Flecha hacia abajo al principio -->
                            </button>

                            <!-- Contenedor de temporadas -->
                            <div id="seasons-<?php echo $fila['ID_Anime']; ?>" class="seasons-container" style="display: none; margin-top: 10px;">

                                <?php
                                $numero_temporada = 1;  //
                                while ($temporada = $resultado_temporada->fetch_assoc()) {
                                    // Verifica si el nombre de la temporada no es nulo ni vacío
                                    echo "<div class='season-item'>";
                                    echo "Temporada " . $numero_temporada . ": ";
                                    echo "<span class='product-rating-value'>  " . number_format($temporada['promedio_temporada'], 1) . "</span>";
                                    echo "</div>";
                                    $numero_temporada++;  // Incrementa el número de la temporada
                                }
                                ?>
                            </div>
                        <?php
                        }

                        ?>

                    </div>


            <?php
                }
            } else {
                echo '<div class="col-12 text-center">No se encontraron resultados.</div>';
            }
            ?>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scripts permanecen igual
        document.getElementById('searchAnime').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const animeCards = document.querySelectorAll('.rating-box');

            animeCards.forEach(card => {
                const title = card.querySelector('header').textContent.toLowerCase();
                if (title.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        document.querySelector('.filter-select').addEventListener('change', function(e) {
            const grid = document.querySelector('.anime-grid');
            const cards = Array.from(grid.getElementsByClassName('rating-box'));

            cards.sort((a, b) => {
                if (e.target.value === 'rating') {
                    const ratingA = parseFloat(a.querySelector('.product-rating-value').textContent);
                    const ratingB = parseFloat(b.querySelector('.product-rating-value').textContent);
                    return ratingB - ratingA;
                } else {
                    const titleA = a.querySelector('header').textContent;
                    const titleB = b.querySelector('header').textContent;
                    return titleA.localeCompare(titleB);
                }
            });

            cards.forEach(card => grid.appendChild(card));
        });

        function toggleSeasons(animeId) {
            var seasonContent = document.getElementById('seasons-' + animeId);
            var arrow = document.getElementById('arrow-' + animeId);

            // Si las temporadas están visibles, las ocultamos
            if (seasonContent.style.display === "block") {
                seasonContent.style.display = "none";
                arrow.classList.remove('fa-chevron-up'); // Quitar la flecha hacia arriba
                arrow.classList.add('fa-chevron-down'); // Mostrar flecha hacia abajo
            } else {
                seasonContent.style.display = "block";
                arrow.classList.remove('fa-chevron-down'); // Quitar la flecha hacia abajo
                arrow.classList.add('fa-chevron-up'); // Mostrar flecha hacia arriba
            }
        }
    </script>
</body>

</html>