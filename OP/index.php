<?php

require '../bd.php';

// Establecer la zona horaria predeterminada y el año en español
setlocale(LC_ALL, "es_ES");
$año = date("Y");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/checkbox.css">

    <!-- CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">
    <title>Openings
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

    .op {
        width: auto;
    }
</style>

<body>
    <?php
    include '../menu.php';
    ?>

    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->
        <form action="" method="GET">
            <button type="button" class="btn btn-info " onclick="myFunction()">
                Filtrar por Mix
            </button>
            <button type="button" class="btn btn-info " onclick="myFunction2()">
                Busqueda por Anime
            </button>
            <button type="button" class="btn btn-info " onclick="myFunction3()">
                Busqueda por Cancion
            </button>
            <button type="submit" name="nombre" class="btn btn-info ">
                Sin Nombre
            </button>
            <button type="submit" name="link" class="btn btn-info ">
                Link Faltantes
            </button>
            <button type="button" class="btn btn-info " data-toggle="modal" data-target="#NuevoMix">
                Nuevo Mix
            </button>
        </form>
        <div class="col-sm">
            <!--- Formulario para registrar Cliente --->
            <div class="class-control" id="myDIV" style="display:none;">
                <form action="" method="GET">
                    <select name="estado" class="form-control" style="width:auto;">
                        <option value="" required>Seleccione:</option>
                        <?php
                        $query = $conexion->query("SELECT * FROM `mix`ORDER BY `mix`.`ID`  DESC;");
                        while ($valores = mysqli_fetch_array($query)) {
                            echo '<option value="' . $valores['ID'] . '">' . $valores['ID'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" name="accion" value="Filtro">
                    <br>

                    <button class="btn btn-outline-info" type="submit" name="filtrar"> <b>Filtrar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>
            <div class="class-control" id="myDIV1" style="display:none;">
                <form action="" method="GET">
                    <input class="form-control" type="text" name="busqueda_op" style="width:auto;" placeholder="Nombre de Anime">

                    <button class="btn btn-outline-info" type="submit" name="buscar"> <b>Buscar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>

            <div class="class-control" id="myDIV2" style="display:none;">
                <form action="" method="GET">
                    <input class="form-control" type="text" name="busqueda_cancion" style="width:auto;" placeholder="Nombre de Cancion">

                    <button class="btn btn-outline-info" type="submit" name="buscar1"> <b>Buscar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>

            <?php

            $where = "where op.mostrar='SI' ORDER BY `op`.`ID` DESC limit 10 ";


            if (isset($_GET['borrar'])) {
                $busqueda = "";

                $where = "where op.mostrar='SI' ORDER BY `op`.`ID` DESC limit 10";
            } else if (isset($_GET['filtrar'])) {
                if (isset($_GET['estado'])) {
                    $estado   = $_REQUEST['estado'];
                    $where = "WHERE op.Mix LIKE'%" . $estado . "%' AND op.mostrar='SI' ORDER BY `op`.`ID` DESC ";
                }
            } else if (isset($_GET['link'])) {

                $where = "WHERE Link='' OR Estado_link!='Correcto' OR Link_Iframe='' ORDER BY `op`.`ID` DESC limit 10";
            } else if (isset($_GET['nombre'])) {

                $where = "WHERE Cancion='' OR Autor='' limit 10";
            } else if (isset($_GET['buscar'])) {

                if (isset($_GET['busqueda_op'])) {

                    $busqueda   = $_REQUEST['busqueda_op'];

                    $where = "WHERE op.Nombre LIKE '%$busqueda%' ORDER BY `op`.`ID` DESC limit 10";
                }
            } else if (isset($_GET['buscar1'])) {

                if (isset($_GET['busqueda_cancion'])) {

                    $busqueda   = $_REQUEST['busqueda_cancion'];

                    $where = "WHERE op.Cancion LIKE '%$busqueda%' ORDER BY `op`.`ID` DESC limit 10";
                }
            }


            ?>

            <?php include('ModalMix.php');  ?>
        </div>
    </div>

    <div class="main-container">

        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Opening</th>
                    <th>Cancion</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Temporada</th>
                    <th>Estado</th>
                    <th>N° Mix</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <?php

            $sql = "SELECT op.ID,op.Nombre,op.ID_Anime,op.Opening,op.Cancion,autor.Autor,op.Ano,temporada.Temporada,op.Estado,op.Link,op.Link_Iframe,op.Mix,op.Estado_Link,op.mostrar FROM `op` JOIN temporada ON op.Temporada=temporada.ID JOIN autor ON op.ID_Autor=autor.ID $where";
            //echo $sql;

            $result = mysqli_query($conexion, $sql);


            while ($mostrar = mysqli_fetch_array($result)) {
                $iden = $mostrar['ID_Anime'];
                $name = $mostrar['Nombre'];
                $name2 = substr($name, 0, 8);
                //echo $name2;
                //echo "<br>";


            ?>


                <tr>
                    <td><?php echo $mostrar['ID'] ?></td>
                    <td class="op"><?php echo $mostrar['Nombre'] ?></td>
                    <td>OP <?php echo $mostrar['Opening'] ?></td>
                    <td><a href="<?php echo $mostrar['Link'] ?>" class="enlace" title="<?php echo $mostrar['Estado_Link'] ?>" target="_blank"><?php echo $mostrar['Cancion'] ?></a></td>
                    <td><?php echo $mostrar['Autor'] ?></td>
                    <td><?php echo $mostrar['Ano'] ?></td>
                    <td><?php echo $mostrar['Temporada'] ?></td>
                    <td><?php echo $mostrar['Estado'] ?></td>
                    <td><?php echo $mostrar['Mix'] ?></td>
                    <td>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#info<?php echo $mostrar['ID']; ?>">
                            Info
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editpeli3<?php echo $mostrar['ID']; ?>">
                            Editar
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editpeli2<?php echo $mostrar['ID']; ?>">
                            Eliminar
                        </button>

                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar-OP.php'); ?>
                <?php include('ModalInfo-OP.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ModalDelete-OP.php'); ?>
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
                        lengthMenu: "Filtro de _MENU_ Openings",
                        info: "Mostrando op del _START_ al _END_ de un total de _TOTAL_ openings",
                        infoEmpty: "No existen registros",
                        infoFiltered: "(filtrado de _MAX_ openings en total)",
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
            var x = document.getElementById("myDIV1");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function myFunction3() {
            var x = document.getElementById("myDIV2");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</body>



</html>