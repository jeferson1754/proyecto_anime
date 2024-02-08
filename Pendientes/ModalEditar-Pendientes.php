<div class="modal fade" id="editChildresn10<?php echo $mostrar['ID_Pendientes']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Actualizar Información
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="recib_Update-Pendientes.php">
        <?php
        include('regreso-modal.php');
        ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Pendientes']; ?>">
        <input type="hidden" name="name" value="<?php echo $mostrar['Nombre']; ?>">

        <div class="modal-body" id="cont_modal">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" disabled="true" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Tipo:</label>
            <select name="tipo" class="form-control" required>
              <option value="<?php echo $mostrar['Tipo']; ?>"><?php echo $mostrar['Tipo']; ?></option>
              <?php
              $query = $conexion->query("SELECT Nombre FROM `tipo`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Nombre'] . '">' . $valores['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Capitulos Vistos:</label>
            <input type="number" name="caps" min="0" class="form-control" value="<?php echo $mostrar['Vistos']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Total Capitulos:</label>
            <input type="number" name="total" min="1" class="form-control" value="<?php echo $mostrar['Total']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Link:</label>
            <input type="link" name="enlace" class="form-control" value="<?php echo $mostrar['Link']; ?>">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado del Link:</label>
            <select name="estado" class="form-control" required>
              <option value="<?php echo $mostrar['Estado_Link']; ?>"><?php echo $mostrar['Estado_Link']; ?></option>
              <?php
              $query = $conexion->query("SELECT Estado FROM `estado_link`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
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