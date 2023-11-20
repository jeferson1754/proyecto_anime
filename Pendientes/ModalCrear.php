<!--ventana para Update--->
<div class="modal fade" id="NuevoAnime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Nuevo Anime Pendiente
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="Pendientes/recibCliente.php">
        <?php
        include('./Pendientes/regreso-modal.php');
        ?>
        <div class="modal-body" id="cont_modal">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Tipo:</label>
            <select name="tipo" class="form-control" required>
              <option value="">Seleccione:</option>
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
            <input type="number" name="caps" min="0" value="0" class="form-control" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Total Capitulos:</label>
            <input type="number" name="total" min="1" class="form-control" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Link:</label>
            <input type="url" name="enlace" class="form-control">
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