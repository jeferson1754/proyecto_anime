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
    <title>Endings
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
                        $query = $conexion->query("SELECT * FROM `mix_ed`ORDER BY `mix_ed`.`ID`  DESC;");
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

            $where = "where ed.mostrar='SI' ORDER BY `ed`.`ID` DESC limit 10 ";


            if (isset($_GET['borrar'])) {

                $where = "where ed.mostrar='SI' ORDER BY `ed`.`ID` DESC limit 10 ";
            } else  if (isset($_GET['filtrar'])) {

                if (isset($_GET['estado'])) {
                    $estado   = $_REQUEST['estado'];

                    $where = "WHERE ed.Mix = $estado AND ed.mostrar='SI' ORDER BY `ed`.`ID` DESC";
                }
            } else if (isset($_GET['link'])) {

                $where = "WHERE Link='' OR Estado_link!='Correcto' ORDER BY `ed`.`ID` DESC limit 10";
            } else if (isset($_GET['nombre'])) {

                $where = "WHERE Cancion='' ORDER BY `ed`.`ID` DESC limit 10";
            } else if (isset($_GET['buscar'])) {

                if (isset($_GET['busqueda'])) {

                    $busqueda   = $_REQUEST['busqueda'];

                    $where = "WHERE ed.Nombre LIKE '%$busqueda%' ORDER BY `ed`.`ID` DESC limit 10";
                }
            } else if (isset($_GET['buscar1'])) {

                if (isset($_GET['cancion'])) {

                    $busqueda   = $_REQUEST['cancion'];

                    $where = "WHERE ed.Cancion LIKE '%$busqueda%' ORDER BY `ed`.`ID` DESC limit 10";
                }
            }




            ?>
        </div>

        <?php include('ModalMix-ED.php');  ?>
    </div>

    <div class="main-container">

        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ending</th>
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

            $sql = "SELECT ed.ID,ed.Nombre,ed.ID_Anime,ed.Ending,ed.Cancion,autor.Autor,ed.Ano,temporada.Temporada,ed.Estado,ed.Link,ed.Link_Iframe,ed.Mix,ed.Estado_Link,ed.mostrar FROM `ed` JOIN temporada ON ed.Temporada=temporada.ID JOIN autor ON ed.ID_Autor=autor.ID $where ";
            $result = mysqli_query($conexion, $sql);
            //echo $sql;

            while ($mostrar = mysqli_fetch_array($result)) {
                $iden = $mostrar['ID_Anime'];
            ?>


                <tr>
                    <td><?php echo $mostrar['ID'] ?></td>
                    <td class="td"><?php echo $mostrar['Nombre'] ?></td>
                    <td>ED <?php echo $mostrar['Ending'] ?></td>
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
                <?php include('ED/ModalEditar-ED.php'); ?>
                <?php include('ED/ModalInfo-ED.php'); ?>
                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ED/ModalDelete-ED.php'); ?>
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
                    lengthMenu: "Filtro de _MENU_ Endings",
                    info: "Mostrando ed del _START_ al _END_ de un total de _TOTAL_ endings",
                    infoEmpty: "No existen registros",
                    infoFiltered: "(filtrado de _MAX_ endings en total)",
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