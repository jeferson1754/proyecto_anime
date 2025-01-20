<!-- Modal para confirmación de eliminación -->
<div class="modal fade" id="editChildresn6<?php echo $mostrar['ID']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0">
      <!-- Header con imagen de fondo -->
      <div class="modal-header border-0 rounded-top-4 text-white position-relative"
        style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); height: 120px;">
        <div class="position-absolute bottom-0 start-0 p-4">
          <h4 class="modal-title mb-0" id="deleteModalLabel">Confirmar Acción</h4>
          <p class="mb-0 opacity-75">¿Qué deseas hacer con este anime?</p>
        </div>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Delete-Emision.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="id_anime" value="<?php echo $mostrar['ID_Anime']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
        <?php include('regreso-modal.php'); ?>

        <div class="modal-body px-4 py-4">
          <!-- Información del Anime -->
          <div class="anime-info mb-4">
            <h3 class="text-center fw-bold text-primary mb-4"><?php echo $mostrar['Nombre_Anime']; ?></h3>

            <div class="row g-3 mb-4">
              <div class="col-12">
                <div class="p-3 rounded-3 bg-light">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="small text-muted mb-1">Emisión</p>
                      <p class="mb-0 fw-medium"><?php echo $mostrar['Estado']; ?></p>
                    </div>
                    <div class="text-end">
                      <div class="d-flex gap-3">
                        <div>
                          <p class="small text-muted mb-1">Vistos</p>
                          <h5 class="mb-0"><?php echo $mostrar['Capitulos']; ?></h5>
                        </div>
                        <div>
                          <p class="small text-muted mb-1">Total</p>
                          <h5 class="mb-0"><?php echo $mostrar['Totales']; ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-grid gap-2">
              <button type="submit" name="Calificar_Ahora" class="btn btn-primary py-3 rounded-3 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-star"></i>
                <span>Borrar y Calificar Ahora</span>
              </button>

              <button type="submit" name="Calificar_Luego" class="btn btn-outline-primary py-3 rounded-3 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-clock"></i>
                <span>Borrar y Calificar Luego</span>
              </button>

              <button type="submit" class="btn btn-light py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 text-danger">
                <i class="fa-solid fa-trash"></i>
                <span>Borrar sin Calificar</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .modal-content {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  .btn {
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }

  .btn-light:hover {
    background-color: #fee2e2;
    border-color: #fee2e2;
  }

  .anime-info {
    position: relative;
  }

  .rounded-3 {
    border-radius: 12px !important;
  }

  .rounded-4 {
    border-radius: 16px !important;
  }

  .fw-medium {
    font-weight: 500;
  }

  .modal-dialog {
    max-width: 400px;
    margin: 1.75rem auto;
  }

  @media (max-width: 576px) {
    .modal-dialog {
      margin: 1rem;
    }
  }
</style>