<?php

require '../bd.php';

//-----------GRAFICO CIRCULAR---------------
$consulta = "SELECT 'op' AS Tipo, COUNT(*) AS Recuento FROM op
             UNION ALL
             SELECT 'ed' AS Tipo, COUNT(*) AS Recuento FROM ed";
$resultado = $conexion->query($consulta);
// Verificar si hay resultados
if ($resultado) {
    // Guardar resultados en variables asociativas
    $resultados = $resultado->fetch_all(MYSQLI_ASSOC);
    // Obtener los resultados por tipo
    $opResult = $resultados[0]['Recuento'] ?? 0;
    $edResult = $resultados[1]['Recuento'] ?? 0;
    // Liberar memoria del resultado
    $resultado->free();
} else {
    // Manejar errores si es necesario
    $opResult = 0;
    $edResult = 0;
}

$totalResult = $opResult + $edResult;

//-----------GRAFICO CIRCULAR GRANDE---------------

$consulta1 = "SELECT autor.Autor, COUNT(op.ID) AS CantidadRepeticiones
              FROM autor
              JOIN op ON autor.ID = op.ID_Autor
              WHERE autor.ID != 1
              GROUP BY autor.ID, autor.Autor
              ORDER BY CantidadRepeticiones DESC
              LIMIT 10";

$resultado1 = $conexion->query($consulta1);

$resultados1 = ($resultado1) ? $resultado1->fetch_all(MYSQLI_ASSOC) : array();

// Variables individuales para los 10 primeros resultados
for ($i = 1; $i <= 10; $i++) {
    ${"autor" . $i} = isset($resultados1[$i - 1]) ? $resultados1[$i - 1]['Autor'] : '';
    ${"repeticiones" . $i} = isset($resultados1[$i - 1]) ? $resultados1[$i - 1]['CantidadRepeticiones'] : 0;
}

// Liberar memoria del resultado
if ($resultado1) {
    $resultado1->free();
}

//-----------GRAFICO DE AREA ---------------

// Función para ejecutar consulta y construir el array de datos
function fetchData($conexion, $tabla)
{
    $sql = "SELECT YEARWEEK(Fecha_Ingreso) AS Semana, COUNT(*) AS RecuentoSemana
            FROM $tabla
            WHERE YEARWEEK(Fecha_Ingreso) BETWEEN YEARWEEK(CURDATE() - INTERVAL 4 WEEK) AND YEARWEEK(CURDATE())
            GROUP BY Semana
            ORDER BY Semana";

    // Ejecutar la consulta
    $resultado = $conexion->query($sql);

    // Inicializar un array para almacenar los datos
    $data = array();

    // Procesar los resultados y construir el array
    while ($row = $resultado->fetch_assoc()) {
        $data[] = $row["RecuentoSemana"];
    }

    // Liberar memoria del resultado
    $resultado->free();

    return $data;
}
// Obtener datos para OP
$dataOp = fetchData($conexion, "op");
// Obtener datos para ED
$dataEd = fetchData($conexion, "ed");

//-----------GRAFICO DE CASILLAS FALTANTES ---------------

// Consulta SQL
$consulta = "SELECT (SELECT COUNT(*) FROM op) + (SELECT COUNT(*) FROM ed) AS total_tablas;";
// Ejecutar la consulta
$resultado = $conexion->query($consulta);
// Obtener los resultados como un array asociativo
$resultados = $resultado->fetch_assoc();
$total_tablas = $resultados['total_tablas'];
// Liberar memoria del resultado
$resultado->free();


$consulta = "
    SELECT
        (SELECT COUNT(*) FROM op WHERE Cancion='') +
        (SELECT COUNT(*) FROM ed WHERE Cancion='') AS sinnombre,
        
        (SELECT COUNT(*) FROM op WHERE ID_Autor=1 OR ID_Autor='') +
        (SELECT COUNT(*) FROM ed WHERE ID_Autor=1 OR ID_Autor='') AS sinautor,
        
        (SELECT COUNT(*) FROM op WHERE Link='' OR Estado_Link='Faltante' OR Estado_link!='Correcto') +
        (SELECT COUNT(*) FROM ed WHERE Link='' OR Estado_Link='Faltante' OR Estado_link!='Correcto')  AS sinlink,
        
        (SELECT COUNT(*) FROM op WHERE Link_Iframe='') +
        (SELECT COUNT(*) FROM ed WHERE Link_Iframe='') AS sinifra;
";

$resultado = $conexion->query($consulta);

