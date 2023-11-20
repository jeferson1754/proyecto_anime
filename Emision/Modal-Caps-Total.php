<!--ventana para Update--->
<?php
include './bd.php';

?>
<div class="modal fade" id="ModalTotal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h6 class="modal-title">
          ¿Realmente desea aumentar el numero de capitulos vistos?
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <style>
        .div1 {
          text-align: center;
        }
      </style>
      <?php

      $busqueda = "";

      $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";

      if (isset($_GET['enviar'])) {


        $accion1 = $_REQUEST['accion'];
        echo "<input type='hidden' name='accion' value='  $accion1  '>";
        $link = "emision.php?enviar=&accion=HOY";
        echo "<input type='hidden' name='link' value='  $link  '>";

        $busqueda = $nombre_dia;

        $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision'";
      } else 
      if (isset($_GET['borrar'])) {
        $busqueda = "";

        $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        $accion2 = "nose";
        echo "<input type='hidden' name='accion' value='  $accion2  '>";
        $link = "emision.php";
        echo "<input type='hidden' name='link' value='  $link  '>";
      } else
      if (isset($_GET['enviar2'])) {

        $accion2 = $_REQUEST['accion'];
        echo "<input type='hidden' name='accion' value='  $accion2 '>";
        $dia   = $_REQUEST['dias'];
        $link = "emision.php?dias=$dia&enviar2=&accion=Filtro";
        echo "<input type='hidden' name='link' value='  $link  '>";
        if ($dia == "Lunes") {

          $busqueda = "Lunes";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Martes") {

          $busqueda = "Martes";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Miercoles") {

          $busqueda = "Miercoles";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Jueves") {

          $busqueda = "Jueves";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Viernes") {

          $busqueda = "Viernes";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Sabado") {

          $busqueda = "Sabado";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Domingo") {

          $busqueda = "Domingo";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        } else if ($dia == "Indefinido") {

          $busqueda = "Indefinido";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'";
        }
      } else {
        $accion2 = "nose";
        echo "<input type='hidden' name='accion' value='  $accion2  '>";
        $link = "emision.php";
        echo "<input type='hidden' name='link' value='  $link  '>";
      }

      $alumnos = "SELECT * FROM emision $where and Emision='Emision' ORDER BY `emision`.`Nombre` ASC";

      $resAlumnos = $conexion->query($alumnos);
      ?>

      <html lang="es">

      <section>
        <div class="div1">

        </div>
        <form method="post">
          <?php

          while ($registroAlumnos = $resAlumnos->fetch_array(MYSQLI_BOTH)) {
            echo '
            <input type="hidden" name="idalu[]" value="' . $registroAlumnos['ID_Emision'] . '">   
            <input type="hidden" name="nombre[' . $registroAlumnos['ID_Emision'] . ']" value="' . $registroAlumnos['Nombre'] . '">
            <input type="hidden" name="capitulos[' . $registroAlumnos['ID_Emision'] . ']" value="' . $registroAlumnos['Capitulos'] . '">
           
        
            <div class="modal-body div1" id="cont_modal">

              <h1 class="modal-title">
              ' . $registroAlumnos['Nombre'] . '
              </h1>
              <h2 class="modal-title">
                Vistos:
                ' . $registroAlumnos['Capitulos'] . '
              </h2>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">N° Capitulos Vistos:</label>
                <input type="number" id="visto" name="vistos[' . $registroAlumnos['ID_Emision'] . ']" class="form-control-number" min="0" value="0" max= ' . $registroAlumnos['Totales'] . ' required="true">
              </div>

            </div>

            <script>
            function myFunction2() {
              var x = document.getElementById("visto");
              if (x.value === "0") {
              x.value = "1";
              } else {
              x.value = "0";
            }
            }
            </script>
            ';
          }
          ?>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Emision</button>
          </div>
        </form>
        <?php

        if (isset($_POST['actualizar'])) {
          foreach ($_POST['idalu'] as $ids) {
            $editNom = mysqli_real_escape_string($conexion, $_POST['nombre'][$ids]);
            $editCaps = mysqli_real_escape_string($conexion, $_POST['capitulos'][$ids]);
            $editVis = mysqli_real_escape_string($conexion, $_POST['vistos'][$ids]);

            $actualizar = $conexion->query("UPDATE emision SET Capitulos ='" . $editCaps . "'+'" . $editVis . "' WHERE Nombre='$editNom' AND Capitulos < Totales;");
          }

          if ($actualizar == true) {

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando los Capitulos Vistos en Emision del dia ' . $busqueda . '",
                confirmButtonText: "OK"
            });
            </script>';

            /*
            echo $busqueda;
            echo "<br>";
            echo $link;
            */
          } else {
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error al Actualizar los Animes en Emision del dia ' . $busqueda . ' ",
                confirmButtonText: "OK"
            }).then(function() {
                window.location =  "' . $link . '";
            });
            </script>';
          }
        }

        ?>
    </div>
  </div>
</div>



<!---fin ventana Update --->