<?php
if (isset($_GET['enviar'])) //BOTON HOY
{
   $accion1 = $_REQUEST['accion'];
   echo "<input type='hidden' name='accion' value='  $accion1  '>";
   $link = "./?enviar=&accion=HOY";
   echo "<input type='hidden' name='link' value='  $link  '>";
} else if (isset($_GET['enviar2'])) //FILTRAR POR DIA
{
   $accion2 = $_REQUEST['accion'];
   echo "<input type='hidden' name='accion' value='  $accion2 '>";
   $link = "./?dias=$dia&enviar2=&accion=Filtro";
   echo "<input type='hidden' name='link' value='  $link  '>";
} else //BOTON BORRAR Y DEMAS 
{
   $accion2 = "nose";
   echo "<input type='hidden' name='accion' value='  $accion2  '>";
   $link = "./?borrar=&accion=HOY";
   echo "<input type='hidden' name='link' value='  $link  '>";
}
