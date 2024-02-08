<!--ventana para Update--->
<div class="modal fade" id="NuevoMix" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Nuevo Mix
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
      <form name="form-data" action="recib_Delete.php" method="POST">

        <input type="hidden" name="accion" value="nuevo_mix">
        <?php
        include('regreso-modal.php');
        ?>
        <div class="modal-body div1" id="cont_modal">
          <h1 class="modal-title">
            Va a crear un nuevo mix, ¿está seguro?
          </h1>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" name="mix" id="btnEnviar">
            Crear Nuevo Mix
          </button>
        </div>
      </form>
    </div>


  </div>
</div>
</div>