<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

    <!--fontawesome-->

    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js"></script>

    <!--This is used for search icon. Instead putting icon manually it is loaded from fontawesome-->

</head>
<style>

</style>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark"> <a class="navbar-brand" href="/Anime/" target="menu">Lista de Animes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"> <a class="nav-link" href="/Anime/">Animes</a> </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"> <a class="nav-link" href="/Anime/Emision/">Emision</a> </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"> <a class="nav-link" href="/Anime/Pendientes/">Pendientes</a> </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"> <a class="nav-link" href="/Anime/peliculas/">Peliculas</a> </li>
            </ul>

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Calificaciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/Anime/Calificaciones/calificaciones.php">Ver Calificaciones</a>
                        <a class="dropdown-item" href="/Anime/Calificaciones/">Editar Calificaciones</a>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Horarios
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/Anime/Horarios/">Horario</a>
                        <a class="dropdown-item" href="/Anime/Horarios/horarios.php">Horarios</a>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Op y ED
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/Anime/Graficos/">Graficos</a>
                        <a class="dropdown-item" href="/Anime/OP/">OP</a>
                        <a class="dropdown-item" href="/Anime/ED/">ED</a>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Extras
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/Manga/">Mangas</a>
                        <a class="dropdown-item" href="/Series/">Series</a>
                        <a class="dropdown-item" href="/Buscador/anime.php">Buscador de Anime</a>
                        <a class="dropdown-item" href="../../calculador_anime.php">Calculador de Anime</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <br>
    <br>
    <br>

    <script src="./js/jquery-1.12.4-jquery.min.js"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>