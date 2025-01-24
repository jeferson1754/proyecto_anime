<!-- Modal para Registrar Película -->
<div class="modal fade" id="editpeli1" tabindex="-1" aria-labelledby="modalNuevaPelicula" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Encabezado del Modal -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalNuevaPelicula">
          <i class="bi bi-film me-2"></i>Nueva Película
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Formulario -->
      <form id="form-data" action="recibCliente-Peli.php" method="POST" novalidate>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?php echo $peli1; ?>">

          <!-- Nombre de la Película -->
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Película</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingresa el nombre de la película" required>
          </div>

          <!-- ID Anime -->
          <div class="form-group mb-3">
            <label for="anime" class="col-form-label">Anime:</label>
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
          <!-- Estado -->
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" style="max-width: 100% !important;" required>
              <option value="" selected disabled>Seleccione el estado</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Pendiente">Pendiente</option>
            </select>
          </div>

          <!-- Año -->
          <div class="mb-3">
            <label for="fecha" class="form-label">Año</label>
            <input type="number" name="fecha" id="fecha" min="1900" max="<?php echo date('Y'); ?>" class="form-control" value="<?php echo date('Y'); ?>" required>
          </div>
        </div>

        <!-- Pie del Modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cerrar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Registrar Película
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-data');

    // Validación en tiempo real
    form.addEventListener('input', function(event) {
      const input = event.target;

      // Remover clases de validación previas
      input.classList.remove('is-valid', 'is-invalid');

      // Validar campo actual
      if (input.checkValidity()) {
        input.classList.add('is-valid');
      } else {
        input.classList.add('is-invalid');
      }
    });

    // Validación al enviar
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      let isValid = true;

      // Validar todos los campos
      form.querySelectorAll('input, select').forEach(input => {
        if (!input.checkValidity()) {
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
      form.submit();
    });
  });
</script>

<style>
  @keyframes shake {

    0%,
    100% {
      transform: translateX(0);
    }

    25% {
      transform: translateX(-5px);
    }

    50% {
      transform: translateX(5px);
    }

    75% {
      transform: translateX(-5px);
    }
  }

  .shake {
    animation: shake 0.82s cubic-bezier(.36, .07, .19, .97) both;
  }

  .is-valid {
    border-color: #198754;
  }

  .is-invalid {
    border-color: #dc3545;
  }
</style>