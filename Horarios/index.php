<?php

require '../bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");

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

$num = "SELECT * FROM `num_horario` where Temporada='$tempo' and Ano='$año'";
$result2     = mysqli_query($conexion, $num);

while ($mostrar = mysqli_fetch_array($result2)) {

    $number = $mostrar['Num'];
}

$mayusculas = strtoupper($tempo);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/horarios.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <title>Horario</title>
</head>

<body>

    <?php include('../menu.php'); ?>

    <div class="col-sm">

        <!--- Formulario para registrar Cliente --->
        <div class="auto-style12" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 22px; font-weight: bold;"><?php echo $año ?><img style="width:50px;" src="<?php echo $img ?>"></div>
        <div class="auto-style13" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 22px; font-weight: bold;">N° <?php echo $number; ?></div>
        <div class="image-container">
            <img class="liston" src="../img/liston.png" alt="Imagen con Listón">
            <div class="texto-superpuesto">
                HORARIO DE ANIME
            </div>
        </div>
    </div>

    <div class="main-container">

        <table>

            <?php

            // Días de la semana
            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

            // Inicializar el array para almacenar los resultados
            $resultados = [];

            // Consultas y resultados
            foreach ($dias as $dia) {
                $consulta = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='$dia' ORDER BY LENGTH(Nombre) DESC";
                $resultado = mysqli_query($conexion, $consulta);

                // Verificar si hay resultados
                if ($resultado) {
                    // Guardar los resultados en el array
                    $resultados[$dia] = $resultado;
                } else {
                    // Manejar errores si es necesario
                    echo "Error en la consulta para $dia";
                }
            }

            // Array para almacenar los resultados
            $resultados2 = [];

            // Consultas y resultados
            foreach ($dias as $dia) {
                $consulta = "SELECT COUNT(dia) as conteo,Dia FROM emision where dia='$dia' AND Emision='Emision';";
                $resultado = mysqli_query($conexion, $consulta);

                // Verificar si hay resultados
                if ($resultado) {
                    // Guardar los resultados en el array
                    $resultados2[$dia] = $resultado;
                } else {
                    // Manejar errores si es necesario
                    echo "Error en la consulta para $dia";
                }
            }

            // Array para almacenar los resultados
            $resultados3 = [];

            // Consultas y resultados
            foreach ($dias as $dia) {
                $consulta = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='$dia' and Emision='Emision';";
                $resultado = mysqli_query($conexion, $consulta);

                // Verificar si hay resultados
                if ($resultado) {
                    // Guardar los resultados en el array
                    $resultados3[$dia] = $resultado;
                } else {
                    // Manejar errores si es necesario
                    echo "Error en la consulta para $dia";
                }
            }

            $consulta = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Emision='Emision' and Dia!='Indefinido';";
            $resultado = mysqli_query($conexion, $consulta);

            // Verificar si hay resultados
            if ($resultado) {
                // Obtener el resultado directamente
                $fila = mysqli_fetch_assoc($resultado);

                // Asignar el valor a la variable
                $total_tiempo = $fila['hours'];

                // Liberar memoria del resultado
                mysqli_free_result($resultado);
            } else {
                // Manejar errores si es necesario
                echo "Error en la consulta";
            }

            $consulta = "SELECT COUNT(*) AS Total_Registros FROM emision WHERE Emision='Emisión' AND Dia!='Indefinido';";
            $resultado = mysqli_query($conexion, $consulta);

            // Verificar si hay resultados
            if ($resultado) {
                // Obtener el resultado directamente
                $fila = mysqli_fetch_assoc($resultado);

                // Asignar el valor a la variable
                $total_anime = $fila['Total_Registros'];

                // Liberar memoria del resultado
                mysqli_free_result($resultado);
            } else {
                // Manejar errores si es necesario
                echo "Error en la consulta";
            }

            foreach ($dias as $dia) {
                $resultadosDia = mysqli_fetch_all($resultados[$dia], MYSQLI_ASSOC);
                $resultadosDia2 = mysqli_fetch_all($resultados2[$dia], MYSQLI_ASSOC);
                $resultadosDia3 = mysqli_fetch_all($resultados3[$dia], MYSQLI_ASSOC);

                // Verificar si hay resultados
                if (!empty($resultadosDia) && !empty($resultadosDia2) && !empty($resultadosDia3)) {
                    // Iterar sobre los resultados
                    foreach ($resultadosDia2 as $mostrar2) {
            ?>
                        <tr>
                            <td rowspan="<?php echo $mostrar2['conteo'] ?>" class="auto-style3 <?php echo $dia ?>">

                                <div class="auto-style8"><?php echo $mostrar2['Dia']   ?>
                                    <?php
                                    foreach ($resultadosDia3 as $mostrar3) {
                                    ?>

                                        <div class="auto-style4"> <?php echo $mostrar3['hours'] ?></div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </td>
                        <?php
                    }

                    // Iterar sobre los resultados
                    foreach ($resultadosDia as $mostrar) {
                        ?>
                            <td><?php echo $mostrar['Nombre'] ?></td>
                        </tr>
            <?php
                    }
                } // Fin del día
            }

            foreach ($resultados as $resultado) {
                mysqli_free_result($resultado);
            }

            foreach ($resultados2 as $resultado) {
                mysqli_free_result($resultado);
            }

            foreach ($resultados3 as $resultado) {
                mysqli_free_result($resultado);
            }
            ?>

        </table>
    </div>

    <div style="text-align: center;">
        <h2 class="auto-style14">Horas Total Semana: <?php echo $total_tiempo ?> -</h2>

        <h2 class="auto-style14">Total Animes Semana : <?php echo $total_anime ?> </h2>
    </div>
    <br>
    <br>
</body>
<?php

$conexion = null;
?>

</html>