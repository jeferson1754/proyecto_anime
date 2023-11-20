<!--ventana para Update--->
<div class="modal fade" id="info<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Info de la Cancion
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

      <?php
      include('./OP/regreso-modal.php');

      $op = $conexion->query("SELECT Anime FROM `anime` WHERE id='$mostrar[ID_Anime]';");

      while ($valores = mysqli_fetch_array($op)) {
        $album = $valores[0];
      }

      ?>
      <div class="modal-body div1" id="cont_modal">
        <h1 class="modal-title">
          Titulo: <h1 lass="modal-title2" ><?php echo $mostrar['Cancion']; ?></h1>
        </h1>
  
        <h3 class="modal-body">
          Artista: <?php echo $mostrar['Nombre']; ?> OP <?php echo $mostrar['Opening']; ?>(o Autor)
          Album: <?php echo $album; ?>
        </h3>

      </div>

    </div>
  </div>
</div>

<!---fin ventana Update --->