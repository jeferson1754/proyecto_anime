<div class="modal fade" id="editpeli3<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

      <link rel="stylesheet" href="checkbox.css">
      <form method="POST" action="OP/recib_Update-OP.php">
        <?php
        include('./OP/regreso-modal.php');
        ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="anime" value="<?php echo $mostrar['ID_Anime']; ?>">
        <input type="hidden" name="op" value="<?php echo $mostrar['Opening']; ?>">

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre:</label><br>
            <input type="hidden" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>">
            <input type="text" name="nombre2" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" disabled>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Cancion:</label>
            <input type="text" name="cancion" class="form-control" value="<?php echo $mostrar['Cancion']; ?>">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Link:</label>
            <input type="text" name="enlace" class="form-control" value="<?php echo $mostrar['Link']; ?>">
          </div>


          <div class="form-group">
            <label for="iframe" class="col-form-label">Link Iframe:</label>
            <input type="text" name="iframe" class="form-control" value="<?php echo $mostrar['Link_Iframe']; ?>">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Autor:</label>
            <input type="text" name="autor" id="autor" list="autores" value="<?php echo $mostrar['Autor']; ?>" class="form-control">

            <datalist id="autores">
              <?php
              $mangas = $conexion->query("SELECT * FROM `autor`;");

              foreach ($mangas as $manga) {
                echo "<option value='" . $manga['Autor'] . "'></option>";
              }

              ?>
            </datalist>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado del Link:</label>
            <select name="estado_link" class="form-control" required>
              <option value="<?php echo $mostrar['Estado_Link']; ?>"><?php echo $mostrar['Estado_Link']; ?></option>
              <?php
              $query = $conexion->query("SELECT Estado FROM `estado_link`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Año:</label>
            <input type="number" name="ano" class="form-control" value="<?php echo $mostrar['Ano']; ?>">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Temporada:</label>
            <select name="temp" class="form-control">
              <option value="<?php echo $mostrar['Temporada']; ?>"><?php echo $mostrar['Temporada']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `temporada`.`ID` ASC;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['ID'] . '">' . $valores['Meses'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control">
              <option value="<?php echo $mostrar['Estado']; ?>"><?php echo $mostrar['Estado']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `estado_op`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Nombre'] . '">' . $valores['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Mix:</label>
            <select name="mix" class="form-control">
              <option value="<?php echo $mostrar['Mix']; ?>"><?php echo $mostrar['Mix']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `mix`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['ID'] . '">' . $valores['ID'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="todo">
            <label class="container">

              <?php
              $etiqueta = $mostrar['mostrar'];
              if ($etiqueta == "NO") {
                echo "<input type='checkbox' name='ocultar' value='NO' checked>";
                echo "<span class='text'>Ocultar:</span>";
                echo "<div class='checkmark'></div>";
              } else {
                echo "<input type='checkbox' name='ocultar' value='SI' unchecked>";
                echo "<span class='text'>Ocultar:</span>";
                echo "<div class='checkmark'></div>";
              }
              ?>
            </label>
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