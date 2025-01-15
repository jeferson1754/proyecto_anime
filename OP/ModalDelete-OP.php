<!--ventana para Update--->
<div class="modal fade delete-modal" id="editpeli2<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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

      <form method="POST" action="recib_Delete-OP.php">
        <?php
        include('regreso-modal.php');
        ?>
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="anime" value="<?php echo $mostrar['Nombre']; ?>">
        <input type="hidden" name="op" value="<?php echo $mostrar['Opening']; ?>">

        <div class="modal-body">
          <i class="fas fa-exclamation-triangle warning-icon"></i>

          <h2 class="anime-title">
            <?php echo $mostrar['Nombre']; ?> OP <?php echo $mostrar['Opening']; ?>
          </h2>
          <h2 class="anime-details">
            <?php echo $mostrar['Cancion']; ?>
          </h2>
          <h2 class="anime-details">
            <?php echo $mostrar['Autor']; ?>
          </h2>
          <h2 class="anime-details">
            <?php echo $mostrar['Temporada']; ?>
          </h2>
          <h2 class="anime-details">
            <?php echo $mostrar['Ano']; ?>
          </h2>
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
<!---fin ventana Update --->