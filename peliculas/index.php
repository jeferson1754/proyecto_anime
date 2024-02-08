<?php

require '../bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css?<?php echo time(); ?>">

    <!-- CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">

    <title>Peliculas
    </title>
</head>

<body>

    <?php
    include '../menu.php';
    ?>

    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->
        <form action="" method="GET">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editpeli1">
                Nueva Pelicula
            </button>
            <button type="submit" name="pendientes" class="btn btn-info ">
                Pendientes
            </button>
        </form>
        <?php include('ModalCrear.php');  ?>

    </div>

    <?php

    $where = " ";

    if (isset($_GET['pendientes'])) {

        $where = "WHERE Estado='Pendiente'";
    } else {
        $where = "";
    }
    ?>

    <div class="main-container">

        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Estado</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <?php
            $sql1 = "SELECT * FROM `peliculas` $where";

            $result = mysqli_query($conexion, $sql1);
            //echo $sql1;

            while ($mostrar = mysqli_fetch_array($result)) {

            ?>

                <tr>
                    <td><?php echo $mostrar['ID'] ?></td>
                    <td><?php echo $mostrar['Nombre'] ?></td>
                    <td><?php echo $mostrar['Ano'] ?></td>
                    <td><?php echo $mostrar['Estado'] ?></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editpeli3<?php echo $mostrar['ID']; ?>">
                            Editar
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editpeli2<?php echo $mostrar['ID']; ?>">
                            Eliminar
                        </button>

                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar-Peli.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ModalDelete-Peli.php'); ?>
            <?php
            }
            ?>
        </table>
    </div>

    <!-- jQuery    -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                    language: {
                        processing: "Tratamiento en curso...",
                        search: "Buscar:",
                        lengthMenu: "Filtro de _MENU_ Pendientes",
                        info: "Mostrando pendientes del _START_ al _END_ de un total de _TOTAL_ pendientes",
                        infoEmpty: "No existen registros",
                        infoFiltered: "(filtrado de _MAX_ pendientes en total)",
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

    </script>
</body>

</html>