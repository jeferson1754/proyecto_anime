<?php

require '../bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");
$day = date("D");

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

/* ESTO DA EL DIA ACTUAL EN ESPAÑOL PARA EL MODAL*/
$sql1 = ("SELECT CONCAT( CASE WEEKDAY(DATE_SUB(NOW(), INTERVAL 5 HOUR)) WHEN 0 THEN 'Lunes' WHEN 1 THEN 'Martes' WHEN 2 THEN 'Miercoles' WHEN 3 THEN 'Jueves' WHEN 4 THEN 'Viernes' WHEN 5 THEN 'Sabado' WHEN 6 THEN 'Domingo' END ) AS DiaActual;");
$date      = mysqli_query($conexion, $sql1);

while ($rows = mysqli_fetch_array($date)) {

    $day = $rows[0];
    //echo $day;
}
/* */

$num = "SELECT * FROM `num_horario` where Temporada='$tempo' and Ano='$año'";
$result2     = mysqli_query($conexion, $num);

while ($mostrar = mysqli_fetch_array($result2)) {

    $number = $mostrar['Num'];
}
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

    <title>Horarios</title>
</head>


<body>
    <?php include('../menu.php'); ?>
    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->

        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#NuevoHorario">
            Nuevo Anime
        </button>
        <button type="button" class="btn btn-info " onclick="myFunction()">
            Filtrar por Temporada
        </button>
        <div class="class-control" id="myDIV" style="display:none;">
            <form action="" method="GET">
                <select name="anis" class="form-control" style="width:auto;">
                    <?php
                    if (isset($_GET['filtrar'])) {

                        if (isset($_GET['anis'])) {
                            $estado   = $_REQUEST['anis'];
                            $query = $conexion->query("SELECT * FROM `num_horario` ORDER BY `num_horario`.`Num` DESC;");
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="' . $valores['Num'] . '">' . $valores['Ano'] . '-' . $valores['Temporada'] . '</option>';
                            }
                        }
                    } else {
                        echo "<option value=''> Seleccione: </option>";
                        $query = $conexion->query("SELECT * FROM `num_horario` ORDER BY `num_horario`.`Num` DESC;");
                        while ($valores = mysqli_fetch_array($query)) {
                            echo '<option value="' . $valores['Num'] . '">' . $valores['Ano'] . '-' . $valores['Temporada'] . '</option>';
                        }
                    }
                    ?>
                </select>


                <button class="btn btn-outline-info" type="submit" name="filtrar"> <b>Filtrar </b> </button>
                <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>

            </form>
        </div>
        <?php include('ModalCrear-Horario.php');  ?>
    </div>
    <?php


    if (isset($_GET['borrar'])) {
        $estado   = $number;
        echo '<h5 class="number" style="text-align:left;margin-left: 5%;">N°:' . $estado . '</h5>';
        echo '<h1 class="tempo" style="text-align:center">' . $año . '-' . $tempo . '</h1>';
    } else if (isset($_GET['filtrar'])) {
        if (isset($_GET['anis'])) {
            $estado   = $_REQUEST['anis'];
            $sql1 = $conexion->query("SELECT * from num_horario where Num='$estado';");
            while ($consulta = mysqli_fetch_array($sql1)) {
                echo '<h5 class="number" style="text-align:left;margin-left: 5%;">N°:' . $estado . '</h5>';
                echo '<h1 style="text-align:center">' . $consulta['Ano'] . '-' . $consulta['Temporada'] . '</h1>';
            }
        }
    } else {
        $estado   = $number;
        echo '<h5 class="number" style="text-align:left;margin-left: 5%;">N°:' . $estado . '</h5>';
        echo '<h1 class="tempo" style="text-align:center">' . $año . '-' . $tempo . '</h1>';
    }



    ?>

    </div>

    <div class="main-container">

        <table>
            <thead>
                <tr>
                    <th>Dias</th>
                    <th>Anime</th>
                </tr>
            </thead>
            <?php

            $existe = "SELECT COUNT(ID) FROM horario where num_horario='$estado';";
            $result     = mysqli_query($conexion, $existe);
            //echo $existe . "<br>";

            while ($mostrar = mysqli_fetch_array($result)) {

                $final = $mostrar['0'];
            }
            //echo $final;

            // Días de la semana
            $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo', 'Indefinido'];

            // Inicializar el array para almacenar los resultados
            $resultados = [];

            // Consultas y resultados
            foreach ($dias as $dia) {
                $consulta = "SELECT Nombre,Dia FROM horario where dia='$dia' AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC";
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

            //$name8 = "SELECT * from horario where Dia='Indefinido' AND num_horario='$estado' OR  Dia='' AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";

            // Array para almacenar los resultados
            $resultados2 = [];

            // Consultas y resultados
            foreach ($dias as $dia) {
                $consulta = "SELECT COUNT(dia) as conteo,Dia FROM horario where dia='$dia' AND num_horario='$estado';";
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
                $consulta = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='$dia' AND num_horario='$estado';";
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

            $consulta = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia!='Indefinido' AND num_horario='$estado';";
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

            $consulta = "SELECT COUNT(*) AS Total_Registros FROM horario WHERE num_horario='$estado';";
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
                }
            } ?>
        </table>
    </div>
    <div style="text-align: center;">
        <h2 class="auto-style14">Horas Total Semana: <?php echo $total_tiempo ?> -</h2>
        <h2 class="auto-style14">Total Animes Semana : <?php echo $total_anime ?> </h2>
    </div>
    <br>
    <br>

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