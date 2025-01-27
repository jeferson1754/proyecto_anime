<div class="modal fade" id="editChildresn10<?php echo $mostrar['ID']; ?>" tabindex="-1" aria-labelledby="updateAnimeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="animeUpdateModal">
          <i class="fas fa-edit me-2"></i>Actualizar Información del Anime
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Update-Pendientes.php" id="updateAnimeForm<?php echo $mostrar['ID']; ?>" class="needs-validation" novalidate>
        <?php include('regreso-modal.php'); ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="nombre_aviso" value="<?php echo $mostrar['Nombre_Anime']; ?>">

        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">
              <i class="lucide-tv-2 me-1"></i>Nombre del Anime
            </label>
            <input type="text" name="name" class="form-control"
              value="<?php echo $mostrar['Temporada']; ?>">
            <i class="lucide-lock input-icon"></i>
          </div>

          <!-- ID Anime -->
          <div class="form-group mb-3">
            <label for="anime" class="col-form-label">Anime:</label>
            <select class="form-control" name="anime" id="anime">
              <option value="" disabled <?= ($mostrar['ID_Anime'] === 0 || empty($mostrar['ID_Anime'])) ? 'selected' : ''; ?>>
                Selecciona un Anime
              </option>
              <?php
              // Consulta para obtener los datos
              $animes = $conexion->query("SELECT id, Nombre FROM `anime` ORDER BY `anime`.`Nombre` ASC");

              // Obtener el ID seleccionado actualmente
              $idSeleccionado = $mostrar['ID_Anime'];

              // Generar opciones
              foreach ($animes as $anime) {
                // Verificar si este es el anime actualmente seleccionado
                $selected = ($anime['id'] == $idSeleccionado) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($anime['Nombre'], ENT_QUOTES, 'UTF-8') . "</option>";
              }
              ?>
            </select>




          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="lucide-list me-1"></i>Tipo
            </label>
            <select name="tipo" class="form-select" style="max-width: 100% !important;" required>
              <option value="<?php echo $mostrar['Tipo']; ?>"><?php echo $mostrar['Tipo']; ?></option>
              <?php
              $query = $conexion->query("SELECT Nombre FROM `tipo`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Nombre'] . '">' . $valores['Nombre'] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="episodes-container">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-md-0">
                  <label class="form-label">
                    <i class="lucide-eye me-1"></i>Capítulos Vistos
                  </label>
                  <input type="number" id="caps<?php echo $mostrar['ID']; ?>" name="caps" min="0" class="form-control"
                    value="<?php echo $mostrar['Vistos']; ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="form-label">
                    <i class="lucide-layers me-1"></i>Total Capítulos
                  </label>
                  <input type="number" id="total<?php echo $mostrar['ID']; ?>" name="total" min="1" class="form-control"
                    value="<?php echo $mostrar['Total']; ?>" required>
                </div>
              </div>
            </div>
            <div id="capsError<?php echo $mostrar['ID']; ?>" class="text-center invalid-feedback">
              Por favor ingrese un número válido de episodios (entre 1 y <?php echo $mostrar['Pendientes']; ?>).
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="lucide-link me-1"></i>Enlace
            </label>
            <input type="url" name="enlace" class="form-control"
              value="<?php echo $mostrar['Link']; ?>"
              placeholder="https://ejemplo.com/anime">
          </div>

          <div class="form-group mb-0">
            <label class="form-label">
              <i class="lucide-activity me-1"></i>Estado del Enlace
            </label>
            <select name="estado" class="form-select" style="max-width: 100% !important;" required>
              <option value="<?php echo $mostrar['Estado_Link']; ?>">
                <?php echo $mostrar['Estado_Link']; ?>
              </option>
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
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="lucide-x"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="lucide-save"></i>Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Form validation
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Seleccionar todos los formularios con la clase `needs-validation`
      const forms = document.querySelectorAll('.needs-validation');


      forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add('was-validated');

          // Validar campos específicos según el ID del formulario
          const formId = form.getAttribute('id').replace('updateAnimeForm', '');

          const caps = document.getElementById(`caps${formId}`);
          const total = document.getElementById(`total${formId}`);

          // Validar relación entre capítulos vistos y total
          validarCampo(caps, total, `capsError${formId}`);
        }, false);
      });

      // Función para validar campos específicos
      function validarCampo(capsInput, totalInput, errorId) {
        const errorMessage = document.getElementById(errorId);

        if (capsInput && totalInput && errorMessage) {
          // Mostrar error si los capítulos vistos superan el total
          if (parseInt(capsInput.value, 10) > parseInt(totalInput.value, 10)) {
            errorMessage.style.display = "block";
            errorMessage.textContent = "Los capítulos vistos no pueden superar el total.";
            capsInput.setCustomValidity("Invalid");
          } else {
            errorMessage.style.display = "none";
            capsInput.setCustomValidity("");
          }
        }
      }



    });
  })();
</script>