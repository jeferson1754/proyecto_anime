<div class="modal fade delete-modal" id="editChildresn9<?php echo $mostrar['ID']; ?>" tabindex="-1" aria-labelledby="deleteAnimeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="lucide-alert-triangle me-2"></i>
          Confirmar Eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="deleteAnimeForm" method="POST" action="recib_Delete-Pendientes.php">
        <?php include('regreso-modal.php'); ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="id_anime" value="<?php echo $mostrar['ID_Anime']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
        <input type="hidden" name="nombre_aviso" value="<?php echo $mostrar['Nombre_Anime']; ?>">
        <input type="hidden" name="tipo" value="<?php echo $mostrar['Tipo']; ?>">

        <div class="modal-body text-center">
          <i class="fas fa-exclamation-triangle warning-icon"></i>
          <h2 class="anime-title">
            <?php echo $mostrar['Nombre_Anime']; ?>
          </h2>
          <div class="anime-details">
            Capitulos Vistos: <?php echo $mostrar['Vistos']; ?>
          </div>
          <div class="anime-details">
            Totales: <?php echo $mostrar['Total']; ?>
          </div>
          <p class="mt-4 text-gray-600">
            Esta acción no se puede deshacer.
          </p>


          <div class="modal-footer">
            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
              <i class="fas fa-times"></i>
              Cancelar
            </button>
            <button type="submit" class="btn btn-danger" id="deleteButton">
              <i class="lucide-trash-2"></i>Eliminar
            </button>
          </div>
      </form>
    </div>
  </div>
</div>