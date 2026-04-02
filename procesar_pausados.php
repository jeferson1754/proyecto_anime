<?php
include("bd.php"); // Asegúrate de incluir tu conexión

if (isset($_POST['ids']) && $_POST['accion'] == 'posponer_un_mes') {
    // Limpiamos los IDs para seguridad (solo números y comas)
    $ids = preg_replace('/[^0-9,]/', '', $_POST['ids']);

    if (!empty($ids)) {
        // Truco: Seteamos la fecha a "Hoy menos 2 meses". 
        // Así, en 30 días más, el sistema detectará que pasaron 3 meses y avisará otra vez.
        $sql = "UPDATE anime 
                SET Fecha_Modificacion = DATE_SUB(NOW(), INTERVAL 2 MONTH) 
                WHERE id IN ($ids)";

        if (mysqli_query($conexion, $sql)) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
