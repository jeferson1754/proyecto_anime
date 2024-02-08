<div class="modal fade" id="editHorarios<?php echo $mostrar['Nombre']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Actualizar Horario
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="Update_Horario.php">
        <?php
        if (isset($_GET['borrar'])) {
          $link = "horarios.php?anis=&borrar=";
          echo "<input type='hidden' name='link' value='$link'>";
        } else if (isset($_GET['filtrar'])) {
          $link = "horarios.php?anis=$estado&filtrar=";
          echo "<input type='hidden' name='link' value='$link'>";
        } else {
          $link = "horarios.php";
          echo "<input type='hidden' name='link' value='$link'>";
        }
        ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Anime:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Dia de Emision:</label>

            <select name="dias" class="form-control" required>
              <option value="">Indefinido</option>
              <?php
              $query = $conexion->query("SELECT * FROM `dias` where Dia!='Indefinido'");
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->