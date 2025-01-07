<!-- Modal for Anime Information Update -->
<div class="modal fade" id="editChildresn5<?php echo $mostrar['ID_Emision']; ?>"
  tabindex="-1"
  role="dialog"
  aria-labelledby="animeUpdateModal"
  aria-hidden="true">

  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="animeUpdateModal">
          <i class="fas fa-edit me-2"></i>Actualizar Información del Anime
        </h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Update-Emision.php" class="needs-validation" id="Edicion_Emision<?php echo $mostrar['ID_Emision']; ?>" novalidate>
        <!-- Hidden Fields -->
        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Emision']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">

        <?php
        include('regreso-modal.php');
        $idRegistros = $mostrar['ID_Emision'];
        $estado = $mostrar['Emision'];
        $sql2 = $conexion->query("SELECT * FROM `anime` where id_Emision='$idRegistros';");
        while ($valores = mysqli_fetch_array($sql2)) {
          $IdAnime = $valores["id"];
        }
        ?>

        <div class="modal-body">
          <div class="container-fluid">
            <!-- Título del Anime -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card bg-light">
                  <div class="card-body">
                    <h4 class="text-primary text-center mb-0"><?php echo $mostrar['Nombre']; ?></h4>
                  </div>
                </div>
              </div>
            </div>

            <!-- Primera fila: Estado y Posición -->
            <div class="row">
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <select name="estado" class="form-select" id="estadoSelect" required>
                    <option value="<?php echo $mostrar['Emision']; ?>"><?php echo $mostrar['Emision']; ?></option>
                    <?php
                    $query = $conexion->query("SELECT Estado FROM `estado` where Estado !='$estado' AND Estado !='Finalizado';");
                    while ($valores = mysqli_fetch_array($query)) {
                      echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
                    }
                    ?>
                  </select>
                  <label for="estadoSelect">Estado</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating">
                  <?php
                  $query = $conexion->query("SELECT COUNT(Posicion) as conteo FROM `emision` WHERE Dia='$mostrar[Dia]' and Emision='Emision';");
                  while ($valores = mysqli_fetch_array($query)) {
                    $conteo = $valores['conteo'];
                  }
                  ?>
                  <input type="number"
                    name="posicion"
                    class="form-control mb-3"
                    id="posicionInput<?php echo $mostrar['ID_Emision']; ?>"
                    min="0"
                    max="<?php echo $conteo; ?>"
                    value="<?php echo $mostrar['Posicion']; ?>"
                    required>
                  <label for="posicionInput">Posición</label>

                  <!-- Mensaje de error -->
                  <div id="posicionError<?php echo $mostrar['ID_Emision']; ?>" class="invalid-feedback">
                    Por favor ingrese una posición válida (entre 0 y <?php echo $conteo; ?>)
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <select name="dias" class="form-select" id="diasSelect" required>
                    <!-- Opción preseleccionada -->
                    <option value="<?php echo $mostrar['Dia']; ?>" selected><?php echo $mostrar['Dia']; ?></option>

                    <?php
                    // Consulta para obtener los días
                    $query = $conexion->query("SELECT * FROM `dias`;");

                    // Iteración sobre los resultados
                    while ($valores = mysqli_fetch_array($query)) {
                      // Verificar si la opción ya está seleccionada, en caso contrario, mostrarla
                      if ($valores['Dia'] != $mostrar['Dia']) {
                        echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
                      }
                    }
                    ?>
                  </select>

                  <label for="diasSelect">Día de Emisión</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <select name="duracion" class="form-select" id="duracionSelect" required>
                    <!-- Opción preseleccionada -->
                    <option value="<?php echo $mostrar['Duracion']; ?>" selected><?php echo $mostrar['Duracion']; ?></option>

                    <?php
                    // Consulta para obtener las duraciones
                    $query = $conexion->query("SELECT * FROM `duracion`;");

                    // Iteración sobre los resultados
                    while ($valores = mysqli_fetch_array($query)) {
                      // Verificar si la opción ya está seleccionada, en caso contrario, mostrarla
                      if ($valores['Duracion'] != $mostrar['Duracion']) {
                        echo '<option value="' . $valores['Duracion'] . '">' . $valores['Duracion'] . '</option>';
                      }
                    }
                    ?>
                  </select>

                  <label for="duracionSelect">Duración</label>
                </div>
              </div>

            </div>
            <!-- Segunda fila: Capítulos -->
            <div class="row">
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="number"
                    name="caps"
                    class="form-control"
                    id="capsInput"
                    min="1"
                    max="<?php echo $mostrar['Totales']; ?>"
                    value="<?php echo $mostrar['Capitulos']; ?>"
                    required>
                  <label for="capsInput">Capítulos Vistos</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="number"
                    name="faltantes"
                    class="form-control"
                    id="faltantesInput<?php echo $mostrar['ID_Emision']; ?>"
                    min="0"
                    value="<?php echo $faltantes ?>"
                    required>
                  <label for="faltantesInput">Capítulos Faltantes</label>
                  <div id="faltantesError<?php echo $mostrar['ID_Emision']; ?>" class="invalid-feedback">
                    Por favor ingrese una numero válido (mayor o igual a 0)
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <input type="number"
                    name="total"
                    class="form-control"
                    id="totalInput"
                    value="<?php echo $mostrar['Totales']; ?>"
                    required>
                  <label for="totalInput">Total Capítulos</label>
                </div>
              </div>
            </div>

            <!-- Tercera fila: OP y ED -->
            <?php
            $op = $conexion->query("SELECT COUNT(*) total FROM `op` where ID_Anime='$IdAnime';");
            while ($valores = mysqli_fetch_array($op)) {
              $op1 = $valores[0];
            }
            $op2 = $op1 + 1;

            $ed = $conexion->query("SELECT COUNT(*) total FROM `ed` where ID_Anime='$IdAnime';");
            while ($valores = mysqli_fetch_array($ed)) {
              $ed1 = $valores[0];
            }
            $ed2 = $ed1 + 1;
            ?>
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="number"
                    name="op"
                    class="form-control"
                    id="opInput"
                    min="<?php echo $op1; ?>"
                    max="<?php echo $op2; ?>"
                    value="<?php echo $op1; ?>"
                    required>
                  <label for="opInput">Opening (OP)</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="number"
                    name="ed"
                    class="form-control"
                    id="edInput"
                    min="<?php echo $ed1; ?>"
                    max="<?php echo $ed2; ?>"
                    value="<?php echo $ed1; ?>"
                    required>
                  <label for="edInput">Ending (ED)</label>
                </div>
              </div>
            </div>

            <!-- Cuarta fila: Día y Duración -->
            <div class="row mb-3">

            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
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