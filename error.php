<?php
include 'bd.php';
$sql1 = ("SELECT COUNT(dia),Dia FROM Emision where dia='Domingo' and ID_Emision>1;");
//$validar      = mysqli_query($conexion, $sql1);
/*
while ($rows = mysqli_fetch_array($validar)) {

    echo "cantidad de dias domingo: " . $rows[0] . "<br>";
    echo "cantidad de dias domingo: " . $rows[1] . "<br>";
}
*/



for($i=1;$i<=10;$i++){

    
    $sql2=("UPDATE tabla1 SET columna_data1='" . $i . "' WHERE columna_id1='" . $i . "' AND columna_id2='6000';");
    //mysqli_query($conexion,"UPDATE tabla1 SET columna_data1='5000'" . $id ." WHERE columna_id1='50001' AND columna_id2=6000;");
    echo $sql2;
    echo "<br>";
}
    
?>