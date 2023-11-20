<form name="form-data" action="recibCliente.php" method="POST">

    <div class="row">
        <div class="col-md-12">
            <label for="name" class="form-label">Nombre del Anime</label>
            <input type="text" class="form-control" name="nombre" required='true' autofocus>
        </div>
        <div class="col-md-12 mt-2">
            <label for="temps" class="form-label">Ultima Temporada </label>
            <input type="text" class="form-control" name="temps" required='true' autofocus>
        </div>
        <div class="col-md-12 mt-2">
            <label for="peli" class="form-label">Peliculas</label>
            <input type="numvber" class="form-control" name="peli" required='true'>
        </div>
        <div class="col-md-12 mt-2">
            <label for="spin" class="form-label">Spin-Off</label>
            <input type="numvber" class="form-control" name="spin" required='true'>
        </div>
        <div class="col-md-12 mt-2">
            <label for="estado" class="form-label">Estado</label>

            <select name="estado" class="form-control">
                <option value="0">Seleccione:</option>
                <?php
                $query = $conexion->query("SELECT ID FROM `estado`;");
                while ($valores = mysqli_fetch_array($query)) {
                    echo '<option value="' . $valores['ID'] . '">' . $valores['ID'] . '</option>';
                }
                ?>
            </select>

        </div>

        <div class="col-md-12 mt-2">
            <label for="fecha" class="form-label">Año</label>
            <input type="date" class="form-control" name="fecha" required='true'>
        </div>



        <div class="col-md-12 mt-2">
            <label for="temp" class="form-label">Temporada</label>

            <select name="temp" class="form-control">
                <option value="0">Seleccione:</option>
                <?php
                $query = $conexion->query("SELECT ID,temporada FROM `temporada`;");
                while ($valores = mysqli_fetch_array($query)) {
                    echo '<option value="' . $valores['ID'] . '">' . $valores['temporada'] . '</option>';
                }
                ?>
            </select>

        </div>

    </div>
    <div class="row justify-content-start text-center mt-5">
        <div class="col-12">
            <button class="btn btn-primary btn-block" id="btnEnviar">
                Registrar Cliente
            </button>
        </div>
    </div>
</form>