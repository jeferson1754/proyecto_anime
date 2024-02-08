<div class="modal fade" id="editpeli3<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Actualizar Información
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="recib_Update-Peli.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="pendi" value="<?php echo $mostrar['ID_Pendientes']; ?>">

        <div class="modal-body" id="cont_modal">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $mostrar['Nombre']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Año:</label>
            <input type="number" name="fecha" maxlength="4" min="1900" class="form-control" max="<?php echo $año ?>" value="<?php echo $mostrar['Ano']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control" required>
              <option value="<?php echo $mostrar['Estado']; ?>">Seleccione:</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Pendiente">Pendiente</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->