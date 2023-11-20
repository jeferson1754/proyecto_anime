<?php

require 'bd.php';

setlocale(LC_ALL, "es_ES");

$año = date("Y");
$mes = date("F");

if ($mes == "January" || $mes == "February" || $mes == "March") {
    $tempo = "Invierno";
} else if ($mes == "April" || $mes == "May" || $mes == "June") {
    $tempo = "Primavera";
} else if ($mes == "July" || $mes == "August" || $mes == "September") {
    $tempo = "Verano";
} else if ($mes == "October" || $mes == "November" || $mes == "December") {
    $tempo = "Otoño";
}

$sql1 = ("SELECT CONCAT( CASE WEEKDAY(DATE_SUB(NOW(), INTERVAL 5 HOUR)) WHEN 0 THEN 'Lunes' WHEN 1 THEN 'Martes' WHEN 2 THEN 'Miercoles' WHEN 3 THEN 'Jueves' WHEN 4 THEN 'Viernes' WHEN 5 THEN 'Sabado' WHEN 6 THEN 'Domingo' END ) AS DiaActual;");
$date      = mysqli_query($conexion, $sql1);

while ($rows = mysqli_fetch_array($date)) {

    $day = $rows[0];
    //echo $day;
}

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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <title>Horarios</title>
</head>
<style>
    .main-container {
        max-width: 600%;
        margin: 30px 20px;
    }

    table {
        width: 100%;
        background-color: white !important;
        text-align: left;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;

    }

    thead {
        background-color: #5a9b8d !important;
        color: white !important;
        border-bottom: solid 5px #0F362D !important;
    }


    tr:nth-child(even) {
        background-color: #ddd !important;
    }

    tr:hover td {
        background-color: #369681 !important;
        color: white !important;
    }

    .auto-style14 {
        display: inline-block;
        margin-right: 5px;
        /* Ajusta el valor según sea necesario */
    }
</style>

