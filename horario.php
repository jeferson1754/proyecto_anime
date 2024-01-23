<?php

require 'bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");

if ($mes == "January" || $mes == "February" || $mes == "March") {
    $tempo = "Invierno";
    $img = "img/winter.png";
} else if ($mes == "April" || $mes == "May" || $mes == "June") {
    $tempo = "Primavera";
    $img = "img/spring.png";
} else if ($mes == "July" || $mes == "August" || $mes == "September") {
    $tempo = "Verano";
    $img = "img/sun.png";
} else if ($mes == "October" || $mes == "November" || $mes == "December") {
    $tempo = "Otoño";
    $img = "img/autumn.png";
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
    <link rel="stylesheet" type="text/css" href="./css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <title>Horario
    </title>
</head>

<body>

    <?php
    include 'menu.php';

    $num = "SELECT * FROM `num_horario` where Temporada='$tempo' and Ano='$año'";
    $result2     = mysqli_query($conexion, $num);

    while ($mostrar = mysqli_fetch_array($result2)) {

        $number = $mostrar['Num'];
    }

    $mayusculas = strtoupper($tempo);
    ?>

    <div class="col-sm">

        <!--- Formulario para registrar Cliente --->
        <div class="auto-style12" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 22px; font-weight: bold;"><?php echo $año ?><img style="width:50px;" src="<?php echo $img ?>"></div>
        <div class="auto-style13" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 22px; font-weight: bold;">N° <?php echo $number; ?></div>
        <div class="image-container">
            <img class="liston" src="./img/liston.png" alt="Imagen con Listón">
            <div class="texto-superpuesto">
                HORARIO DE ANIME
            </div>
        </div>
    </div>

    <div class="main-container">

        <table>
            <thead>

            </thead>
            <?php

            $name1 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Lunes' ORDER BY LENGTH(Nombre) DESC;";
            $name2 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Domingo' ORDER BY LENGTH(Nombre) DESC;";
            $name3 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Martes' ORDER BY LENGTH(Nombre) DESC;";
            $name4 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Miercoles' ORDER BY LENGTH(Nombre) DESC;";
            $name5 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Jueves' ORDER BY LENGTH(Nombre) DESC;";
            $name6 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Viernes' ORDER BY LENGTH(Nombre) DESC;";
            $name7 = "SELECT Nombre,Dia from emision where Emision='Emision' and Dia='Sabado' ORDER BY LENGTH(Nombre) DESC;";

            $result1     = mysqli_query($conexion, $name1);
            $result2     = mysqli_query($conexion, $name2);
            $result3     = mysqli_query($conexion, $name3);
            $result4     = mysqli_query($conexion, $name4);
            $result5     = mysqli_query($conexion, $name5);
            $result6     = mysqli_query($conexion, $name6);
            $result7     = mysqli_query($conexion, $name7);

            $sql2 = ("SELECT COUNT(dia),Dia FROM emision where dia='Lunes'      and Emision='Emision';");
            $sql3 = ("SELECT COUNT(dia),Dia FROM emision where dia='Domingo'    and Emision='Emision';");
            $sql4 = ("SELECT COUNT(dia),Dia FROM emision where dia='Martes'     and Emision='Emision';");
            $sql5 = ("SELECT COUNT(dia),Dia FROM emision where dia='Miercoles'  and Emision='Emision';");
            $sql6 = ("SELECT COUNT(dia),Dia FROM emision where dia='Jueves'     and Emision='Emision'");
            $sql7 = ("SELECT COUNT(dia),Dia FROM emision where dia='Viernes'    and Emision='Emision';");
            $sql8 = ("SELECT COUNT(dia),Dia FROM emision where dia='Sabado'     and Emision='Emision';");

            $lunes      = mysqli_query($conexion, $sql2);
            $domingo    = mysqli_query($conexion, $sql3);
            $martes     = mysqli_query($conexion, $sql4);
            $miercoles  = mysqli_query($conexion, $sql5);
            $jueves     = mysqli_query($conexion, $sql6);
            $viernes    = mysqli_query($conexion, $sql7);
            $sabado     = mysqli_query($conexion, $sql8);

            $COUNT1 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Lunes'      and Emision='Emision';");
            $COUNT2 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Martes'     and Emision='Emision';");
            $COUNT3 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Miercoles'  and Emision='Emision';");
            $COUNT4 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Jueves'     and Emision='Emision'");
            $COUNT5 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Viernes'    and Emision='Emision';");
            $COUNT6 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Sabado'     and Emision='Emision';");
            $COUNT7 = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Dia='Domingo'    and Emision='Emision';");
            $COUNTFINAL = ("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Duracion))) AS hours FROM emision WHERE Emision='Emision' and Dia!='Indefinido';");
            $COUNTANIME = ("SELECT COUNT(*) AS Total_Registros FROM emision WHERE Emision='Emisión' AND Dia!='Indefinido';");

            $lunesco        = mysqli_query($conexion, $COUNT1);
            $martesco       = mysqli_query($conexion, $COUNT2);
            $miercolesco    = mysqli_query($conexion, $COUNT3);
            $juevesco       = mysqli_query($conexion, $COUNT4);
            $viernesco      = mysqli_query($conexion, $COUNT5);
            $sabadoco       = mysqli_query($conexion, $COUNT6);
            $domingoco      = mysqli_query($conexion, $COUNT7);
            $total_final      = mysqli_query($conexion, $COUNTFINAL);
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

            while ($mostrar = mysqli_fetch_array($total_final)) {

                $final_total = $mostrar['0'];
            }

            while ($mostrar = mysqli_fetch_array($total_anime)) {

                $final_anime = $mostrar['0'];
            }


            //echo $sql2;
            //echo $name1;

            //Lunes
            while ($mostrar = mysqli_fetch_array($result1)) {
                while ($l = mysqli_fetch_array($lunes)) {

            ?>


                    <tr>
                        <td rowspan="<?php echo $l['0'] ?>" class="auto-style3">
                            <div class="auto-style8"><?php echo $l['1'] ?><div class="auto-style4"> <?php echo $final1 ?></div>
                            </div>
                        </td>
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
                            <td rowspan="<?php echo $ma['0'] ?>" class="auto-style9" style="background-color: #B9CDD9">
                                <div class="auto-style8"><?php echo $ma['1'] ?></div>
                                <div class="auto-style4"><?php echo $final2 ?></div>
                            </td>
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
                                <td rowspan="<?php echo $mi['0'] ?>" class="auto-style3" style="background-color: #EBC6C8">
                                    <div class="auto-style8"><?php echo $mi['1'] ?></div>
                                    <div class="auto-style4"><?php echo $final3 ?></div>
                                </td>
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
                                    <td rowspan="<?php echo $j['0'] ?>" class="auto-style3" style="background-color: #E4B1C2">
                                        <div class="auto-style8"><?php echo $j['1'] ?></div>
                                        <div class="auto-style4"><?php echo $final4 ?></div>
                                    </td>
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
                                        <td rowspan="<?php echo $v['0'] ?>" class="auto-style3" style="background-color: #BFD5FD">
                                            <div class="auto-style8"><?php echo $v['1'] ?></div>
                                            <div class="auto-style4"><?php echo $final5 ?></div>
                                        </td>
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
                                            <td rowspan="<?php echo $s['0'] ?>" class="auto-style3" style="background-color: #75E7FD">
                                                <div class="auto-style8"><?php echo $s['1'] ?></div>
                                                <div class="auto-style4"><?php echo $final6 ?></div>
                                            </td>
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
                                                <td rowspan="<?php echo $d['0'] ?>" class="auto-style3" style="background-color: #E1FDD1">
                                                    <div class="auto-style8"><?php echo $d['1'] ?></div>
                                                    <div class="auto-style4"><?php echo $final7 ?></div>
                                                </td>
                                            <?php
                                        }
                                            ?>
                                            <td><?php echo $mostrar['Nombre'] ?></td>
                                            </tr>

                                        <?php

                                    } //Fin Domingo

                                        ?>
        </table>
    </div>
    <div style="text-align: center;">
        <h2 class="auto-style14">Horas Total Semana: <?php echo $final_total ?> -</h2>

        <h2 class="auto-style14">Total Animes Semana : <?php echo $final_anime ?> </h2>
    </div>
    <br>
    <br>
</body>
<?php

include 'scripts.php';
$conexion = null;
?>

</html>