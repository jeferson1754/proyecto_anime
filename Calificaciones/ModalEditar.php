<div class="modal fade" id="editCalif<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

      <form method="POST" action="recib_Update.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Anime']; ?>">

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Link de Imagen:</label>
            <input type="link" name="link_imagen" class="form-control" value="<?php echo $mostrar['Link_Imagen']; ?>" required="true">
          </div>

          <div class="form-group">
            <div class="rating-box">
              <header>Calificacion del Anime</header>
              <div class="stars product-stars">
                <!-- Estrellas del anime -->
                <?php

                include('regreso-modal.php');

                $calificacion = $mostrar["Promedio"];
                $texto = "Promedio:";

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

              <div class="rating-text product-rating"><?php echo $texto ?>
                <span class="product-rating-value"><?php echo $calificacion ?></span>
              </div>

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