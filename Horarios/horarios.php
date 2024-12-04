<?php
require '../bd.php';

setlocale(LC_ALL, "es_ES");

$año_actual = date("Y");

/* ESTO DA EL DIA ACTUAL EN ESPAÑOL PARA EL MODAL */
$sql1 = "SELECT WEEKDAY(DATE_SUB(NOW(), INTERVAL 5 HOUR)) AS DiaSemana";
$date = mysqli_query($conexion, $sql1);

if ($rows = mysqli_fetch_array($date)) {
    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    $day = $dias[$rows['DiaSemana']];
    //echo $diaActual;
}

// Función para obtener la imagen según la temporada
function obtenerImagenPorTemporada($temporada)
{
    $imagenes = [
        "Invierno" => "../img/winter.png",
        "Primavera" => "../img/spring.png",
        "Verano" => "../img/sun.png",
        "Otoño" => "../img/autumn.png"
    ];
    return $imagenes[$temporada] ?? null; // Devuelve la imagen correspondiente o null si no se encuentra
}

if (isset($_GET['filtrar']) && isset($_GET['anis'])) {
    $estado = $_GET['anis'];
    $sql1 = "SELECT * FROM num_horario WHERE Num='$estado'";
    $result = mysqli_query($conexion, $sql1);
    $row = mysqli_fetch_assoc($result);

    // Asignación de variables a partir de la consulta
    $año = $row['Ano'];
    $tempo = $row['Temporada'];
    $img = obtenerImagenPorTemporada($tempo);
} else {
    // Asignación automática basada en el mes actual
    $mes = date("F");
    $año = date("Y");

    // Mapear meses a temporadas
    $temporadas = [
        ["Invierno", ["January", "February", "March"]],
        ["Primavera", ["April", "May", "June"]],
        ["Verano", ["July", "August", "September"]],
        ["Otoño", ["October", "November", "December"]]
    ];

    // Buscar temporada correspondiente al mes
    foreach ($temporadas as $temporada) {
        if (in_array($mes, $temporada[1])) {
            $tempo = $temporada[0];
            $img = obtenerImagenPorTemporada($tempo);
            break;
        }
    }
}







// Get schedule number
$num_query = "SELECT * FROM `num_horario` WHERE Temporada='$tempo' AND Ano='$año'";
$num_result = mysqli_query($conexion, $num_query);
$num_row = mysqli_fetch_assoc($num_result);
$number = $num_row['Num'];

// Days of the week
$dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo', 'Indefinido'];

// Colors for each day
$day_colors = [
    'Lunes' => 'blue',
    'Martes' => 'green',
    'Miercoles' => 'yellow',
    'Jueves' => 'red',
    'Viernes' => 'pink',
    'Sabado' => 'purple',
    'Domingo' => 'blue',
    'Indefinido' => 'gray'
];

// Prepare results for each day
$daily_results = [];
$total_hours = '00:00:00';
$total_anime = 0;

// Filtrar los horarios y animes según la temporada seleccionada
if (isset($_GET['filtrar']) && isset($_GET['anis'])) {
    $estado = $_GET['anis'];

    foreach ($dias as $dia) {
        // Fetch anime for the day
        $anime_query = "SELECT Nombre, Duracion FROM horario where dia='$dia' AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC";
        $anime_result = mysqli_query($conexion, $anime_query);

        // Fetch total hours for the day
        $hours_query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario where dia='$dia' AND num_horario='$estado'";
        $hours_result = mysqli_query($conexion, $hours_query);
        $hours_row = mysqli_fetch_assoc($hours_result);

        $daily_results[$dia] = [
            'animes' => mysqli_fetch_all($anime_result, MYSQLI_ASSOC),
            'hours' => $hours_row['hours'] ?: '00:00:00'
        ];
    }

    // Total weekly hours and anime count
    $total_hours_query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario where num_horario='$estado' AND Dia!='Indefinido'";
    $total_hours_result = mysqli_query($conexion, $total_hours_query);
    $total_hours_row = mysqli_fetch_assoc($total_hours_result);
    $total_hours = $total_hours_row['hours'];

    $total_anime_query = "SELECT COUNT(*) AS Total_Registros FROM horario where num_horario='$estado' AND Dia!='Indefinido'";
    $total_anime_result = mysqli_query($conexion, $total_anime_query);
    $total_anime_row = mysqli_fetch_assoc($total_anime_result);
    $total_anime = $total_anime_row['Total_Registros'];
} else {

    foreach ($dias as $dia) {
        // Fetch anime for the day
        $anime_query = "SELECT Nombre, Duracion FROM emision WHERE Dia='$dia' AND Emision='Emision' ORDER BY LENGTH(Nombre) DESC";
        $anime_result = mysqli_query($conexion, $anime_query);

        // Fetch total hours for the day
        $hours_query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='$dia' AND Emision='Emision'";
        $hours_result = mysqli_query($conexion, $hours_query);
        $hours_row = mysqli_fetch_assoc($hours_result);

        $daily_results[$dia] = [
            'animes' => mysqli_fetch_all($anime_result, MYSQLI_ASSOC),
            'hours' => $hours_row['hours'] ?: '00:00:00'
        ];
    }

    // Total weekly hours and anime count
    $total_hours_query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Emision='Emision' AND Dia!='Indefinido'";
    $total_hours_result = mysqli_query($conexion, $total_hours_query);
    $total_hours_row = mysqli_fetch_assoc($total_hours_result);
    $total_hours = $total_hours_row['hours'];

    $total_anime_query = "SELECT COUNT(*) AS Total_Registros FROM emision WHERE Emision='Emision' AND Dia!='Indefinido'";
    $total_anime_result = mysqli_query($conexion, $total_anime_query);
    $total_anime_row = mysqli_fetch_assoc($total_anime_result);
    $total_anime = $total_anime_row['Total_Registros'];
}

