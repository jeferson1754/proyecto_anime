<!--ventana para Update--->
<div class="modal fade" id="NuevoAnime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

      <link rel="stylesheet" href="checkbox.css">

      <form name="form-data" action="recibCliente.php" method="POST">
        <?php
        include('regreso-modal.php');
        ?>
        <div class="modal-body" id="cont_modal">
          <input type="hidden" min="1" name="id" value="<?php echo $ani1 ?>" class="form-control">
          <input type="hidden" name="tempo" value="<?php echo $tempo ?>" class="form-control">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre del Anime:</label>
            <input type="text" name="anime" class="form-control" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control" required>
              <option value="">Seleccione:</option>
              <?php
              $query = $conexion->query("SELECT ID,Estado FROM `estado`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Año:</label>
            <input type="number" name="fecha" min="1900" max="<?php echo $año ?>" class="form-control" value="<?php echo $año ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Temporada:</label>
            <select name="temp" class="form-control" required>
              <option value="<?php echo $id_tempo ?>"><?php echo $tempo ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `temporada`.`ID` ASC;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['ID'] . '">' . $valores['Meses'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Dia de Emision:</label>
            <select name="dias" class="form-control" required>
              <option value="<?php echo $day ?>"><?php echo $day ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `dias`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="todo">
            <label class="container">
              <input type="checkbox" name="OP" value="SI" unchecked>
              <span class="text">OP</span>
              <div class="checkmark"></div>
            </label>

            <label class="container">
              <input type="checkbox" name="ED" value="SI" unchecked>
              <span class="text">ED</span>
              <div class="checkmark"></div>
            </label>
          </div>
          <!---->


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