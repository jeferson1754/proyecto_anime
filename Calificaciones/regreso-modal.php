<?php
if (isset($_GET['sin_calificar'])) //BOTON HOY
{
   $link = "./?sin_calificar=";
   echo "<input type='hidden' name='link' value='  $link  '>";
} else //BOTON BORRAR Y DEMAS 
{
   $link = "./";
   echo "<input type='hidden' name='link' value='  $link  '>";
}