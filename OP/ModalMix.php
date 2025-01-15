<!DOCTYPE html>
<html>

<head>
  <style>

  </style>
</head>

<body>

  <div class="modal fade modal-custom" id="NuevoMix" tabindex="-1" role="dialog" aria-labelledby="modalNuevoMix" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modalNuevoMix">
            Nuevo Mix
          </h6>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form name="form-data" action="recib_Delete-OP.php" method="POST">
          <input type="hidden" name="accion" value="nuevo_mix">
          <?php include('regreso-modal.php'); ?>

          <div class="modal-body">
            <h1>
              Va a crear un nuevo mix, ¿está seguro?
            </h1>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="mix" id="btnEnviar">
              Crear Nuevo Mix
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>