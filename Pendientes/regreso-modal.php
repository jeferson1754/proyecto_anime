<?php
if (isset($_GET['borrar'])) {
    $link = "../pendientes.php?busqueda=&borrar=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['filtrar'])) {
    $link = "../pendientes.php?tipo=$tipo&filtrar=&accion=Filtro";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['link'])) {
    $link = "../pendientes.php?link=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else{
    $link = "../pendientes.php";
    echo "<input type='hidden' name='link' value='$link'>";
 }
