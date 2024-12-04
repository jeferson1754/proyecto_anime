<?php
require '../bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");

// Determine season and image
if ($mes == "January" || $mes == "February" || $mes == "March") {
    $tempo = "Invierno";
    $img = "../img/winter.png";
} else if ($mes == "April" || $mes == "May" || $mes == "June") {
    $tempo = "Primavera";
    $img = "../img/spring.png";
} else if ($mes == "July" || $mes == "August" || $mes == "September") {
    $tempo = "Verano";
    $img = "../img/sun.png";
} else if ($mes == "October" || $mes == "November" || $mes == "December") {
    $tempo = "Otoño";
    $img = "../img/autumn.png";
}

// Get schedule number
$num_query = "SELECT * FROM `num_horario` WHERE Temporada='$tempo' AND Ano='$año'";
$num_result = mysqli_query($conexion, $num_query);
$num_row = mysqli_fetch_assoc($num_result);
$number = $num_row['Num'];

// Days of the week
$dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

// Colors for each day
$day_colors = [
    'Lunes' => 'blue',
    'Martes' => 'green',
    'Miercoles' => 'yellow',
    'Jueves' => 'red',
    'Viernes' => 'pink',
    'Sabado' => 'purple',
    'Domingo' => 'blue'
];

// Prepare results for each day
$daily_results = [];
$total_hours = '00:00:00';
$total_anime = 0;

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
            <p>© <?php echo $año; ?> Seguimiento de Horario de Anime</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>
<?php
// Close database connection
mysqli_close($conexion);
?>