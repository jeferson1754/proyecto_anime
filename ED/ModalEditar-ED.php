<div class="modal fade" id="editpeli3<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="animeUpdateModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="animeUpdateModal">
          <i class="fas fa-edit me-2"></i>Actualizar Información del Ending
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="recib_Update-ED.php" class="needs-validation" id="Edicion_ED<?php echo $mostrar['ID']; ?>" novalidate>

        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="anime" value="<?php echo $mostrar['ID_Anime']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">


        <?php
        include('regreso-modal.php');
        ?>

        <div class="modal-body">
          <div class="container-fluid">
            <!-- Título del Anime -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card bg-light">
                  <div class="card-body">
                    <h4 class="text-primary text-center mb-0"><?php echo $mostrar['Nombre'] . "  ED " . $mostrar['Ending'] ?></h4>
                  </div>
                </div>
              </div>
            </div>

            <!-- Primera fila: Cancion y Autor -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="text" id="cancion" name="cancion" class="form-control" value="<?php echo $mostrar['Cancion']; ?>">
                  <label for="cancion" class="col-form-label">Cancion:</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" name="autor" id="autor" list="autores" value="<?php echo $mostrar['Autor']; ?>" class="form-control">
                  <datalist id="autores">
                    <?php
                    $mangas = $conexion->query("SELECT * FROM `autor`;");

                    foreach ($mangas as $manga) {
                      echo "<option value='" . $manga['Autor'] . "'></option>";
                    }

                    ?>
                  </datalist>
                  <label for="autor" class="col-form-label">Autor:</label>
                </div>
              </div>
            </div>

            <!-- Segunda fila: Links -->
            <div class="row">
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="text" id="enlace" name="enlace" class="form-control" value="<?php echo $mostrar['Link']; ?>">
                  <label for="enlace" class="col-form-label">Link:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="text" id="iframe" name="iframe" class="form-control" value="<?php echo $mostrar['Link_Iframe']; ?>">
                  <label for="iframe" class="col-form-label">Link Iframe:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <select id="estado_link" name="estado_link" class="form-control" required>
                    <!-- Opción seleccionada actualmente -->
                    <option value="<?php echo htmlspecialchars($mostrar['Estado_Link']); ?>">
                      <?php echo htmlspecialchars($mostrar['Estado_Link']); ?>
                    </option>
                    <?php
                    // Preparar la consulta con una cláusula para excluir el estado actual
                    $estado_actual = $conexion->real_escape_string($mostrar['Estado_Link']);
                    $query = $conexion->query("SELECT Estado FROM `estado_link` WHERE Estado != '$estado_actual'");

                    // Verificar si la consulta se ejecutó correctamente
                    if ($query) {
                      while ($valores = $query->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($valores['Estado']) . '">'
                          . htmlspecialchars($valores['Estado']) . '</option>';
                      }
                    }
                    ?>
                  </select>

                  <label for="estado_link" class="col-form-label">Estado del Link:</label>
                </div>
              </div>
            </div>


            <!-- Tercera fila: Estado y Mix-->
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <select id="estado" name="estado" class="form-control">
                    <option value="<?php echo $mostrar['Estado']; ?>"><?php echo $mostrar['Estado']; ?></option>
                    <?php

                    // Preparar la consulta con una cláusula para excluir el estado actual
                    $estado_actual = $conexion->real_escape_string($mostrar['Estado']);
                    $query = $conexion->query("SELECT * FROM `estado_ed` WHERE Nombre != '$estado_actual'");

                    // Verificar si la consulta se ejecutó correctamente
                    if ($query) {
                      while ($valores = $query->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($valores['Nombre']) . '">'
                          . htmlspecialchars($valores['Nombre']) . '</option>';
                      }
                    }
                    ?>
                  </select>
                  <label for="estado" class="col-form-label">Estado:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="number" id="ed" name="ed" class="form-control" value="<?php echo $mostrar['Ending']; ?>">
                  <label for="ed" class="col-form-label">Ending:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <select id="mix" name="mix" class="form-control">
                    <option value="<?php echo $mostrar['Mix']; ?>"><?php echo $mostrar['Mix']; ?></option>
                    <?php
                    // Preparar la consulta con una cláusula para excluir el estado actual
                    $mix_actual = $conexion->real_escape_string($mostrar['Mix']);
                    $query = $conexion->query("SELECT * FROM `mix_ed` WHERE ID != '$mix_actual'");

                    // Verificar si la consulta se ejecutó correctamente
                    if ($query) {
                      while ($valores = $query->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($valores['ID']) . '">'
                          . htmlspecialchars($valores['ID']) . '</option>';
                      }
                    }
                    ?>
                  </select>
                  <label for="mix" class="col-form-label">Mix:</label>
                </div>
              </div>
            </div>


            <!-- Cuarta fila: Año y Temporada-->
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <select id="temp" name="temp" class="form-control">
                    <option value="<?php echo $mostrar['Temporada']; ?>"><?php echo $mostrar['Temporada']; ?></option>
                    <?php
                    $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `temporada`.`ID` ASC;");
                    while ($valores = mysqli_fetch_array($query)) {
                      echo '<option value="' . $valores['ID'] . '">' . $valores['Meses'] . '</option>';
                    }
                    ?>
                  </select>
                  <label for="temp" class="col-form-label">Temporada:</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="number" id="ano" name="ano" class="form-control" value="<?php echo $mostrar['Ano']; ?>">
                  <label for="ano" class="col-form-label">Año:</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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
