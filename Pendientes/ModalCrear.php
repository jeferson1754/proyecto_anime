<div class="modal fade" id="NuevoAnime" tabindex="-1" aria-labelledby="modalAnimeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white fw-bold">
          <i class="lucide-play-circle me-2"></i>
          Nuevo Anime Pendiente
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="animeForm" method="POST" action="recibCliente.php" novalidate>
        <?php include('regreso-modal.php'); ?>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label" for="nombre">
              <i class="lucide-film me-1"></i>Nombre del Anime
            </label>
            <i class="lucide-type input-icon"></i>
            <input type="text" id="nombre" name="nombre" class="form-control"
              placeholder="Ingresa el nombre del anime" required
              minlength="2" maxlength="100">
            <div class="invalid-feedback">
              Por favor ingresa un nombre válido (2-100 caracteres)
            </div>
          </div>

          <!-- ID Anime -->
          <div class="form-group">
            <label for="anime" class="form-label">Anime:</label>
            <select class="js-example-matcher-start form-control" name="anime" id="anime">
              <option value="" disabled selected>Selecciona un Anime</option>
              <?php
              // Consulta para obtener los datos
              $animes = $conexion->query("SELECT id, Nombre FROM `anime` ORDER BY `anime`.`Nombre` ASC");

              // Generar opciones
              foreach ($animes as $anime) {
                echo "<option value='" . htmlspecialchars($anime['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($anime['Nombre'], ENT_QUOTES, 'UTF-8') . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="tipo">
              <i class="lucide-list me-1"></i>Tipo
            </label>
            <i class="lucide-chevron-down input-icon"></i>
            <select id="tipo" name="tipo" class="form-select" style="max-width: 100% !important;" required>
              <option value="" disabled selected>Selecciona un tipo</option>
              <?php
              $query = $conexion->query("SELECT Nombre FROM `tipo`;");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Nombre'] . '">' . $valores['Nombre'] . '</option>';
              }
              ?>
            </select>
            <div class="invalid-feedback">
              Por favor selecciona un tipo de anime
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="caps">
                  <i class="lucide-eye me-1"></i>Capítulos Vistos
                </label>
                <i class="lucide-play input-icon"></i>
                <input type="number" id="caps" name="caps" min="0" value="0"
                  class="form-control" required>
                <div class="invalid-feedback">
                  El número debe ser mayor o igual a 0
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="total">
                  <i class="lucide-layers me-1"></i>Total Capítulos
                </label>
                <i class="lucide-list input-icon"></i>
                <input type="number" id="total" name="total" min="1"
                  class="form-control" required>
                <div class="invalid-feedback">
                  Debe ser mayor que 0 y mayor que los capítulos vistos
                </div>
              </div>
            </div>
          </div>
          <div class="form-group mb-0">
            <label class="form-label" for="enlace">
              <i class="lucide-link me-1"></i>Enlace
            </label>
            <i class="lucide-link input-icon"></i>
            <input type="url" id="enlace" name="enlace" class="form-control"
              placeholder="https://ejemplo.com/anime">
            <div class="invalid-feedback">
              Por favor ingresa una URL válida
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="lucide-x"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="lucide-save"></i>Guardar Anime
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();

  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('animeForm');
    const capsInput = document.getElementById('caps');
    const totalInput = document.getElementById('total');

    // Validación personalizada para capítulos
    function validateEpisodes() {
      const caps = parseInt(capsInput.value);
      const total = parseInt(totalInput.value);

      if (caps < 0) {
        capsInput.setCustomValidity('Los capítulos vistos no pueden ser negativos');
        return false;
      }

      if (total < 1) {
        totalInput.setCustomValidity('El total de capítulos debe ser mayor a 0');
        return false;
      }

      if (caps > total) {
        capsInput.setCustomValidity('Los capítulos vistos no pueden ser mayores al total');
        return false;
      }

      capsInput.setCustomValidity('');
      totalInput.setCustomValidity('');
      return true;
    }

    // Validación en tiempo real
    form.addEventListener('input', function(event) {
      const input = event.target;

      // Remover clases de validación previas
      input.classList.remove('is-valid', 'is-invalid');

      // Validar campo actual
      if (input.checkValidity() && (input !== capsInput && input !== totalInput || validateEpisodes())) {
        input.classList.add('is-valid');
      } else {
        input.classList.add('is-invalid');
      }
    });

    // Validación al enviar
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      // Validar todos los campos
      let isValid = true;
      form.querySelectorAll('input, select').forEach(input => {
        if (!input.checkValidity() || (input === capsInput || input === totalInput) && !validateEpisodes()) {
          input.classList.add('is-invalid');
          isValid = false;
        }
      });

      if (!isValid) {
        // Animar el formulario si hay errores
        form.classList.add('shake');
        setTimeout(() => form.classList.remove('shake'), 820);
        return;
      }

      // Si todo está válido, enviar el formulario
      this.submit();
    });

    // Validar capítulos cuando cambien los valores
    capsInput.addEventListener('change', validateEpisodes);
    totalInput.addEventListener('change', validateEpisodes);
  });
</script>