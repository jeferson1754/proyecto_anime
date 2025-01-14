<div class="modal fade" id="editChildresn10<?php echo $mostrar['ID']; ?>" tabindex="-1" aria-labelledby="updateAnimeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="animeUpdateModal">
          <i class="fas fa-edit me-2"></i>Actualizar Imagen
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Update.php">
        <?php include('regreso-modal.php'); ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Anime']; ?>">

        <div class="modal-body">
          <div class="text-center mb-4">
            <div class="preview-image-container">
              <img id="imagePreview<?php echo $mostrar['ID']; ?>"
                src="<?php echo $mostrar['Link_Imagen'] ?: 'https://i0.wp.com/ayuda.marketplace.paris.cl/wp-content/uploads/2024/02/placeholder.png?fit=1200%2C800&ssl=1'; ?>"
                alt="Preview"
                class="img-preview">
            </div>
          </div>

          <div class="form-group mb-4">
            <label for="linkImagen<?php echo $mostrar['ID']; ?>" class="form-label">
              <i class="fas fa-link me-2"></i>Link de Imagen
            </label>
            <div class="input-group">
              <input type="url"
                id="linkImagen<?php echo $mostrar['ID']; ?>"
                name="link_imagen"
                class="form-control"
                value="<?php echo $mostrar['Link_Imagen']; ?>"
                required
                placeholder="https://ejemplo.com/imagen.jpg"
                onchange="updatePreview(this, <?php echo $mostrar['ID']; ?>)">
              <button class="btn btn-outline-secondary" type="button" onclick="clearImage(<?php echo $mostrar['ID']; ?>)">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>

          <!-- Calificación actual -->
          <div class="rating-display">
            <h6 class="text-center mb-3">
              <i class="fas fa-star me-2 text-warning"></i>Calificación Actual
            </h6>
            <div class="rating-box">
              <div class="stars product-stars">
                <!-- Estrellas del anime -->
                <?php

                $calificacion = $mostrar["Promedio"];

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
            </div>
            <div class="rating-value text-center">
              Promedio: <span class="badge bg-primary"><?php echo $calificacion ?></span>
            </div>
          </div>



        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function updatePreview(input, id) {
    const preview = document.getElementById('imagePreview' + id);
    if (input.value) {
      preview.src = input.value;
    } else {
      preview.src = 'https://i0.wp.com/ayuda.marketplace.paris.cl/wp-content/uploads/2024/02/placeholder.png?fit=1200%2C800&ssl=1';
    }
  }

  function clearImage(id) {
    const input = document.getElementById('linkImagen' + id);
    const preview = document.getElementById('imagePreview' + id);
    input.value = '';
    preview.src = 'https://i0.wp.com/ayuda.marketplace.paris.cl/wp-content/uploads/2024/02/placeholder.png?fit=1200%2C800&ssl=1';
  }
</script>