// El resto del código permanece igual
$anime_result = mysqli_query($conexion, $anime_query);





?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Semanal de Anime</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f8;
            font-family: 'Inter', sans-serif;
        }

        .day-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .day-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 98% !important;
            }
        }
    </style>
</head>
<?php include('../menu.php'); ?>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                <div class="flex space-x-4">
                    <button
                        type="button"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 shadow-md"
                        data-toggle="modal"
                        data-target="#NuevoHorario">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Nuevo Anime
                    </button>
                    <button
                        type="button"
                        class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 shadow-md"
                        onclick="myFunction()">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar por Temporada
                    </button>
                </div>
            </div>

            <div
                id="myDIV"
                class="transition-all duration-300 ease-in-out transform origin-top"
                style="display:none;">
                <form action="" method="GET" class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                        <select
                            name="anis"
                            class="w-full md:w-auto flex-grow px-4 py-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                            <?php
                            // Obtener los valores de la base de datos y mostrar las opciones
                            $query = $conexion->query("SELECT * FROM `num_horario` ORDER BY `num_horario`.`Num` DESC;");
                            while ($valores = mysqli_fetch_array($query)) {
                                // Verificar si el valor actual es el que ha sido seleccionado
                                $selected = (isset($_GET['anis']) && $_GET['anis'] == $valores['Num']) ? 'selected' : '';
                                echo '<option value="' . $valores['Num'] . '" ' . $selected . '>' . $valores['Num'] . '/' . $valores['Ano'] . '-' . $valores['Temporada'] . '</option>';
                            }
                            ?>
                        </select>

                        <div class="flex space-x-4">
                            <button
                                type="submit"
                                name="filtrar"
                                class="px-6 py-2 border-2 border-blue-500 text-blue-500 rounded-md hover:bg-blue-50 transition duration-300 font-semibold">
                                Filtrar
                            </button>
                            <button
                                type="submit"
                                name="borrar"
                                class="px-6 py-2 border-2 border-red-500 text-red-500 rounded-md hover:bg-red-50 transition duration-300 font-semibold">
                                Borrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php include('ModalCrear-Horario.php'); ?>
        </div>
    </div>


    <div class="container mx-auto">
        <header class="text-center mb-10">
            <div class="flex justify-center items-center mb-4">
                <img src="<?php echo $img; ?>" alt="Ícono de Temporada" class="w-12 mr-4">
                <h1 class="text-4xl font-bold text-gray-800">Horario de Anime <?php echo $año; ?></h1>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-lg shadow-md">
                <p class="text-xl font-semibold">Programación Semanal de Anime | Edición #<?php echo $number; ?></p>
            </div>
        </header>

        <div class="grid md:grid-cols-7 gap-6">
            <!-- Días de la Semana -->
            <div class="md:col-span-7 grid grid-cols-7 gap-2 mb-6">
                <?php foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia_corto): ?>
                    <div class="text-center font-bold text-gray-600"><?php echo $dia_corto; ?></div>
                <?php endforeach; ?>
            </div>

            <!-- Tarjetas de Horarios por Día -->
            <?php foreach ($dias as $dia):
                $color = $day_colors[$dia];
                $animes = $daily_results[$dia]['animes'];
                $hours = $daily_results[$dia]['hours'];
            ?>
                <div class="day-card bg-white rounded-lg shadow-md p-4 transform transition hover:scale-105">
                    <h2 class="text-2xl font-bold text-<?php echo $color; ?>-600 mb-4 flex justify-between">
                        <?php echo $dia; ?>
                        <span class="text-sm text-gray-500"><?php echo $hours; ?></span>
                    </h2>
                    <ul class="space-y-2">
                        <?php foreach ($animes as $anime): ?>
                            <li class="bg-<?php echo $color; ?>-100 p-2 rounded hover:bg-<?php echo $color; ?>-200 transition">
                                <?php echo htmlspecialchars($anime['Nombre']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-10 text-center bg-white rounded-lg shadow-md p-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>Total de Horas Semanales
                    </h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo $total_hours; ?></p>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">
                        <i class="fas fa-film mr-2 text-purple-500"></i>Total de Animes en la Semana
                    </h3>
                    <p class="text-3xl font-bold text-indigo-600"><?php echo $total_anime; ?></p>
                </div>
            </div>
        </div>

        <footer class="mt-10 text-center text-gray-500">
            <p>© <?php echo $año_actual; ?> Seguimiento de Horario de Anime</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</body>

</html>
<?php
// Close database connection
mysqli_close($conexion);
?>