<!--ventana para Update--->
<div class="modal fade" id="editpeli2<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          ¿Realmente deseas eliminar a ?
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <style>
        .div1 {
          text-align: center;
        }
      </style>


      <form method="POST" action="recib_Delete-Peli.php">

        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">
        <input type="hidden" name="estado" value="<?php echo $mostrar['Estado']; ?>">

        <div class="modal-body div1" id="cont_modal">

          <h1 class="modal-title">
            <?php echo $mostrar['Nombre']; ?> 
          </h1>
          <h2 class="modal-title">
            <?php echo $mostrar['Ano']; ?>
          </h2>
          <h2 class="modal-title">
            <?php echo $mostrar['Estado']; ?>
          </h2>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Borrar</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->