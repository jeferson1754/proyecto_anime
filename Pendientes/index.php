<?php

require '../bd.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css?<?php echo time(); ?>">

    <!-- CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">

    <title>Pendientes
    </title>
</head>
<style>
    .enlace {
        text-decoration: none;
        color: black;
    }

    .enlace:hover {
        color: white;
        text-decoration: none;
    }

    @media screen and (max-width: 600px) {

        div#example_info {
            font-size: 10px;
        }

    }
</style>


<body>

    <?php
    include '../menu.php';
    ?>


    <div class="col-sm">
        <form action="" method="GET">
            <button type="button" class="btn btn-info " data-toggle="modal" data-target="#NuevoAnime">
                Nuevo Anime Pendiente
            </button>
            <button type="button" class="btn btn-info " onclick="myFunction()">
                Filtrar
            </button>
            <button type="button" class="btn btn-info " onclick="myFunction2()">
                Busqueda
            </button>
            <button type="submit" name="link" class="btn btn-info ">
                Sin Link
            </button>
        </form>
        <div class="class-control" id="myDIV" style="display:none;">
            <form action="" method="GET">
                <select name="tipo" class="form-control" style="width:auto;">
                    <option value="" required>Seleccione:</option>
                    <?php
                    $query = $conexion->query("SELECT DISTINCT Tipo FROM `pendientes` WHERE ID_Pendientes>1;;");
                    while ($valores = mysqli_fetch_array($query)) {
                        echo '<option value="' . $valores['Tipo'] . '">' . $valores['Tipo'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <button class="btn btn-outline-info" type="submit" name="filtrar"> <b>Filtrar </b> </button>
                <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                <input type="hidden" name="accion" value="Filtro">
            </form>
        </div>
        <div class="class-control" id="myDIV1" style="display:none;">
            <form action="" method="GET">
                <input class="form-control" type="text" name="busqueda_pendientes" style="width:auto;" placeholder="Nombre de Anime">

                <button class="btn btn-outline-info" type="submit" name="buscar"> <b>Buscar </b> </button>
                <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
            </form>
        </div>
        <?php

        $where = "AND ID_Pendientes>1  ORDER BY rn, Tipo, Pendientes ASC;";

        if (isset($_GET['borrar'])) {
            $busqueda = "";

            $where = "AND ID_Pendientes>1  ORDER BY rn, Tipo, Pendientes ASC;";
        } else if (isset($_GET['filtrar'])) {
            if (isset($_GET['tipo'])) {
                $tipo   = $_REQUEST['tipo'];

                $where = "AND Tipo='" . $tipo . "' AND ID_Pendientes>1 ORDER BY rn, Tipo, Pendientes ASC;";
            }
        } else if (isset($_GET['link'])) {

            $where = "AND Link='' AND Estado_link='Faltante' OR Estado_link='Erroneo/Inexistente'  ORDER BY rn, Tipo, Pendientes ASC;";
        } else if (isset($_GET['buscar'])) {

            if (isset($_GET['busqueda_pendientes'])) {

                $busqueda   = $_REQUEST['busqueda_pendientes'];

                $where = "AND Nombre LIKE '%$busqueda%'  ORDER BY rn, Tipo, Pendientes ASC;";
            }
        }
        ?>
        <?php include('ModalCrear.php'); ?>
    </div>

    <div class="main-container">



        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>N° Vistos</th>
                    <th>N° Totales</th>
                    <th>N° Pendientes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <?php

            $sql1 = "SELECT *, ROW_NUMBER() OVER (PARTITION BY Tipo ORDER BY Pendientes ASC, ID_Pendientes ASC) AS rn FROM `pendientes` WHERE Tipo IN ('Pelicula', 'Ova y Otros', 'Anime')$where";
            //echo $sql1;
            $result = mysqli_query($conexion, $sql1);


            while ($mostrar = mysqli_fetch_array($result)) {

            ?>

                <tr>
                    <td class="max"><a href="<?php echo $mostrar['Link'] ?>" title="<?php echo $mostrar['Estado_Link'] ?>" class="enlace" target="_blank"><?php echo $mostrar['Nombre'] ?></a></td>
                    <td><?php echo $mostrar['Tipo'] ?></td>
                    <td><?php echo $mostrar['Vistos'] ?></td>
                    <td><?php echo $mostrar['Total'] ?></td>
                    <td><?php echo $mostrar['Pendientes'] ?></td>
                    <td>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editChildresn11<?php echo $mostrar['ID_Pendientes']; ?>">
                            Visto
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editChildresn10<?php echo $mostrar['ID_Pendientes']; ?>">
                            Editar
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editChildresn9<?php echo $mostrar['ID_Pendientes']; ?>">
                            Eliminar
                        </button>

                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar-Pendientes.php'); ?>
                <?php include('Modal-Caps.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ModalDelete-Pendientes.php'); ?>
            <?php
            }
            ?>
        </table>
    </div>
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                    "order": [],
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

        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function myFunction2() {
            var x = document.getElementById("myDIV1");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</body>

</html>