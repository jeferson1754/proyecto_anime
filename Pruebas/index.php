<!DOCTYPE html>
<html>

<head>
    <title>Selección condicional de selects</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function habilitarSelect2() {
            var select1 = document.getElementsByName("anis")[0];
            var select2 = document.getElementsByName("estado")[0];

            if (select1.value !== "") {
                $.ajax({
                    type: "POST",
                    url: "procesar_consulta.php", // Ruta del script PHP que procesa la consulta
                    data: {
                        anio: select1.value
                    }, // Enviar el valor seleccionado al servidor
                    dataType: "json",
                    success: function(response) {
                        select2.disabled = false;
                        select2.innerHTML = ""; // Limpiar las opciones existentes en select2

                        // Llenar select2 con los resultados de la consulta
                        for (var i = 0; i < response.length; i++) {
                            var option = document.createElement("option");
                            option.value = response[i].valor;
                            option.text = response[i].texto;
                            select2.appendChild(option);
                        }
                    },
                    error: function() {
                        console.log("Error en la solicitud AJAX");
                    }
                });
            } else {
                select2.disabled = true;
                select2.innerHTML = "<option value=''>Seleccione:</option>";
            }
        }
    </script>
</head>

<body>

    <form action="" method="POST">
        <label>Año</label>
        <select name="anis" class="form-control" style="width:auto;" onchange="habilitarSelect2()">
            <option value="">Seleccione:</option>
            <!-- ... Opciones de select1 aquí ... -->
        </select>

        <label>Temporada</label>
        <select name="estado" class="form-control" style="width:auto;" disabled>
            <option value="">Seleccione:</option>
            <!-- ... Opciones de select2 aquí ... -->
        </select>

        <button class="btn btn-outline-info" type="submit" name="filtrar"><b>Filtrar</b></button>
        <button class="btn btn-outline-info" type="submit" name="borrar"><b>Borrar</b></button>
    </form>

</body>

</html>