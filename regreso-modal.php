<?php
if (isset($_GET['borrar'])) {
   $link = "./?estado=&accion=Filtro&borrar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['filtrar'])) {
   $link = "./?estado=$estado&accion=Filtro&filtrar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else if (isset($_GET['buscar'])) {
   $link = "./?busqueda_anime=$busqueda&estado=$estado&temporada=$temporada&buscar=";
   echo "<input type='hidden' name='link' value='$link'>";
} else {
   $link = "./";
   echo "<input type='hidden' name='link' value='$link'>";
}