if ($resultado) {
    $resultados = $resultado->fetch_assoc();

    // Obtén los valores de cada categoría
    $sinnombre = $resultados['sinnombre'];
    $sinautor = $resultados['sinautor'];
    $sinlink = $resultados['sinlink'];
    $sinifra = $resultados['sinifra'];

    // Suma total
    $tablas_vacias = $sinnombre + $sinautor + $sinlink + $sinifra;

    // Liberar memoria del resultado
    $resultado->free();
} else {
    // Manejar errores si es necesario
    echo "Error en la consulta: " . $conexion->error;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graficos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><!--Graficos de Area-->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script><!--Graficos de Pie-->
</head>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: center;
    }

    a {
        color: inherit !important;
        text-decoration: none !important;
        /* Otros estilos aquí si es necesario */
    }

    h1 {
        text-align: center;
    }

    .hover-text {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hover-text .hidden-text {
        display: none;
        background-color: #f0f0f0;
    }

    .hover-text:hover .visible-text {
        display: none;
    }

    .hover-text:hover .hidden-text {
        display: block;
        background-color: white;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .content {
        width: 35%;
        height: 300px;
    }

    iframe,
    img {
        width: 100%;
        height: 100%;
        max-width: 1000px;
        border: 0;
    }

    #pie,
    #main,
    #pie2 {
        width: 600px;
        height: 400px;
        /* Borde opcional para visualización */
    }

    #areaChart {
        /*height: 300px !important;*/
        max-height: 300px;
        max-width: 45%;
    }

    table {
        width: 35%;
        background-color: white;
        text-align: center;
        border-collapse: collapse;
        font-size: 25px;
    }


    thead {
        background-color: #084887;
        color: white;
    }


    tr:nth-child(even) {
        background-color: #ddd;
    }

    tr:hover td {
        background-color: #909cc2;
        color: white;
    }
</style>

