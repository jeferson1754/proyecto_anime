<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #6366f1;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .dropdown-menu {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .dropdown-menu {
                opacity: 1;
                transform: none;
                display: none;
            }

            .dropdown.active .dropdown-menu {
                display: block;
            }
        }

        .main-container{
            margin-top: 80px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav id="menu" class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/Anime/" class="text-indigo-600 font-bold text-xl hover:text-indigo-800 transition duration-300">
                        AnimeList Pro
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16" />
                        </svg>
                    </button>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:flex md:items-center md:space-x-6">
                    <a href="/Anime/" class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Animes</a>
                    <a href="/Anime/Emision/" class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Emisión</a>
                    <a href="/Anime/Pendientes/" class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Pendientes</a>
                    <a href="/Anime/peliculas/" class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Películas</a>

                    <!-- Dropdowns -->
                    <div class="relative dropdown">
                        <button class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Calificaciones
                        </button>
                        <div class="dropdown-menu absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl">
                            <a href="/Anime/Calificaciones/calificaciones.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Ver Calificaciones</a>
                            <a href="/Anime/Calificaciones/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Editar Calificaciones</a>
                        </div>
                    </div>

                    <div class="relative dropdown">
                        <button class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Horarios
                        </button>
                        <div class="dropdown-menu absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl">
                            <a href="/Anime/Horarios/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Ver Horario</a>
                            <a href="/Anime/Horarios/horarios.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Horarios Antiguos</a>
                        </div>
                    </div>

                    <div class="relative dropdown">
                        <button class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            OP y ED
                        </button>
                        <div class="dropdown-menu absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl">
                            <a href="../../Graficos/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Gráficos</a>
                            <a href="/Anime/OP/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Openings</a>
                            <a href="/Anime/ED/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Endings</a>
                        </div>
                    </div>

                    <div class="relative dropdown">
                        <button class="nav-link text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Extras
                        </button>
                        <div class="dropdown-menu absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl">
                            <a href="/Manga/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Mangas</a>
                            <a href="/Series/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Series</a>
                            <a href="/Buscador/anime.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Buscador</a>
                            <a href="../../calculador_anime.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Calculadora</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white pb-4">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/Anime/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Animes</a>
                <a href="/Anime/Emision/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Emisión</a>
                <a href="/Anime/Pendientes/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Pendientes</a>
                <a href="/Anime/peliculas/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Películas</a>

                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">
                        Calificaciones
                    </button>
                    <div class="hidden pl-4">
                        <a href="/Anime/Calificaciones/calificaciones.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Ver Calificaciones</a>
                        <a href="/Anime/Calificaciones/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Editar Calificaciones</a>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">
                        Horarios
                    </button>
                    <div class="hidden pl-4">
                        <a href="/Anime/Horarios/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Ver Horario</a>
                        <a href="/Anime/Horarios/horarios.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Horarios Antiguos</a>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">
                        OP y ED
                    </button>
                    <div class="hidden pl-4">
                        <a href="../../Graficos/index.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Gráficos</a>
                        <a href="/Anime/OP/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Openings</a>
                        <a href="/Anime/ED/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Endings</a>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">
                        Extras
                    </button>
                    <div class="hidden pl-4">
                        <a href="/Manga/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Mangas</a>
                        <a href="/Series/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Series</a>
                        <a href="/Buscador/anime.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Buscador</a>
                        <a href="../../calculador_anime.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Calculadora</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Mobile dropdowns
        document.querySelectorAll('.mobile-dropdown button').forEach(button => {
            button.addEventListener('click', function() {
                const content = this.nextElementSibling;
                content.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>