<style>
  .modal-dialog-scrollable {
    max-height: 90vh;
  }

  .form-floating {
    position: relative;
    margin-bottom: 1rem;
  }

  .form-floating>.form-control,
  .form-floating>.form-select {
    height: calc(3.5rem + 2px);
    padding: 1rem 0.75rem;
  }

  .form-floating>label {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    padding: 1rem 0.75rem;
    pointer-events: none;
    border: 1px solid transparent;
    transform-origin: 0 0;
    transition: opacity .1s ease-in-out, transform .1s ease-in-out;
  }

  .card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  .btn {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
  }

  .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
  }

  .was-validated .form-control:invalid {
    border-color: red;
  }

  .was-validated .form-control:valid {
    border-color: green;
  }

  /* Mostrar el mensaje de error solo si el campo es inválido */
  .invalid-feedback {
    display: none;
    /* Ocultar el mensaje por defecto */
  }

  /* Mostrar el mensaje cuando el campo es inválido */
  .was-validated .form-control:invalid~.invalid-feedback {
    display: block;
    /* Mostrar el mensaje de error */
  }

  .input-group {
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border-radius: 0.25rem;
    transition: all 0.3s ease;
  }

  .input-group:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
  }

  /* Input styling */
  .input-group input[type="number"] {
    border-radius: 0;
    border-right: none;
    text-align: center;
    font-size: 1rem;
    font-weight: 500;
  }

  /* Remove spinner arrows from number input */
  .input-group input[type="number"]::-webkit-inner-spin-button,
  .input-group input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Icon styling */
  .input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-right: none;
    padding: 0.375rem 0.75rem;
  }

  .input-group-text i {
    color: #6c757d;
    width: 16px;
    text-align: center;
  }

  /* Button styling */
  .input-group .btn {
    padding: 0.375rem 0.5rem;
    border: 1px solid #ced4da;
    background-color: #fff;
    color: #6c757d;
    transition: all 0.2s ease;
  }

  .input-group .btn:hover {
    background-color: #e9ecef;
    color: #495057;
  }

  .input-group .btn:active {
    background-color: #dde0e3;
  }

  /* Help text styling */
  .form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
    color: #6c757d;
  }

  /* Invalid state */
  .was-validated .form-control:invalid,
  .form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
  }

  .invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
  }
</style>

<script>
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Seleccionar todos los formularios que tienen la clase `needs-validation`
      const forms = document.querySelectorAll('.needs-validation');

      forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
          // Prevenir el envío si el formulario es inválido
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }

          // Agregar la clase 'was-validated' para aplicar los estilos
          form.classList.add('was-validated');

          // Función para manejar la validación de campos
          function validarCampo(inputId, errorId) {
            const input = document.getElementById(inputId);
            const errorMessage = document.getElementById(errorId);

            if (input && errorMessage) { // Verificar que los elementos existan
              // Mostrar u ocultar el mensaje de error según la validez del campo
              errorMessage.style.display = input.validity.valid ? "none" : "block";
            }
          }

          // Obtener el ID dinámico del formulario actual
          const formId = form.getAttribute('id').replace('Edicion_Emision', '');

          // Validaciones específicas
          validarCampo(`posicionInput${formId}`, `posicionError${formId}`);
          validarCampo(`faltantesInput${formId}`, `faltantesError${formId}`);
        }, false);
      });
    }, false);
  })();
</script>