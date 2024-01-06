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
      include('./ED/regreso-modal.php');

      $ed = $conexion->query("SELECT Anime FROM `anime` WHERE id='$mostrar[ID_Anime]';");

      while ($valores = mysqli_fetch_array($ed)) {
        $album = $valores[0];
      }
      ?>
      <div class="modal-body div1" id="cont_modal">
        <h1 class="modal-title">
          Titulo: <?php echo $mostrar['Cancion']; ?>
        </h1>
        <h3 class="modal-body">
          
          Artista: <?php
          if($mostrar['Autor']==""){
            echo $mostrar['Nombre'] ." ED ". $mostrar['Ending']; 
          }else{
            echo $mostrar['Autor'];
          }
          ?>  
          <br>
          Album: <?php echo $album; ?>
        </h3>

      </div>

    </div>
  </div>
</div>
<!---fin ventana Update --->