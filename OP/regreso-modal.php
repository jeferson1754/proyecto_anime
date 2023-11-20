<?php
if (isset($_GET['borrar'])) {
    $link = "../openings.php?busqueda=&borrar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['buscar'])) {
    $link = "../openings.php?busqueda=$busqueda&buscar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['filtrar'])) {
    $link = "../openings.php?estado=$estado&accion=Filtro&filtrar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['link'])) {
    $link = "../openings.php?link=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['nombre'])) {
    $link = "../openings.php?nombre=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else{
    $link = "../openings.php";
    echo "<input type='hidden' name='link' value='$link'>";
 }
