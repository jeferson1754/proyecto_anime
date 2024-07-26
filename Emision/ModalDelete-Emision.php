<!--ventana para Update--->
<div class="modal fade" id="editChildresn6<?php echo $mostrar['ID_Emision']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


      <form method="POST" action="recib_Delete-Emision.php">

        <input type="hidden" name="id" value="<?php echo $mostrar['ID_Emision']; ?>">
        <input type="hidden" name="nombre" value="<?php echo $mostrar['Nombre']; ?>">
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
          <h2 class="modal-title">
            Total:
            <?php echo $mostrar['Totales']; ?>
          </h2>
        </div>
        <div class="modal-footer" style="display: flex; flex-direction: column; align-items: center;">
          <div>
            <button type="submit" name="Calificar_Ahora" class="btn btn-primary" style="font-size: 18px;">
              <i class="fa-solid fa-star"></i> Borrar y Calificar Anime Ahora
            </button>
          </div>
          <div style="margin-top: 10px;">
            <button type="submit" name="Calificar_Luego" class="btn btn-warning" style="font-size: 18px;">
              <i class="fa-solid fa-clock"></i> Borrar y Calificar Anime Luego
            </button>
          </div>
          <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-danger" style="font-size: 18px;">
              <i class="fa-solid fa-trash"></i> Borrar y No Calificar
            </button>
          </div>
        </div>


      </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->