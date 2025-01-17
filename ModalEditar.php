<div class="modal fade" id="editModal<?php echo $mostrar['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit me-2"></i>Actualizar Anime
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="recib_Update.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['id']; ?>">

        <?php
        include('regreso-modal.php');

        // Consultar la cantidad de OP y calcular el siguiente número
        $queryOp = "SELECT COUNT(*) AS total FROM `op` WHERE ID_Anime = ?";
        $stmtOp = $conexion->prepare($queryOp);
        $stmtOp->bind_param('i', $iden);
        $stmtOp->execute();
        $resultOp = $stmtOp->get_result();
        $op1 = $resultOp->fetch_assoc()['total'] ?? 0;
        $op2 = $op1 + 1;
        $stmtOp->close();

        // Consultar la cantidad de ED y calcular el siguiente número
        $queryEd = "SELECT COUNT(*) AS total FROM `ed` WHERE ID_Anime = ?";
        $stmtEd = $conexion->prepare($queryEd);
        $stmtEd->bind_param('i', $iden);
        $stmtEd->execute();
        $resultEd = $stmtEd->get_result();
        $ed1 = $resultEd->fetch_assoc()['total'] ?? 0;
        $ed2 = $ed1 + 1;
        $stmtEd->close();
        ?>

        <input type="hidden" name="op_total" value="<?php echo $ed1; ?>">
        <input type="hidden" name="ed_total" value="<?php echo $op1; ?>">

        <div class="modal-body">
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Anime</label>
                <input type="text" name="anime" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" required>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Última Temporada</label>
                <input type="text" name="temps" class="form-control" value="<?php echo $mostrar['Temporadas']; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Películas</label>
                <input type="number" name="peli" class="form-control" value="<?php echo $mostrar['Peliculas']; ?>" required>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Spin-Off</label>
                <input type="text" name="spin" class="form-control" value="<?php echo $mostrar['Spin_Off']; ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control">
                  <?php
                  // Consulta para obtener los estados
                  $query = "SELECT ID, Estado FROM `estado`;";
                  $stmt = $connect->prepare($query);
                  $stmt->execute();
                  $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  // Mostrar el estado actual seleccionado si existe
                  if (empty($mostrar['Estado'])) {
                    echo "<option value=''>Selecciona un estado</option>";
                  }

                  // Mostrar las opciones de los estados disponibles
                  if ($estados) {
                    foreach ($estados as $estado) {
                      echo "<option value='{$estado['Estado']}' " .
                        ($estado['Estado'] === $mostrar['Estado'] ? 'selected' : '') .
                        ">{$estado['Estado']}</option>";
                    }
                  } else {
                    echo "<option value=''>No hay estados disponibles</option>";
                  }
                  ?>
                </select>

              </div>
            </div>


          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">OP</label>
                <input type="number" class="form-control" min="<?php echo $op1; ?>" value="<?php echo $op1; ?>" max="<?php echo $op2; ?>" name="op" id="op">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">ED</label>
                <input type="number" class="form-control" min="<?php echo $ed1; ?>" value="<?php echo $ed1; ?>" max="<?php echo $ed2; ?>" name="ed" id="ed">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Año</label>
                <input type="number" name="fecha" min="1900" class="form-control" max="<?php echo $año ?>" value="<?php echo $mostrar['Ano']; ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Temporada</label>
                <select name="temp" class="form-control">
                  <option value="<?php echo $mostrar['Temporada']; ?>"><?php echo $mostrar['Temporada']; ?></option>
                  <?php
                  $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `temporada`.`ID` ASC;");
                  while ($valores = mysqli_fetch_array($query)) {
                    echo '<option value="' . $valores['Temporada'] . '">' . $valores['Meses'] . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>


          <div class="rating-box">
            <header>Calificación del Anime</header>
            <div class="stars">
              <?php
              $sql2 = "SELECT promedio FROM calificaciones WHERE ID_Anime=$iden";
              $result2 = $conexion->query($sql2);

              if ($result2->num_rows > 0) {
                $row = $result2->fetch_assoc();
                $calificacion = $row["promedio"];
                $texto = "Promedio:";
              } else {
                $calificacion = "";
                $texto = "Sin Calificar Aun";
              }

              for ($i = 1; $i <= 5; $i++) {
                if ($i <= $calificacion) {
                  echo '<i class="fa-solid fa-star active"></i>';
                } else {
                  echo '<i class="fa-solid fa-star"></i>';
                }
              }
              ?>
            </div>
            <div class="rating-text">
              <?php echo $texto ?> <span class="rating-value"><?php echo $calificacion ?></span>
            </div>
            <?php $variable_nombre = urlencode($mostrar["Nombre"]); ?>
            <?php $variable_temporada = urlencode($mostrar['Temporadas']); ?>
            <a href="./Calificaciones/editar_stars.php?id=<?php echo $iden; ?>&nombre=<?php echo $variable_nombre; ?>&temporada=<?php echo $variable_temporada; ?>"
              class="btn btn-secondary mt-3">
              Cambiar Calificación
            </a>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cerrar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>