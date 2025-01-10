<!-- Ventana para eliminar película -->
<div class="modal fade delete-modal" id="editpeli2<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="lucide-alert-triangle me-2"></i>
          Confirmar Eliminación
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="recib_Delete-Peli.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="estado" value="<?php echo $mostrar['Estado']; ?>">

        <div class="modal-body text-center" id="cont_modal">
          <i class="fas fa-exclamation-triangle warning-icon"></i>
          <h2 class="anime-title">
            <?php echo $mostrar['Nombre']; ?>
          </h2>
          <div class="anime-details">
            <?php echo $mostrar['Ano']; ?>
          </div>
          <div class="anime-details">
            <?php echo $mostrar['Estado']; ?>
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
          <button type="submit" class="btn btn-danger" id="deleteButton">
            <i class="fas fa-trash"></i>Eliminar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
<!-- Fin ventana eliminar película -->