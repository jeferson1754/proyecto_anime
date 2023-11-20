<?php

require 'bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'cabecera.php';
    ?>
    <title>Peliculas
    </title>
</head>

<body>

    <?php
    include 'menu.php';
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
        <?php include('peliculas/ModalCrear.php');  ?>

    </div>

    <?php

    $where = " hola";


    if (isset($_GET['pendientes'])) {

        $where = "WHERE Estado='Pendiente'";
    } else {
        $where = "";
    }
    ?>

    <div class="main-container">

        <table>
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
                <?php include('peliculas/ModalEditar-Peli.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('peliculas/ModalDelete-Peli.php'); ?>
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

</html>