<body>
    <?php
    include 'menu.php';

    ?>

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

            $name1 = "SELECT Nombre,Dia from horario where Dia='Lunes'     AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name3 = "SELECT Nombre,Dia from horario where Dia='Martes'    AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name2 = "SELECT Nombre,Dia from horario where Dia='Domingo'   AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name4 = "SELECT Nombre,Dia from horario where Dia='Miercoles' AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name5 = "SELECT Nombre,Dia from horario where Dia='Jueves'    AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name6 = "SELECT Nombre,Dia from horario where Dia='Viernes'   AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";
            $name7 = "SELECT Nombre,Dia from horario where Dia='Sabado'    AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";

            $name8 = "SELECT * from horario where Dia='Indefinido' AND num_horario='$estado' OR  Dia='' AND num_horario='$estado' ORDER BY LENGTH(Nombre) DESC;";

            $result1     = mysqli_query($conexion, $name1);
            $result2     = mysqli_query($conexion, $name2);
            $result3     = mysqli_query($conexion, $name3);
            $result4     = mysqli_query($conexion, $name4);
            $result5     = mysqli_query($conexion, $name5);
            $result6     = mysqli_query($conexion, $name6);
            $result7     = mysqli_query($conexion, $name7);

            $result8     = mysqli_query($conexion, $name8);

            $sql3 = ("SELECT COUNT(dia),Dia FROM horario where dia='Domingo'   AND num_horario='$estado';");
            $sql2 = ("SELECT COUNT(dia),Dia FROM horario where dia='Lunes'     AND num_horario='$estado';");
            $sql4 = ("SELECT COUNT(dia),Dia FROM horario where dia='Martes'    AND num_horario='$estado';");
            $sql5 = ("SELECT COUNT(dia),Dia FROM horario where dia='Miercoles' AND num_horario='$estado';");
            $sql6 = ("SELECT COUNT(dia),Dia FROM horario where dia='Jueves'    AND num_horario='$estado';");
            $sql7 = ("SELECT COUNT(dia),Dia FROM horario where dia='Viernes'   AND num_horario='$estado';");
            $sql8 = ("SELECT COUNT(dia),Dia FROM horario where dia='Sabado'    AND num_horario='$estado';");

            $sql9 = ("SELECT COUNT(dia),Dia FROM horario where dia='Indefinido' AND num_horario='$estado' OR Dia='' AND num_horario='$estado';");


            $lunes      = mysqli_query($conexion, $sql2);
            $domingo    = mysqli_query($conexion, $sql3);
            $martes     = mysqli_query($conexion, $sql4);
            $miercoles  = mysqli_query($conexion, $sql5);
            $jueves     = mysqli_query($conexion, $sql6);
            $viernes    = mysqli_query($conexion, $sql7);
            $sabado     = mysqli_query($conexion, $sql8);

            $indefinido     = mysqli_query($conexion, $sql9);

            $COUNT1 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Lunes'     AND num_horario='$estado';");
            $COUNT2 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Martes'    AND num_horario='$estado';");
            $COUNT3 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Miercoles' AND num_horario='$estado';");
            $COUNT4 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Jueves'    AND num_horario='$estado';");
            $COUNT5 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Viernes'   AND num_horario='$estado';");
            $COUNT6 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Sabado'    AND num_horario='$estado';");
            $COUNT7 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Domingo'   AND num_horario='$estado';");
            $COUNTFINAL = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia!='Indefinido' AND num_horario='$estado';");
            $COUNTANIME = ("SELECT COUNT(*) AS Total_Registros FROM horario WHERE num_horario='$estado';");

            $COUNT8 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM horario WHERE Dia='Indefinido'  AND num_horario='$estado' OR Dia='' AND num_horario='$estado';");

            $lunesco        = mysqli_query($conexion, $COUNT1);
            $martesco       = mysqli_query($conexion, $COUNT2);
            $miercolesco    = mysqli_query($conexion, $COUNT3);
            $juevesco       = mysqli_query($conexion, $COUNT4);
            $viernesco      = mysqli_query($conexion, $COUNT5);
            $sabadoco       = mysqli_query($conexion, $COUNT6);
            $domingoco      = mysqli_query($conexion, $COUNT7);

            $indefinidoco      = mysqli_query($conexion, $COUNT8);

            $total_final    = mysqli_query($conexion, $COUNTFINAL);
            $total_anime      = mysqli_query($conexion, $COUNTANIME);

            while ($mostrar = mysqli_fetch_array($lunesco)) {

                $final1 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($martesco)) {


                $final2 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($miercolesco)) {


                $final3 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($juevesco)) {


                $final4 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($viernesco)) {


                $final5 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($sabadoco)) {

                $final6 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($domingoco)) {

                $final7 = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($indefinidoco)) {

                $final8 = $mostrar['0'];
            }
            while ($mostrar = mysqli_fetch_array($total_final)) {

                $final_total = $mostrar['0'];
            }
            while ($mostrar = mysqli_fetch_array($total_anime)) {

                $final_anime = $mostrar['0'];
            }

            //Lunes
            while ($mostrar = mysqli_fetch_array($result1)) {
                while ($l = mysqli_fetch_array($lunes)) {

            ?>


                    <tr>
                        <td rowspan="<?php echo $l['0'] ?>"><?php echo $l['1'] ?><br> <?php echo $final1 ?></td>
                    <?php
                }
                    ?>
                    <td><?php echo $mostrar['Nombre'] ?></td>
                    </tr>



                    <?php

                } //Fin Lunes

                //Martes


                while ($mostrar = mysqli_fetch_array($result3)) {
                    while ($ma = mysqli_fetch_array($martes)) {
                        $colum1 = $ma[0];

                    ?>


                        <tr>
                            <td rowspan="<?php echo $ma['0'] ?>"><?php echo $ma['1'] ?><br><?php echo $final2 ?></td>
                        <?php
                    }
                        ?>
                        <td><?php echo $mostrar['Nombre'] ?></td>
                        </tr>

                    <?php
                } //Fin Martes

                    ?>


                    <?php

                    //Miercoles

                    while ($mostrar = mysqli_fetch_array($result4)) {
                        while ($mi = mysqli_fetch_array($miercoles)) {
                    ?>


                            <tr>
                                <td rowspan="<?php echo $mi['0'] ?>"><?php echo $mi['1'] ?><br><?php echo $final3 ?></td>
                            <?php
                        }

                            ?>
                            <td><?php echo $mostrar['Nombre'] ?></td>

                            </tr>



                            <?php

                        } //Fin Miercoles

                        //Jueves
                        while ($mostrar = mysqli_fetch_array($result5)) {
                            while ($j = mysqli_fetch_array($jueves)) {
                            ?>


                                <tr>
                                    <td rowspan="<?php echo $j['0'] ?>"><?php echo $j['1'] ?><br><?php echo $final4 ?></td>
                                <?php
                            }
                                ?>
                                <td><?php echo $mostrar['Nombre'] ?></td>
                                </tr>



                                <?php

                            } //Fin Jueves


                            //Viernes
                            while ($mostrar = mysqli_fetch_array($result6)) {
                                while ($v = mysqli_fetch_array($viernes)) {
                                ?>


                                    <tr>
                                        <td rowspan="<?php echo $v['0'] ?>"><?php echo $v['1'] ?><br><?php echo $final5 ?></td>
                                    <?php
                                }
                                    ?>
                                    <td><?php echo $mostrar['Nombre'] ?></td>
                                    </tr>



                                    <?php

                                } //Fin Viernes

                                //Sabado
                                while ($mostrar = mysqli_fetch_array($result7)) {
                                    while ($s = mysqli_fetch_array($sabado)) {
                                    ?>


                                        <tr>
                                            <td rowspan="<?php echo $s['0'] ?>"><?php echo $s['1'] ?><br><?php echo $final6 ?></td>
                                        <?php
                                    }
                                        ?>
                                        <td><?php echo $mostrar['Nombre'] ?></td>
                                        </tr>



                                        <?php

                                    } //Fin Sabado

                                    //Domingo
                                    while ($mostrar = mysqli_fetch_array($result2)) {
                                        while ($d = mysqli_fetch_array($domingo)) {
                                        ?>

                                            <tr>
                                                <td rowspan="<?php echo $d['0'] ?>"><?php echo $d['1'] ?><br><?php echo $final7 ?></td>
                                            <?php
                                        }
                                            ?>
                                            <td><?php echo $mostrar['Nombre'] ?></td>
                                            </tr>

                                            <?php

                                        }   //Fin Domingo

                                        //Indefinido
                                        while ($mostrar = mysqli_fetch_array($result8)) {
                                            while ($i = mysqli_fetch_array($indefinido)) {
                                            ?>

                                                <tr>
                                                    <td rowspan="<?php echo $i['0'] ?>"><?php echo $i['1'] ?><br><?php echo $final8 ?></td>
                                                <?php
                                            }
                                                ?>
                                                <td><?php echo $mostrar['Nombre'] ?></td>
                                                <td>
                                                    <button type=" button" class="btn btn-primary" data-toggle="modal" data-target="#editHorarios<?php echo $mostrar['Nombre']; ?>">
                                                        Editar
                                                    </button>
                                                </td>
                                                </tr>

                                                <?php include('ModalEditar-Horario.php');  ?>

                                            <?php

                                        } //Fin Indefinido
                                            ?>
        </table>
    </div>
    <div style="text-align: center;">
        <h2 class="auto-style14">Horas Total Semana: <?php echo $final_total ?> -</h2>
        <h2 class="auto-style14">Total Animes Semana : <?php echo $final_anime ?> </h2>
    </div>
    <br>
    <br>

    <?php
    include 'scripts.php';
    $conexion = null;
    ?>
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