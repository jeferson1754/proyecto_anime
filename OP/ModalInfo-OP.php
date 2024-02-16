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

        .buttons-container {
          display: flex;
          justify-content: center;
          /* Centrar horizontalmente */
        }

        .buttons-container button {
          width: auto;
          height: auto;
          margin: 0 5px;
          margin-bottom: 20px;
          /* Espacio entre los botones */
        }

        i {
          font-size: 40px;
        }
      </style>

      <?php
      include('regreso-modal.php');

      $op = $conexion->query("SELECT Anime FROM `anime` WHERE id='$mostrar[ID_Anime]';");

      while ($valores = mysqli_fetch_array($op)) {
        $album = $valores[0];
      }

      ?>
      <div class="modal-body div1" id="cont_modal 1">
        <h1 class="modal-title">
          Titulo: <h1 lass="modal-title2"><?php echo $mostrar['Cancion']; ?></h1>
        </h1>

        <h3 class="modal-body">

          Artista:
          <?php

          $sql1 = "SELECT autor.Autor, ((SELECT COUNT(*) FROM op WHERE op.ID_Autor = autor.ID) + (SELECT COUNT(*) FROM ed WHERE ed.ID_Autor = autor.ID)) AS Repeticiones FROM autor where Autor='$mostrar[Autor]' and Autor !='' HAVING Repeticiones > 3;";
          $result1 = $conexion->query($sql1);

          if ($result1->num_rows > 0) {
            echo $mostrar['Autor'];
          } else {
            echo $mostrar['Nombre'] . " OP " . $mostrar['Opening'];
          }

          ?>
          <br>
          Album: <?php echo $album; ?>
        </h3>

      </div>

      <div class="container" style="width: 100%; height: 100px;">
        <iframe src="./ejemplo.php?id=<?php echo $id_Registros; ?>" frameborder="0" ñ style="width: 100%; height: 100%;"></iframe>
      </div>


      <script>
        function copyToClipboard(text) {
          var textarea = document.createElement("textarea");
          textarea.value = text;
          document.body.appendChild(textarea);
          textarea.select();
          document.execCommand("copy");
          document.body.removeChild(textarea);
          alert("Texto copiado al portapapeles: " + text);
        }
      </script>



    </div>
  </div>
</div>

<!---fin ventana Update --->