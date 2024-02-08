<?php
if (isset($_GET['borrar'])) {
   $link = "./?busqueda_ed=&borrar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['buscar'])) {
   $link = "./?busqueda_ed=$busqueda&buscar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['filtrar'])) {
   $link = "./?estado=$estado&accion=Filtro&filtrar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['link'])) {
   $link = "./?link=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['nombre'])) {
   $link = "./?nombre=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['buscar1'])) {
   $link = "./?busqueda_cancion_ed=$busqueda&buscar1=";
   echo "<input type='hidden' name='link' value='$link'>";
} else {
   $link = "./";
   echo "<input type='hidden' name='link' value='$link'>";
}
