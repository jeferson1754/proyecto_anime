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
    <link rel="stylesheet" href="../css/checkbox.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <!-- CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">

    <title>Calificaciones
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
</style>

<body>
    <?php
    include '../menu.php';
    ?>

    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->
        <form action="" method="GET">
            <button type="submit" name="sin_calificar" class="btn btn-info ">
                Sin Calificar
            </button>
            <button type="submit" name="borrar" class="btn btn-info ">
                Todos
            </button>
        </form>


        <?php

        $where = " ";


        if (isset($_GET['borrar'])) {

            $where = " ";
        } else  if (isset($_GET['sin_calificar'])) {

            $where = "where calificaciones.Promedio=0.0";
        }

        ?>


    </div>

    <div class="main-container">

        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Historia</th>
                    <th>Musica</th>
                    <th>Animacion</th>
                    <th>Desarrollo</th>
                    <th>Final</th>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <?php

            $sql = "SELECT anime.Anime, calificaciones.* FROM calificaciones INNER JOIN anime ON anime.id = calificaciones.ID_Anime $where ORDER BY calificaciones.ID DESC; ";
            $result = mysqli_query($conexion, $sql);
            //echo $sql;

            while ($mostrar = mysqli_fetch_array($result)) {
                $iden = $mostrar['ID_Anime'];
                $id_Registros = $mostrar['ID'];
                $variable_nombre = $mostrar['Anime'];
            ?>


                <tr>
                    <td><?php echo $mostrar['ID'] ?></td>
                    <td class="td"><?php echo $mostrar['Anime'] ?></td>
                    <td><?php echo $mostrar['Calificacion_1'] ?></td>
                    <td><?php echo $mostrar['Calificacion_2'] ?></td>
                    <td><?php echo $mostrar['Calificacion_3'] ?></td>
                    <td><?php echo $mostrar['Calificacion_4'] ?></td>
                    <td><?php echo $mostrar['Calificacion_5'] ?></td>
                    <td><?php echo $mostrar['Promedio'] ?></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCalif<?php echo $mostrar['ID']; ?>">
                            Editar Imagen
                        </button>

                        <div class="btn btn-secondary">
                            <a href="../editar_stars.php?id=<?php echo $iden; ?>&nombre=<?php echo $variable_nombre; ?>" class="link">
                                Cambiar Calificacion
                            </a>
                        </div>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $mostrar['ID']; ?>">
                            Eliminar
                        </button>

                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar.php'); ?>
                <?php include('ModalDelete.php'); ?>
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
                        lengthMenu: "Filtro de _MENU_ Calificaciones",
                        info: "Mostrando calificiones del _START_ al _END_ de un total de _TOTAL_ calificiones",
                        infoEmpty: "No existen registros",
                        infoFiltered: "(filtrado de _MAX_ calificiones en total)",
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
    </script>
</body>



</html>