<style>

</style>

<div class="modal fade delete-modal" id="deleteModal<?php echo $mostrar['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">
          Confirmar Eliminación
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="recib_Delete.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['id']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Anime']; ?>">
        <input type="hidden" name="estado" value="<?php echo $mostrar['Estado']; ?>">
        <?php include('regreso-modal.php'); ?>

        <div class="modal-body">
          <i class="fas fa-exclamation-triangle warning-icon"></i>
          <div class="anime-title">
            <?php echo $mostrar['Anime']; ?>
          </div>
          <div class="anime-details">
            Temporada: <?php echo $mostrar['Temporadas']; ?>
          </div>
          <div class="anime-details">
            Estado: <?php echo $mostrar['Estado']; ?>
          </div>
          <div class="anime-details">
            <?php echo $mostrar['Temporada']; ?>
          </div>
          <p class="mt-4 text-gray-600">
            Esta acción no se puede deshacer.
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
            <i class="fas fa-times"></i>
            Cancelar
          </button>
          <button type="submit" class="btn btn-delete">
            <i class="fas fa-trash-alt"></i>
            Eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>