<body>
    <?php

    // Consulta SQL
    $sql = "(SELECT * FROM op ORDER BY RAND() LIMIT 1) UNION ALL (SELECT * FROM ed ORDER BY RAND() LIMIT 1) ORDER BY RAND() LIMIT 1";

    // Ejecutar la consulta
    $result = $conexion->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Obtener los datos
        $row = $result->fetch_assoc();

        $sql1 = "SELECT * FROM `op` WHERE ID='$row[ID]'";
        $result1 = $conexion->query($sql1);
        $fila = $result1->fetch_assoc();

        $sql2 = "SELECT * FROM `autor` WHERE ID='$row[ID_Autor]'";
        $result2 = $conexion->query($sql2);
        $columna = $result2->fetch_assoc();
        //echo "" . $columna["Autor"] . "</br>";

        echo "<h1 class='hover-text'>";

        if ($fila["Nombre"] == $row["Nombre"]) {
            echo "<span class='visible-text'>" . $row["Nombre"] . " - OP " . $row["Opening"] . "</span>";
        } else {
            echo "<span class='visible-text'>" . $row["Nombre"] . " - ED " . $row["Opening"] . "</span>";
        }

        echo "<a  href=" . $row["Link"] . " target='_blanck'>";
        if ($columna["Autor"] != "") {
            echo "<span class='hidden-text'>" . $row["Cancion"] . " - " . $columna["Autor"] . "</span>";
        } else {
            echo "<span class='hidden-text'>" . $row["Cancion"] . "</span>";
        }
        echo "</a>";

        echo "</h1>";
    } else {
        echo "No se encontraron resultados";
    }

    $sql3 = "SELECT * FROM `autor` ORDER BY `autor`.`ID` DESC limit 5;";
    $result3 = mysqli_query($conexion, $sql3);
    ?>

    <div class="container">
        <div class="content">
            <?php
            if ($row["Link_Iframe"] == "") {
                echo "<img src='' alt='Sin video'>";
            } else {
                echo "<iframe src='" . $row["Link_Iframe"] . "'></iframe>";
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div id="pie"></div>
        <canvas id="areaChart"></canvas>
    </div>

    <div class="container">

        <table>
            <thead>
                <th>Ultimos Artistas:</th>
            </thead>
            <tbody>
                <?php
                while ($mostrar = mysqli_fetch_array($result3)) {
                    echo "<tr>";
                    echo "<td>" . $mostrar['Autor'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div id="main"></div>
    </div>

    <div class="container">
        <div id="pie2"></div>
    </div>


    <script type="text/javascript">
        //Grafico Circular
        var myChart = echarts.init(document.getElementById('pie'));
        <?php
        // Supongamos que $opResult y $edResult son variables con los resultados deseados
        // Datos para la serie del gráfico
        $data = [
            ['value' => $opResult, 'name' => 'Openings'],
            ['value' => $edResult, 'name' => 'Endings'],
        ];

        // Configuración del gráfico
        $option = [
            'title' => [
                'text' => 'Openings vs. Endings',
                'left' => 'center',
                'top' => 0
            ],
            'tooltip' => [
                'trigger' => 'item',
                'formatter' => '{a} <br/>{b}: {c} ({d}%)'
            ],
            'series' => [
                [
                    'name' => 'Cantidad de',
                    'type' => 'pie',
                    'radius' => '65%',
                    'data' => $data,
                    'emphasis' => [
                        'itemStyle' => [
                            'shadowBlur' => 10,
                            'shadowOffsetX' => 0,
                            'shadowColor' => 'rgba(0, 0, 0, 0.5)'
                        ]
                    ],
                    'label' => [
                        'show' => true,
                        'formatter' => '{b}: {d}%'
                    ],
                ]
            ]
        ];

        // Convertir a JSON
        $jsonOption = json_encode($option, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        ?>

        var option = <?php echo $jsonOption; ?>;

        myChart.setOption(option);

        //Grafico de Area
        var ctx = document.getElementById('areaChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
                datasets: [{
                    label: 'Cant. de Openings',
                    data: [<?php echo implode(", ", $dataOp); ?>],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true,
                }, {
                    label: 'Cant. de Endings',
                    data: [<?php echo implode(", ", $dataEd); ?>],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    fill: true,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });




        //Grafico Circular Grande
        var myChart2 = echarts.init(document.getElementById('main'));
        option = {
            title: {
                text: 'Artistas Más Repetidos',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
            },

            series: [{
                type: 'pie',
                radius: [50, 200],
                center: ['50%', '62%'],
                roseType: 'area',
                itemStyle: {
                    borderRadius: 0
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                data: [
                    <?php
                    $dataArray = array(
                        array('value' => $repeticiones1, 'name' => $autor1),
                        array('value' => $repeticiones2, 'name' => $autor2),
                        array('value' => $repeticiones3, 'name' => $autor3),
                        array('value' => $repeticiones4, 'name' => $autor4),
                        array('value' => $repeticiones5, 'name' => $autor5),
                        array('value' => $repeticiones6, 'name' => $autor6),
                        array('value' => $repeticiones7, 'name' => $autor7),
                        array('value' => $repeticiones8, 'name' => $autor8),
                        array('value' => $repeticiones9, 'name' => $autor9),
                        array('value' => $repeticiones10, 'name' => $autor10)
                    );

                    foreach ($dataArray as $item) {
                        echo '{';
                        echo 'value: ' . $item['value'] . ',';
                        echo "name: '{$item['name']}'";
                        echo '},';
                    }
                    ?>

                ]
            }]
        };
        myChart2.setOption(option);

        var myChart3 = echarts.init(document.getElementById('pie2'));
        <?php
        // Supongamos que $opResult y $edResult son variables con los resultados deseados

        // Datos para la serie del gráfico
        $data1 = [
            ['value' => $tablas_vacias, 'name' => 'Faltantes'],
            ['value' => $total_tablas, 'name' => 'Total'],
        ];

        // Configuración del gráfico
        $option1 = [
            'title' => [
                'text' => 'Casillas Faltantes',
                'left' => 'center',
                'top' => 0
            ],
            'tooltip' => [
                'trigger' => 'item',
                'formatter' => '{a} <br/>{b}: {c} ({d}%)',
            ],
            'series' => [
                [
                 
                    'name' => 'Cantidad de Casillas',
                    'type' => 'pie',
                    'radius' => '65%',
                    'data' => $data1,
                    'emphasis' => [
                        'itemStyle' => [
                            'shadowBlur' => 10,
                            'shadowOffsetX' => 0,
                            'shadowColor' => 'rgba(0, 0, 0, 0.5)'
                        ]
                    ],
                    'label' => [
                        'show' => true,
                        'formatter' => '{b}: {d}%'
                    ],
                    
                ]
            ]
        ];

        // Convertir a JSON
        $jsonOption1 = json_encode($option1, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        ?>

        var option1 = <?php echo $jsonOption1; ?>;

        myChart3.setOption(option1);
    </script>
</body>

</html>