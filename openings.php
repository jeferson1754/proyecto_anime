<?php

require 'bd.php';

// Establecer la zona horaria predeterminada y el año en español
setlocale(LC_ALL, "es_ES");
$año = date("Y");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'cabecera.php';
    ?>
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
    include 'menu.php';
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
                    <input class="form-control" type="text" name="busqueda" style="width:auto;" placeholder="Nombre de Anime">

                    <button class="btn btn-outline-info" type="submit" name="buscar"> <b>Buscar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>

            <div class="class-control" id="myDIV2" style="display:none;">
                <form action="" method="GET">
                    <input class="form-control" type="text" name="cancion" style="width:auto;" placeholder="Nombre de Cancion">

                    <button class="btn btn-outline-info" type="submit" name="buscar1"> <b>Buscar </b> </button>
                    <button class="btn btn-outline-info" type="submit" name="borrar"> <b>Borrar </b> </button>
                </form>
            </div>

            <?php

            $where = "where op.mostrar='SI' ORDER BY `op`.`ID` DESC limit 10 ";


            if (isset($_GET['borrar'])) {
                $busqueda = "";

                $where = "where op.mostrar='SI' ORDER BY `op`.`ID` DESC limit 10 ";
            } else if (isset($_GET['filtrar'])) {
                if (isset($_GET['estado'])) {
                    $estado   = $_REQUEST['estado'];
                    $where = "WHERE op.Mix LIKE'%" . $estado . "%' AND op.mostrar='SI' ORDER BY `op`.`ID` DESC";
                }
            } else if (isset($_GET['link'])) {

                $where = "WHERE Link='' OR Estado_link!='Correcto' ORDER BY `op`.`ID` DESC";
            } else if (isset($_GET['nombre'])) {

                $where = "WHERE Cancion='' limit 10";
            } else if (isset($_GET['buscar'])) {

                if (isset($_GET['busqueda'])) {

                    $busqueda   = $_REQUEST['busqueda'];

                    $where = "WHERE op.Nombre LIKE '%$busqueda%' ORDER BY `op`.`ID` DESC limit 10";
                }
            } else if (isset($_GET['buscar1'])) {

                if (isset($_GET['cancion'])) {

                    $busqueda   = $_REQUEST['cancion'];

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

            //$sql = "SELECT a.id,a.Anime,a.Temporadas,a.Peliculas,a.Spin_Off,e.Estado,a.Año,t.Temporada FROM anime as a INNER JOIN Estado as e ON a.Estado = e.id INNER join Temporada as t ON a.Temporada=t.ID ORDER by a.id;";

            $sql = "  SELECT op.ID,op.Nombre,op.ID_Anime,op.Opening,op.Cancion,op.Autor,op.Ano,temporada.Temporada,op.Estado,op.Link,op.Mix,op.Estado_Link,op.mostrar FROM `op`  JOIN temporada ON   op.Temporada=temporada.ID $where";
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
                <?php include('OP/ModalEditar-OP.php'); ?>
                <?php include('OP/ModalInfo-OP.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('OP/ModalDelete-OP.php'); ?>
            <?php
            }
            ?>
        </table>
    </div>
</body>
<?php
include 'scripts.php';
?>
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

</html>