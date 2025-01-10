<div class="modal fade" id="editChildresn11<?php echo $mostrar['ID_Pendientes']; ?>" tabindex="-1" aria-labelledby="updateEpisodesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="lucide-play-circle me-2"></i>
          Actualizar Capítulos Vistos
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Update-Cap.php" id="Edicion_Pendientes<?php echo $mostrar['ID_Pendientes']; ?>" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Pendientes']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
        <input type="hidden" name="capitulos" value="<?php echo $mostrar['Vistos']; ?>">

        <div class="modal-body text-center">
          <div class="text-center mb-4">
            <h2 class="anime-title">
              <i class="lucide-tv-2 me-2"></i>
              <?php echo $mostrar['Nombre']; ?>
            </h2>

            <div class="row mt-3">
              <div class="col">
                <div class="card bg-light">
                  <div class="card-body">
                    <h6>Episodios Vistos</h6>
                    <span class="h4"><?php echo $mostrar['Vistos']; ?></span>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card bg-light">
                  <div class="card-body">
                    <h6>Total Episodios</h6>
                    <span class="h4"><?php echo $mostrar['Total']; ?></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="episode-input">
              <label for="episodesInput">
                <i class="lucide-plus-circle me-1"></i>
                Agregar Capítulos Vistos
              </label>
              <input type="number" id="episodesInput<?php echo $mostrar['ID_Pendientes']; ?>" name="vistos" class="form-control" min="1" value="1" max="<?php echo $mostrar['Pendientes']; ?>" required>
              <small class="text-muted mt-2 d-block">
                Máximo <?php echo $mostrar['Pendientes']; ?> capítulos pendientes
              </small>
              <div id="episodiosError<?php echo $mostrar['ID_Pendientes']; ?>" class="invalid-feedback">
                Por favor ingrese un número válido de episodios (entre 1 y <?php echo $mostrar['Pendientes']; ?>).
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="lucide-x"></i>
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="lucide-save"></i>
            Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
  }

  .badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
  }

  .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
  }

  .card {
    transition: transform 0.2s;
  }

  .card:hover {
    transform: translateY(-2px);
  }
</style>

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
          const formId = form.getAttribute('id').replace('Edicion_Pendientes', '');

          validarCampo(`episodesInput${formId}`, `episodiosError${formId}`);
        }, false);
      });

      // Función para validar un campo específico
      function validarCampo(inputId, errorId) {
        const input = document.getElementById(inputId);
        const errorMessage = document.getElementById(errorId);

        if (input && errorMessage) {
          errorMessage.style.display = input.validity.valid ? "none" : "block";
        }
      }
    });
  })();
</script>