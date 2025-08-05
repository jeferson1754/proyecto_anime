<!-- Modal para Nuevo Anime -->
<div class="modal fade" id="NuevoAnime" tabindex="-1" aria-labelledby="animeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="animeModalLabel">
          <i class="fas fa-plus-circle me-2"></i>Nuevo Anime
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form name="form-data" action="recibCliente.php" method="POST" class="needs-validation" novalidate>
        <?php
        try {
          $stmt_all_animes = $connect->query("SELECT id, Nombre FROM anime where TipoAnime ='Anime' ORDER BY Nombre ASC;");
          $all_animes = $stmt_all_animes->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          // Manejar el error, por ejemplo, loguearlo y mostrar un mensaje
          $all_animes = [];
        }

        include('regreso-modal.php');
        ?>

        <div class="modal-body">
          <!-- Campos ocultos -->
          <input type="hidden" name="id" value="<?php echo $ani1 ?>">
          <input type="hidden" name="tempo" value="<?php echo $tempo ?>">

          <!-- Nombre del Anime -->
          <div class="mb-3">
            <label class="form-label">
              <i class="fas fa-film me-2"></i>Nombre del Anime
            </label>
            <input type="text" name="anime" class="form-control" required>
            <div class="invalid-feedback">
              Por favor ingrese el nombre del anime
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">
              <i class="fas fa-film me-2"></i>Tipo de Obra
            </label>
            <select name="tipo_anime" class="form-select" id="tipoAnimeSelect" required>
              <option value="">Seleccione el tipo...</option>
              <option value="Anime" selected>Anime Principal</option>
              <option value="Spin off">Spin-off</option>
              <option value="Precuela">Precuela</option>
              <option value="Secuela">Secuela</option>
            </select>
            <div class="invalid-feedback">
              Por favor seleccione el tipo de obra
            </div>
          </div>

          <div class="mb-3 d-none" id="parentAnimeContainer">
            <label class="form-label">
              <i class="fas fa-link me-2"></i>Serie Principal Relacionada
            </label>
            <select name="id_anime_principal" class="form-select" id="parentAnimeSelect">
              <option value="">Seleccione la obra principal...</option>
              <?php foreach ($all_animes as $anime) { ?>
                <option value="<?= htmlspecialchars($anime['id']); ?>">
                  <?= htmlspecialchars($anime['Nombre']); ?>
                </option>
              <?php } ?>
            </select>
            <div class="form-text">
              Seleccione la obra de la que este anime es un spin-off, precuela, etc.
            </div>
          </div>
          <!-- Estado y Año (en la misma fila) -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">
                <i class="fas fa-info-circle me-2"></i>Estado
              </label>
              <select name="estado" class="form-select" required>
                <option value="">Seleccione estado</option>
                <?php
                $query = $conexion->query("SELECT ID,Estado FROM `estado` ORDER BY Estado ASC");
                while ($valores = mysqli_fetch_array($query)) {
                  echo '<option value="' . htmlspecialchars($valores['Estado']) . '">'
                    . htmlspecialchars($valores['Estado']) . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                Seleccione un estado
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">
                <i class="fas fa-calendar-alt me-2"></i>Año
              </label>
              <input type="number" name="fecha" class="form-control"
                min="1900" max="<?php echo $año ?>"
                value="<?php echo $año ?>" required>
              <div class="invalid-feedback">
                Ingrese un año válido
              </div>
            </div>
          </div>

          <!-- Temporada y Día (en la misma fila) -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">
                <i class="fas fa-clock me-2"></i>Temporada
              </label>
              <select name="temp" class="form-select" required>
                <option value="<?php echo $tempo ?>"><?php echo $tempo ?></option>
                <?php
                $query = $conexion->query("SELECT * FROM `temporada` ORDER BY `ID` ASC");
                while ($valores = mysqli_fetch_array($query)) {
                  echo '<option value="' . htmlspecialchars($valores['Temporada']) . '">'
                    . htmlspecialchars($valores['Meses']) . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                Seleccione una temporada
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">
                <i class="fa-solid fa-calendar-day me-2"></i>Día de Emisión
              </label>
              <select name="dias" class="form-select" required>
                <option value="" disabled selected>Seleccione un día</option>
                <?php
                $query = $conexion->query("SELECT * FROM `dias` ORDER BY ID ASC");
                while ($valores = mysqli_fetch_array($query)) {
                  $dia = htmlspecialchars($valores['Dia']);
                  $selected = ($dia == $day) ? ' selected' : '';
                  echo '<option value="' . $dia . '"' . $selected . '>' . $dia . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                Seleccione un día
              </div>
            </div>
          </div>

          <div class="song-options">
            <label class="song-label">
              <i class="fas fa-music"></i>Canciones del Anime
            </label>
            <div class="checkbox-group">
              <div class="custom-checkbox">
                <input type="checkbox" id="checkOP" name="OP" value="SI">
                <label for="checkOP">Opening (OP)</label>
              </div>
              <div class="custom-checkbox">
                <input type="checkbox" id="checkED" name="ED" value="SI">
                <label for="checkED">Ending (ED)</label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Guardar Anime
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  /* Estilos personalizados para el modal */
  .modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  .modal-header {
    border-radius: 1rem 1rem 0 0;
    padding: 1rem 1.5rem;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
  }

  .form-label {
    font-weight: 500;
    color: #495057;
  }

  .form-control,
  .form-select {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid #ced4da;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  .btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
  }

  .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
  }

  .was-validated .form-control:invalid,
  .was-validated .form-select:invalid {
    border-color: #dc3545;
  }

  .was-validated .form-control:valid,
  .was-validated .form-select:valid {
    border-color: #198754;
  }
</style>

<script>
  // Validación del formulario
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');

    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    });

    // Actualizar año máximo automáticamente
    const fechaInput = document.querySelector('input[name="fecha"]');
    const currentYear = new Date().getFullYear();
    fechaInput.max = currentYear;

    // Mostrar tooltip con el rango válido de años
    fechaInput.title = `Año válido entre 1900 y ${currentYear}`;
  });
</script>

<script>
  // Obtener los elementos del DOM
  const tipoAnimeSelect = document.getElementById('tipoAnimeSelect');
  const parentAnimeContainer = document.getElementById('parentAnimeContainer');
  const parentAnimeSelect = document.getElementById('parentAnimeSelect');

  // Función para manejar la visibilidad del select del padre
  function handleParentSelectVisibility() {
    const selectedValue = tipoAnimeSelect.value;
    // Definir qué tipos de anime necesitan un padre
    const typesWithParent = ['Spin-off', 'Precuela', 'Secuela'];

    if (typesWithParent.includes(selectedValue)) {
      parentAnimeContainer.classList.remove('d-none'); // Mostrar el contenedor
      parentAnimeSelect.setAttribute('required', 'required'); // Hacer el select del padre obligatorio
    } else {
      parentAnimeContainer.classList.add('d-none'); // Ocultar el contenedor
      parentAnimeSelect.removeAttribute('required'); // Quitar la obligatoriedad
      parentAnimeSelect.value = ''; // Limpiar la selección
    }
  }

  // Escuchar el evento de cambio en el select del tipo de anime
  tipoAnimeSelect.addEventListener('change', handleParentSelectVisibility);

  // Llamar a la función al cargar la página para manejar el estado inicial
  document.addEventListener('DOMContentLoaded', handleParentSelectVisibility);
</script>