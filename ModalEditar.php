<div class="modal fade" id="editChildresn4<?php echo $mostrar['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Actualizar Anime
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <!--<form method="POST" action="recib_Update.php">-->
      <form method="POST" action="recib_Update copy.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['id']; ?>">
        <input type="hidden" name="emision" value="<?php echo $mostrar['id_Emision']; ?>">
        <input type="hidden" name="pendientes" value="<?php echo $mostrar['id_Pendientes']; ?>">

        <?php
        include('regreso-modal.php');
        ?>

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">ID:</label>
            <input type="number" min="1" name="id2" class="form-control" value="<?php echo $mostrar['id']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Anime:</label>
            <input type="text" name="anime" class="form-control" value="<?php echo $mostrar['Anime']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Ultima Temporada:</label>
            <input type="text" name="temps" class="form-control" value="<?php echo $mostrar['Temporadas']; ?>">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Peliculas:</label>
            <input type="number" name="peli" class="form-control" value="<?php echo $mostrar['Peliculas']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Spin-Off:</label>
            <input type="text" name="spin" class="form-control" value="<?php echo $mostrar['Spin_Off']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control">
              <option value="<?php echo $mostrar['Estado']; ?>"><?php echo $mostrar['Estado']; ?></option>
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
            <input type="number" name="fecha" min="1900" class="form-control" max="<?php echo $año ?>" value="<?php echo $mostrar['Ano']; ?>" required="true">

          </div>
          <style>
            .inline-input {
              display: inline-block;
              width: 49%;
            }
          </style>
          <?php
          $op = $conexion->query("SELECT COUNT(*) total FROM `op` where ID_Anime='$iden';");

          while ($valores = mysqli_fetch_array($op)) {
            $op1 = $valores[0];
          }
          $op2 = $op1 + 1;

          $ed = $conexion->query("SELECT COUNT(*) total FROM `ed` where ID_Anime='$iden';");

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
            <label for="recipient-name" class="col-form-label">Temporada:</label>
            <select name="temp" class="form-control">
              <option value="<?php echo $mostrar['Id_Temporada']; ?>"><?php echo $mostrar['Temporada']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `temporada`.`ID` ASC;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['ID'] . '">' . $valores['Meses'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <div class="rating-box">
              <header>Calificacion del Anime</header>
              <div class="stars product-stars">
                <!-- Estrellas del anime -->
                <?php

                $sql2 = "SELECT promedio FROM calificaciones WHERE ID_Anime=$iden"; // Ajusta el ID según tu estructura de base de datos
                //echo $sql;
                $result2 = $conexion->query($sql2);

                // Obtener y almacenar las calificaciones en el array
                if ($result2->num_rows > 0) {
                  // Obtener la primera fila (solo debería haber una fila si estás buscando un ID específico)
                  $row = $result2->fetch_assoc();

                  $calificacion = $row["promedio"];
                  $texto = "Promedio:";
                } else {
                  $calificacion = "";
                  $texto = "Sin Calificar Aun";
                }


                // Establecer el número de estrellas activas según la calificación
                for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $calificacion) {
                    echo '<i class="fa-solid fa-star active"></i>';
                  } else {
                    echo '<i class="fa-solid fa-star"></i>';
                  }
                }
                ?>
              </div>
              <!-- Texto de calificación del anime -->

              <div class="rating-text product-rating"><?php echo $texto ?> <span class="product-rating-value"><?php echo $calificacion ?></span></div>';

            </div>
            <?php
            $variable_nombre = urlencode($mostrar["Anime"]); // Asegúrate de codificar el nombre para que sea seguro en una URL
            ?>
            <div class=" btn btn-secondary centrar">
              <a href="editar_stars.php?id=<?php echo $iden; ?>&nombre=<?php echo $variable_nombre; ?>" class="link">
                Cambiar Calificacion
              </a>
            </div>
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