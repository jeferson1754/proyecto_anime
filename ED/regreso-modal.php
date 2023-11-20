<?php
if (isset($_GET['borrar'])) {
    $link = "../endings.php?busqueda=&borrar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['buscar'])) {
    $link = "../endings.php?busqueda=$busqueda&buscar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['filtrar'])) {
    $link = "../endings.php?estado=$estado&accion=Filtro&filtrar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['link'])) {
    $link = "../endings.php?link=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['nombre'])) {
    $link = "../endings.php?nombre=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else{
    $link = "../endings.php";
    echo "<input type='hidden' name='link' value='$link'>";
 }
