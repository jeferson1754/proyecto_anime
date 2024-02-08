<?php
if (isset($_GET['borrar'])) {
    $link = "./?tipo=&borrar=&accion=Filtro";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['filtrar'])) {
    $link = "./?tipo=$tipo&filtrar=&accion=Filtro";
    echo "<input type='hidden' name='link' value='$link'>";
 }else if (isset($_GET['link'])) {
    $link = "./?link=";
    echo "<input type='hidden' name='link' value='$link'>";
 }else{
    $link = "./";
    echo "<input type='hidden' name='link' value='$link'>";
 }
