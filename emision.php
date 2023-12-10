<?php

require 'bd.php';
// Establecer la zona horaria para Santiago de Chile.
date_default_timezone_set('America/Santiago');

// Obtener la fecha y hora actual con 5 horas de retraso.
$fecha_actual_retrasada = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Array con los nombres de los días en español.
$nombres_dias = array(
    'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'
);

// Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.).
$numero_dia = date('w', strtotime($fecha_actual_retrasada));

// Obtener el nombre del día actual en español.
$nombre_dia = $nombres_dias[$numero_dia];

//echo 'Hoy es ' . $nombre_dia;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'cabecera.php';
    ?>
    <style>
        @media screen and (max-width: 600px) {
            div#example_info {
                font-size: 10px;
            }
        }
    </style>
    <title>Emision
    </title>
</head>


<body>

    <?php
    include 'menu.php';
    ?>
    <div class="col-sm">
        <div class="container-fluid flex-container">
            <form action="" method="GET">
                <button class="btn btn-outline-info mostrar" type="submit" name="enviar"><b>HOY </b> </button>
                <button class="btn btn-outline-info mostrar" type="submit" name="borrar"> <b>Borrar </b> </button>
                <input type="hidden" name="accion" value="HOY">

                <button type="button" class="btn btn-info mostrar" onclick="myFunction()">
                    Filtrar
                </button>
                <button type="button" class="btn btn-info ocultar" data-toggle="modal" data-target="#ModalTotal">
                    Actualizar Capitulos
                </button>
                <button class='btn btn-outline-info mostrar' type='button' id="miBoton"> <b>Marcar Todos Vistos </b> </button>

            </form>
        </div>

        <div class="col-sm">
            <div class="class-control" id="myDIV" style="display:none;">
                <form action="" method="GET">
                    <select name="dias" class="form-control" style="width:auto;">
                        <option value="" required>Seleccione:</option>
                        <?php
                        $query = $conexion->query("SELECT DISTINCT(e.Dia) FROM emision e INNER JOIN dias ot ON e.Dia = ot.Dia ORDER BY ot.ID ASC;");
                        while ($valores = mysqli_fetch_array($query)) {
                            echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <button class="btn btn-outline-info" type="submit" name="enviar2"> <b>Filtrar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                    <input type="hidden" name="accion" value="Filtro">
                </form>
            </div>



            <?php include('Emision/Modal-Caps-Total.php'); ?>
            <?php
            $busqueda = "";

            $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY `emision`.`Nombre` ASC";

            if (isset($_GET['enviar'])) {

                $accion1 = $_REQUEST['accion'];


                $busqueda = $nombre_dia;

                $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
            } elseif (isset($_GET['borrar'])) {
                $busqueda = "";

                $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and ID_Emision>1 ORDER BY `emision`.`Nombre` ASC";
            } else if (isset($_GET['enviar2'])) {
                $dia   = $_REQUEST['dias'];
                $accion2 = $_REQUEST['accion'];

                if ($dia == "Lunes") {

                    $busqueda = "Lunes";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Martes") {

                    $busqueda = "Martes";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Miercoles") {

                    $busqueda = "Miercoles";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Jueves") {

                    $busqueda = "Jueves";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Viernes") {

                    $busqueda = "Viernes";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Sabado") {

                    $busqueda = "Sabado";
                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Domingo") {

                    $busqueda = "Domingo";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                } else if ($dia == "Indefinido") {

                    $busqueda = "Indefinido";

                    $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
                }
            }


            ?>
        </div>

        <div class="main-container">

            <table id="example" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>N° Capitulos Vistos</th>
                        <th>N° Capitulos Totales</th>
                        <th>Dia Emision</th>
                        <th>Duracion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <?php
                $sql1 = "SELECT * FROM `emision` $where";
                $result = mysqli_query($conexion, $sql1);
                //echo $sql1;

                while ($mostrar = mysqli_fetch_array($result)) {

                ?>

                    <tr>
                        <td><?php echo $mostrar['Nombre'] ?></td>
                        <td><?php echo $mostrar['Emision'] ?></td>
                        <td><?php echo $mostrar['Capitulos'] ?></td>
                        <td><?php echo $mostrar['Totales'] ?></td>
                        <td><?php echo $mostrar['Dia'] ?></td>
                        <td><?php echo $mostrar['Duracion'] ?></td>
                        <td>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editChildresn7<?php echo $mostrar['ID_Emision']; ?>">
                                Visto
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editChildresn5<?php echo $mostrar['ID_Emision']; ?>">
                                Editar
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editChildresn6<?php echo $mostrar['ID_Emision']; ?>">
                                Eliminar
                            </button>

                        </td>
                    </tr>
                    <?php include('Emision/ModalEditar-Emision.php'); ?>
                    <?php include('Emision/Modal-Caps.php'); ?>
                    <?php include('Emision/ModalDelete-Emision.php'); ?>

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
                "order": [],
                language: {
                    processing: "Tratamiento en curso...",
                    search: "Buscar:",
                    lengthMenu: "Filtro de _MENU_ Emisiones",
                    info: "Mostrando emisiones del _START_ al _END_ de un total de _TOTAL_ emisiones",
                    infoEmpty: "No existen registros",
                    infoFiltered: "(filtrado de _MAX_ Emisiones en total)",
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

    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    document.getElementById('miBoton').addEventListener('click', function() {
        Swal.fire({
            icon: 'info',
            title: 'Consulta!',
            text: '¿Desea marcar como vistos todos los animes del dia  <?php echo $nombre_dia ?>  en Emision',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: "SI",
            cancelButtonText: "NO"
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Mensaje importante',
                    text: 'Serás redirigido en 3 segundos...',
                    icon: 'warning',
                    showConfirmButton: false, // Oculta los botones
                    timer: 3000, // Tiempo en milisegundos (5 segundos en este caso)
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    onClose: () => {
                        // Redirige a otra página después de que termine el temporizador
                        window.location.href = 'vistos.php';
                    }
                });

                // Redirige a otra página después de 5 segundos incluso si el usuario no cierra la alerta
                setTimeout(() => {
                    window.location.href = 'vistos.php';
                }, 3000);
            }
        })
    });
</script>

</html>