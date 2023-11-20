<?php

require 'bd.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'cabecera.php';
    ?>
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
    include 'menu.php';
    ?>


    <div class="col-sm">
        <form action="" method="GET">
            <button type="button" class="btn btn-info " data-toggle="modal" data-target="#NuevoAnime">
                Nuevo Anime Pendiente
            </button>
            <button type="button" class="btn btn-info " onclick="myFunction()">
                Filtrar
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
        <?php

        $where = "WHERE ID_Pendientes>1 ORDER BY `pendientes`.`Pendientes` ASC, `pendientes`.`Tipo` ASC;;";

        if (isset($_GET['borrar'])) {
            $busqueda = "";

            $where = "WHERE ID_Pendientes>1 ORDER BY `pendientes`.`Pendientes` ASC, `pendientes`.`Tipo` ASC;";
        } else if (isset($_GET['filtrar'])) {
            if (isset($_GET['tipo'])) {
                $tipo   = $_REQUEST['tipo'];

                $where = "WHERE Tipo='" . $tipo . "' AND ID_Pendientes>1 ORDER BY `pendientes`.`Pendientes` ASC, `pendientes`.`Tipo` ASC;";
            }
        } else if (isset($_GET['link'])) {

            $where = "WHERE Link='' AND Estado_link='Faltante' OR Estado_link='Erroneo/Inexistente' ORDER BY `pendientes`.`ID_Pendientes` ASC";
        }
        ?>
        <?php include('Pendientes/ModalCrear.php'); ?>
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

            $sql1 = "SELECT * FROM `pendientes`$where";
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
                <?php include('Pendientes/ModalEditar-Pendientes.php'); ?>
                <?php include('Pendientes/Modal-Caps.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('Pendientes/ModalDelete-Pendientes.php'); ?>
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
                    [4, 'asc']
                ],
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
</script>

</html>