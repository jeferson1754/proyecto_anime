<div class="modal fade" id="editChildresn5<?php echo $mostrar['ID_Emision']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


      <form method="POST" action="recib_Update-Emision.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Emision']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
        <?php
        include('regreso-modal.php');

        $idRegistros = $mostrar['ID_Emision'];

        $estado = $mostrar['Emision'];

        $sql2 = $conexion->query("SELECT * FROM `anime` where id_Emision='$idRegistros';");

        while ($valores = mysqli_fetch_array($sql2)) {
          $IdAnime = $valores["id"];
        }

        //echo $IdAnime;

        ?>

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" disabled="true" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control">
              <option value="<?php echo $mostrar['Emision']; ?>"><?php echo $mostrar['Emision']; ?></option>
              <?php
              $query = $conexion->query("SELECT Estado FROM `estado` where Estado !='$estado' AND Estado !='Finalizado';");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Posicion:</label>
            <?php
            $query = $conexion->query("SELECT COUNT(Posicion) as conteo FROM `emision` WHERE Dia='$mostrar[Dia]' and Emision='Emision';");
            while ($valores = mysqli_fetch_array($query)) {
              $conteo = $valores['conteo'];
            }
            ?>
            <input type="number" name="posicion" class="form-control" min="0" max="<?php echo $conteo; ?>" value="<?php echo $mostrar['Posicion']; ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Capitulos Vistos:</label>
            <input type="number" name="caps" class="form-control" min="1" max="<?php echo $mostrar['Totales']; ?>" value="<?php echo $mostrar['Capitulos']; ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Capitulos Faltantes:</label>
            <input type="number" name="faltantes" class="form-control" min="1" max="<?php echo $total_faltantes ?>" value="<?php echo $faltantes ?>" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Total Capitulos:</label>
            <input type="number" name="total" class="form-control" value="<?php echo $mostrar['Totales']; ?>" required="true">
          </div>

          <style>
            .inline-input {
              display: inline-block;
              width: 49%;
            }
          </style>
          <?php
          $op = $conexion->query("SELECT COUNT(*) total FROM `op` where ID_Anime='$IdAnime';");

          while ($valores = mysqli_fetch_array($op)) {
            $op1 = $valores[0];
          }
          $op2 = $op1 + 1;

          $ed = $conexion->query("SELECT COUNT(*) total FROM `ed` where ID_Anime='$IdAnime';");

          while ($valores = mysqli_fetch_array($ed)) {
            $ed1 = $valores[0];
          }
          $ed2 = $ed1 + 1;

          ?>

          <div class="form-group">
            <label for="firstNumber">OP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ED:</label><br>
            <input type="number" class="inline-input form-control" min="<?php echo $op1; ?>" value="<?php echo $op1; ?>" max="<?php echo $op2; ?>" name="op" id="op">
            <input type="number" class="inline-input form-control" min="<?php echo $ed1; ?>" value="<?php echo $ed1; ?>" max="<?php echo $ed2; ?>" name="ed" id="ed">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Dia Emision:</label>
            <select name="dias" class="form-control" required>
              <option value="<?php echo $mostrar['Dia']; ?>"><?php echo $mostrar['Dia']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `dias`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Duracion:</label>
            <select name="duracion" class="form-control" required>
              <option value="<?php echo $mostrar['Duracion']; ?>"><?php echo $mostrar['Duracion']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `duracion`;");
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