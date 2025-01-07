<div class="modal fade" id="ModalTotal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit me-2"></i>
          Actualizar Capítulos Vistos
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="post">
        <div class="modal-body p-4">
          <?php

          $busqueda = "";

          $where = "WHERE emision.dia LIKE'%" . $busqueda . "%'and Emision='Emision' and ID_Emision>1 ORDER BY `emision`.`Nombre` ASC";

          if (isset($_GET['enviar'])) {


            $accion1 = $_REQUEST['accion'];
            echo "<input type='hidden' name='accion' value='  $accion1  '>";
            $link = "/Anime/Emision/?enviar=&accion=HOY";
            echo "<input type='hidden' name='link' value='  $link  '>";

            $busqueda = $day;

            $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
          } else
if (isset($_GET['enviar2'])) {

            $accion2 = $_REQUEST['accion'];
            $dia   = $_REQUEST['dias'];

            echo "<input type='hidden' name='accion' value='  $accion2 '>";
            $link = "/Anime/Emision/?dias=$dia&enviar2=&accion=Filtro";
            echo "<input type='hidden' name='link' value='  $link  '>";

            $busqueda = $dia;

            $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and Emision='Emision' and ID_Emision>1 ORDER BY CASE WHEN Posicion = 0 THEN 2 ELSE 1 END, Posicion;";
          } else {
            $busqueda = "";
            $where = "WHERE emision.dia LIKE'%" . $busqueda . "%' and ID_Emision>1 ORDER BY `emision`.`Nombre` ASC";

            $accion2 = "nose";
            echo "<input type='hidden' name='accion' value='  $accion2  '>";
            $link = "/Anime/Emision/?borrar=&accion=HOY";
            echo "<input type='hidden' name='link' value='  $link  '>";
          }

          $alumnos = "SELECT * FROM emision $where";

          $resAlumnos = $conexion->query($alumnos);
          while ($registroAlumnos = $resAlumnos->fetch_array(MYSQLI_BOTH)) {
            echo '
            <input type="hidden" name="idalu[]" value="' . $registroAlumnos['ID_Emision'] . '">   
            <input type="hidden" name="nombre[' . $registroAlumnos['ID_Emision'] . ']" value="' . $registroAlumnos['Nombre'] . '">
            <input type="hidden" name="capitulos[' . $registroAlumnos['ID_Emision'] . ']" value="' . $registroAlumnos['Capitulos'] . '">
           
            <div class="anime-item">
              <h3 class="anime-title">' . $registroAlumnos['Nombre'] . '</h3>
              <div class="episodes-watched">
                <span class="badge bg-success">
                  <i class="fas fa-check me-1"></i>
                  ' . $registroAlumnos['Capitulos'] . ' capítulos vistos
                </span>
                <span class="badge bg-info ms-2">
                  <i class="fas fa-tv me-1"></i>
                  ' . $registroAlumnos['Totales'] . ' capítulos totales
                </span>
              </div>
              
              <div class="form-group text-center">
                <label for="visto-' . $registroAlumnos['ID_Emision'] . '" class="form-label">
                  Agregar capítulos vistos
                </label>
                <input 
                  type="number" 
                  id="visto-' . $registroAlumnos['ID_Emision'] . '" 
                  name="vistos[' . $registroAlumnos['ID_Emision'] . ']" 
                  class="form-control-number" 
                  min="0" 
                  value="0" 
                  max="' . $registroAlumnos['Totales'] . '" 
                  required="true"
                >
              </div>
            </div>
            ';
          }
          ?>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times me-2"></i>
            Cancelar
          </button>
          <button type="submit" name="actualizar" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>
            Guardar Cambios
          </button>
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