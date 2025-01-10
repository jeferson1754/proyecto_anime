<div class="modal fade" id="editpeli3<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- Encabezado del Modal -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title">
          Actualizar Información
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Formulario -->
      <form id="updateForm<?php echo $mostrar['ID']; ?>" method="POST" action="recib_Update-Peli.php" novalidate>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="pendi" value="<?php echo $mostrar['ID_Pendientes']; ?>">

        <div class="modal-body" id="cont_modal">
          <!-- Nombre -->
          <div class="form-group mb-3">
            <label for="nombre<?php echo $mostrar['ID']; ?>" class="form-label">Nombre:</label>
            <input type="text" id="nombre<?php echo $mostrar['ID']; ?>" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" placeholder="Ingrese el nombre de la película" required>
            <div class="invalid-feedback">
              El nombre de la película no puede estar vacío.
            </div>
          </div>

          <!-- Año -->
          <div class="form-group mb-3">
            <label for="fecha<?php echo $mostrar['ID']; ?>" class="form-label">Año:</label>
            <input type="number" id="fecha<?php echo $mostrar['ID']; ?>" name="fecha" class="form-control" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo $mostrar['Ano']; ?>" required>
          </div>

          <!-- Estado -->
          <div class="form-group mb-3">
            <label for="estado<?php echo $mostrar['ID']; ?>" class="form-label">Estado:</label>
            <select id="estado<?php echo $mostrar['ID']; ?>" name="estado" class="form-select" style="max-width: 100% !important;" required>
              <option value="" disabled selected>Seleccione el estado</option>
              <option value="Finalizado" <?php echo $mostrar['Estado'] === 'Finalizado' ? 'selected' : ''; ?>>Finalizado</option>
              <option value="Pendiente" <?php echo $mostrar['Estado'] === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
            </select>
          </div>
        </div>

        <!-- Pie del Modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('updateForm<?php echo $mostrar['ID']; ?>');
    const nombreInput = document.getElementById('nombre<?php echo $mostrar['ID']; ?>');

    // Validación en tiempo real
    form.addEventListener('input', function(event) {
      const input = event.target;
      input.classList.remove('is-valid', 'is-invalid');

      if (input.checkValidity()) {
        input.classList.add('is-valid');
      } else {
        input.classList.add('is-invalid');
      }
    });

    // Validación al enviar
    form.addEventListener('submit', function(event) {
      let isValid = true;

      form.querySelectorAll('input, select').forEach(input => {
        if (!input.checkValidity()) {
          input.classList.add('is-invalid');
          isValid = false;
        }
      });

      // Si el campo "nombre" está vacío, se marcará como inválido
      if (!nombreInput.value.trim()) {
        nombreInput.classList.add('is-invalid');
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
        form.classList.add('shake');
        setTimeout(() => form.classList.remove('shake'), 820);
      }
    });
  });
</script>