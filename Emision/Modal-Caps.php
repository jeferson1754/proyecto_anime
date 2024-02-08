<!--ventana para Update--->
<div class="modal fade" id="editChildresn7<?php echo $mostrar['ID_Emision']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          ¿Realmente desea aumentar el numero de capitulos vistos ?
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


      <form method="POST" action="recib_Update-Cap.php">



        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Emision']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
        <input type="hidden" name="capitulos" value="<?php echo $mostrar['Capitulos']; ?>">
        <?php
        include('regreso-modal.php');

        ?>


        <div class="modal-body div1" id="cont_modal">

          <h1 class="modal-title">
            <?php echo $mostrar['Nombre']; ?>
          </h1>
          <h2 class="modal-title">
            <?php echo $mostrar['Emision']; ?>
          </h2>
          <h2 class="modal-title">
            Vistos:
            <?php echo $mostrar['Capitulos']; ?>
          </h2>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">N° Capitulos Vistos:</label>
            <input type="number" name="vistos" class="form-control-number" min="1" value="1" max="<?php echo $mostrar['Totales']; ?>" required="true">
          </div>
          <h2 class="modal-title">
            Total:
            <?php echo $mostrar['Totales']; ?>
          </h2>
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