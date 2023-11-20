<!--ventana para Update--->
<div class="modal fade" id="NuevoHorario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Nuevo Anime
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form name="form-data" action="recib_Horario.php" method="POST">
        <div class="modal-body" id="cont_modal">
          <?php
          if (isset($_GET['borrar'])) {
            $link = "./horarios.php?anis=&borrar=";
            echo "<input type='hidden' name='link' value='$link'>";
          } else if (isset($_GET['filtrar'])) {
            $link = "./horarios.php?anis=$estado&filtrar=";
            echo "<input type='hidden' name='link' value='$link'>";
          } else {
            $link = "./horarios.php";
            echo "<input type='hidden' name='link' value='$link'>";
          }
          ?>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre del Anime:</label>
            <select name="nombre" class="form-control" required>
              <option value="">Seleccione un Anime</option>
              <?php
              $query = $conexion->query("SELECT id,Anime FROM `anime` ORDER BY `anime`.`Anime` ASC");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Anime'] . '">' . $valores['Anime'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Temporada Emitida:</label>
            <input type="text" name="temps" class="form-control">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Dia de Emision:</label>

            <select name="dias" class="form-control" required>
              <option value="<?php echo $day ?>"><?php echo $day ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `dias` where Dia!='$day'");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
              }
              ?>
            </select>

          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Duracion:</label>

            <select name="duracion" class="form-control" required>
              <option value="00:24:00">00:24:00</option>
              <?php
              $query = $conexion->query("SELECT * FROM `duracion` where Duracion!='00:24:00' ORDER BY `duracion`.`Duracion` DESC");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Duracion'] . '">' . $valores['Duracion'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Año y Temporada:</label>

            <select name="temporada" class="form-control" required>
              <?php
              if (isset($_GET['anis'])) {
                $estado   = $_REQUEST['anis'];
                $sql1 = $conexion->query("SELECT * from num_horario where Num='$estado';");
                while ($consulta = mysqli_fetch_array($sql1)) {


              ?>
                  <option value="<?php echo $estado ?>"><?php echo $consulta['Ano'] . '-' . $consulta['Temporada'] ?></option>

              <?php

                  $query = $conexion->query("SELECT * FROM `num_horario` where Num!='$estado'  ORDER BY `num_horario`.`Num` DESC;");
                  while ($valores = mysqli_fetch_array($query)) {
                    echo '<option value="' . $valores['Num'] . '">' . $valores['Ano'] . '-' . $valores['Temporada'] . '</option>';
                  }
                }
              } else {
                $query = $conexion->query("SELECT * FROM `num_horario` where Num!='$estado'  ORDER BY `num_horario`.`Num` DESC;");
                while ($valores = mysqli_fetch_array($query)) {
                  echo '<option value="' . $valores['Num'] . '">' . $valores['Ano'] . '-' . $valores['Temporada'] . '</option>';
                }
              }

              ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnEnviar">
            Registrar Anime
          </button>
        </div>
    </div>
    </form>

  </div>
</div>
</div>
<!---fin ventana Update --->