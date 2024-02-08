<!--ventana para Update--->
<div class="modal fade" id="editpeli1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Nueva Pelicula
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php

      $peli1 = 0;

      // Ejecutar la consulta
      $peli = "SELECT ID FROM id_peliculas WHERE ID NOT IN (SELECT ID FROM peliculas)ORDER BY `id_peliculas`.`ID` ASC LIMIT 1;";
      $result = mysqli_query($conexion, $peli);

      // Verificar si hay resultados
      if ($result->num_rows > 0) {
        // Obtener el resultado de la primera fila
        $row = $result->fetch_assoc();
        // Asignar el valor de la columna ID a la variable $ani
        $peli1 = $row["ID"];
      }
      //echo $peli1;
      ?>

      <form name="form-data" action="recibCliente-Peli.php" method="POST">

        <div class="modal-body" id="cont_modal">

          <input type="hidden" min="1" value="<?php echo $peli1 ?>" name="id" class="form-control">


          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre de la Pelicula:</label>
            <input type="text" name="nombre" class="form-control" required="true">
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estado:</label>
            <select name="estado" class="form-control" required>
              <option value="">Seleccione:</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Pendiente">Pendiente</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Año:</label>
            <input type="number" name="fecha" min="1900" max="<?php echo $año ?>" class="form-control" value="<?php echo $año ?>" required="true">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnEnviar">
            Registrar Pelicula
          </button>
        </div>
    </div>
    </form>

  </div>
</div>
</div>
<!---fin ventana Update --->