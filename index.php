<?php

require 'bd.php';
include 'update_emision.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");
//echo $mes;


$sql1 = ("SELECT CONCAT( CASE WEEKDAY(DATE_SUB(NOW(), INTERVAL 5 HOUR)) WHEN 0 THEN 'Lunes' WHEN 1 THEN 'Martes' WHEN 2 THEN 'Miercoles' WHEN 3 THEN 'Jueves' WHEN 4 THEN 'Viernes' WHEN 5 THEN 'Sabado' WHEN 6 THEN 'Domingo' END ) AS DiaActual;");

$date      = mysqli_query($conexion, $sql1);

while ($rows = mysqli_fetch_array($date)) {

    $day = $rows[0];
    //echo $day;
}

$ani1 = 0;

// Ejecutar la consulta
$ani = "SELECT ID FROM id_anime WHERE ID NOT IN (SELECT id FROM anime) ORDER BY `id_anime`.`ID` ASC LIMIT 1";
$result = mysqli_query($conexion, $ani);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Obtener el resultado de la primera fila
    $row = $result->fetch_assoc();
    // Asignar el valor de la columna ID a la variable $ani
    $ani1 = $row["ID"];
}

if ($mes == "January" || $mes == "February" || $mes == "March") {
    $tempo = "Invierno";
    $id_tempo = 1;
} else if ($mes == "April" || $mes == "May" || $mes == "June") {
    $tempo = "Primavera";
    $id_tempo = 2;
} else if ($mes == "July" || $mes == "August" || $mes == "September") {
    $tempo = "Verano";
    $id_tempo = 3;
} else if ($mes == "October" || $mes == "November" || $mes == "December") {
    $tempo = "Otoño";
    $id_tempo = 4;
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <?php
    include 'cabecera.php';
    ?>

    <title>Anime
    </title>
</head>

<style>
    .op {
        width: 25% !important;
    }

    @media screen and (max-width: 600px) {
        .op {
            margin-left: auto;
            margin-right: auto;
        }

        div#example_info {
            font-size: 10px;
        }
    }
</style>

<body>
    <?php
    include 'menu.php';
    ?>


    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->

        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#NuevoAnime">
            Nuevo Anime
        </button>
        <button type="button" class="btn btn-info " onclick="myFunction()">
            Filtrar
        </button>
        <button type="button" class="btn btn-info " onclick="myFunction2()">
            Busqueda
        </button>
        <div class="col-sm">
            <!--- Formulario para registrar Cliente --->
            <div class="class-control" id="myDIV" style="display:none;">
                <form action="" method="GET">
                    <select name="estado" class="form-control" style="width:auto;">
                        <option value="" required>Seleccione:</option>
                        <?php
                        $query = $conexion->query("SELECT ID,Estado FROM `estado`;");
                        while ($valores = mysqli_fetch_array($query)) {
                            echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" name="accion" value="Filtro">
                    <br>

                    <button class="btn btn-outline-info" type="submit" name="filtrar"> <b>Filtrar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>
            <div class="class-control" id="myDIV2" style="display:none;">
                <form action="" method="GET">
                    <input class="form-control" type="text" name="busqueda_anime" style="width:auto;" placeholder="Nombre de Anime">

                    <button class="btn btn-outline-info" type="submit" name="buscar"> <b>Buscar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>
            <?php

            $where = "ORDER BY `anime`.`id` DESC  limit 10";


            if (isset($_GET['borrar'])) {
                $busqueda = "";

                $where = "ORDER BY `anime`.`id` DESC  limit 10";
            } else if (isset($_GET['filtrar'])) {
                if (isset($_GET['estado'])) {
                    $estado   = $_REQUEST['estado'];

                    $where = "WHERE anime.Estado LIKE'%" . $estado . "%' ORDER BY `anime`.`id` DESC limit 10";
                }
            } else if (isset($_GET['buscar'])) {
                if (isset($_GET['busqueda_anime'])) {
                    $busqueda   = $_REQUEST['busqueda_anime'];


                    $where = "WHERE anime.Anime LIKE '%$busqueda%' ORDER BY `anime`.`id` DESC limit 10";
                }
            }




            ?>
        </div>
        <?php include('ModalCrear.php');  ?>
    </div>

    <div class="main-container">

        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Anime</th>
                    <th>Ultima Temporada</th>
                    <th>N° Peliculas</th>
                    <th>Ultimo Spin-Off</th>
                    <th>Estado</th>
                    <th>Año</th>
                    <th>Temporada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <?php

            $sql = "SELECT anime.id,anime.Anime,anime.Temporadas,anime.Peliculas,anime.Spin_Off,anime.Estado,anime.id_Emision,anime.id_Pendientes,anime.Ano,anime.Id_Temporada,temporada.Temporada FROM `anime` JOIN temporada ON anime.Id_Temporada=temporada.ID $where";

            $result = mysqli_query($conexion, $sql);
            //echo $sql;

            while ($mostrar = mysqli_fetch_array($result)) {
                $iden = $mostrar['id'];

            ?>


                <tr>
                    <td><?php echo $mostrar['id'] ?></td>
                    <td class="op"><?php echo $mostrar['Anime'] ?></td>
                    <td><?php echo $mostrar['Temporadas'] ?></td>
                    <td><?php echo $mostrar['Peliculas'] ?></td>
                    <td><?php echo $mostrar['Spin_Off'] ?></td>
                    <td><?php echo $mostrar['Estado'] ?></td>
                    <td><?php echo $mostrar['Ano'] ?></td>
                    <td><?php echo $mostrar['Temporada'] ?></td>
                    <td>
                        <button type=" button" class="btn btn-primary" data-toggle="modal" data-target="#editChildresn4<?php echo $mostrar['id']; ?>">
                            Editar
                        </button>

                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editChildresn1<?php echo $mostrar['id']; ?>">
                            Eliminar
                        </button>
                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ModalDelete.php'); ?>
            <?php
            }
            ?>
        </table>
    </div>
</body>
<?php
include 'scripts.php';
$conexion = null;
?>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
                order: [
                    [0, 'desc']
                ],
                language: {
                    processing: "Tratamiento en curso...",
                    search: "Buscar:",
                    lengthMenu: "Filtro de _MENU_ Animes",
                    info: "Mostrando animes del _START_ al _END_ de un total de _TOTAL_ animes",
                    infoEmpty: "No existen registros",
                    infoFiltered: "(filtrado de _MAX_ animes en total)",
                    infoPostFix: "",
                    loadingRecords: "Cargando elementos...",
                    zeroRecords: "No se encontraron los datos de tu busqueda..",
                    emptyTable: "No hay ningun registro en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Ultimo"
                    },
                    aria: {
                        sortAscending: ": Active para odernar en modo ascendente",
                        sortDescending: ": Active para ordenar en modo descendente  ",
                    }
                }


            }


        );

    });

    //Funciona
    function alerta() {
        Swal.fire({
            icon: 'success',
            title: 'Tu trabajo ha sido guardado',
            confirmButtonText: 'OK'

        })

    }

    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function myFunction2() {
        var x = document.getElementById("myDIV2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>

</html>