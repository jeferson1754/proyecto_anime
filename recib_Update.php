<!--cpmment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';
$idRegistros    = $_POST['id'];
$idEmision      = $_POST['emision'];
$idPendientes   = $_POST['pendientes'];
$nombre         = $_POST['anime'];
$temps          = $_POST['temps'];
$peli           = $_POST['peli'];
$spin           = $_POST['spin'];
$estado         = $_POST['estado'];
$fecha          = $_POST['fecha'];
$temp           = $_POST['temp'];
$link           = $_POST['link'];
$op             = $_POST['op'];
$ed             = $_POST['ed'];

if ($temp == 1) {
    $tempo = "Invierno";
} else if ($temp == 2) {
    $tempo = "Primavera";
} else if ($temp == 3) {
    $tempo = "Verano";
} else if ($temp == 4) {
    $tempo = "Otoño";
} else if ($temp == 5) {
    $tempo = "Desconocida";
}


$sql = ("SELECT * FROM `anime` where id='$idRegistros';");
$sql1 = ("SELECT * FROM `emision`  where ID_Emision='$idEmision'and ID_Emision>1;");
$sql2 = ("SELECT * FROM `pendientes`where ID_Pendientes='$idPendientes' and  ID_Pendientes>1;");
$sql4 = ("SELECT * FROM `num_horario` where Temporada='$tempo' and Ano='$fecha';");
$sql5 = ("SELECT * FROM `eliminados_emision` where Nombre='$nombre $temps' limit 1;");

//echo $sql5."<br>";

$anime              = mysqli_query($conexion, $sql);
$emision            = mysqli_query($conexion, $sql1);
$pendientes         = mysqli_query($conexion, $sql2);
$num                = mysqli_query($conexion, $sql4);
$eliminados_emision = mysqli_query($conexion, $sql5);

$opening = $conexion->query("SELECT COUNT(*) total FROM `op` where ID_Anime='$idRegistros';");

while ($valores = mysqli_fetch_array($opening)) {
    $op1 = $valores[0];
}

while ($valores = mysqli_fetch_array($num)) {
    $num_horario = $valores[0];
}


if ($estado != "Finalizado") {
    if (mysqli_num_rows($num) == 0) {
        echo "Horario No Existe<br> Hay que crearlo y buscar el num horario<br> ";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO num_horario (`Temporada`, `Ano`)
        VALUES ( '" . $tempo . "','" . $fecha . "')";
            $conn->exec($sql);
            $num_horario = $conn->lastInsertId();
            echo $sql . "<br>";
            echo 'Num_ Horario: ' . $num_horario;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }
        echo $sql3;
    } else {
        echo $sql4 . "<br>";
        echo "Horario Existe<br> No Hacer Nada<br> ";
        $query = $conexion->query($sql4);
        while ($valores = mysqli_fetch_array($query)) {
            $num_horario = $valores['Num'];
        }
    }

    /*Deberia buscar las varibales en horario en vez de emision*/
    while ($valores = mysqli_fetch_array($emision)) {
        $dia = $valores['Dia'];
        $duracion = $valores['Duracion'];
    }

    $sql3 = ("SELECT * FROM `horario` where Nombre='$nombre' AND num_horario='$num_horario' ;");
    $horario    = mysqli_query($conexion, $sql3);

    if (mysqli_num_rows($horario) == 0) {
        echo "No existe el anime en el horario,asi que lo creo:<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`num_horario`)
            VALUES ( '" . $nombre . " " . $temps . "', '" . $dias . "', '" . $duracion . "','" . $num_horario . "')";
            $conn->exec($sql);
            echo $sql . "<br>Demas<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e . "<br>" . $sql . "<br>";
        }
    } else {
        echo $sql3 . "<br>";
        echo "Si existe el anime en el horario,asi que nada:<br>Demas <br>";
    }
    echo $sql3;
    echo "<br>";
} else {
    echo "No hacer nada esta finalizado:<br>Demas <br>";
}
$ending = $conexion->query("SELECT COUNT(*) total FROM `ed` where ID_Anime='$idRegistros';");

while ($valores = mysqli_fetch_array($ending)) {
    $ed1 = $valores[0];
}

$mixes = $conexion->query("SELECT * FROM mix WHERE ID = (SELECT MAX(ID) FROM mix);");

while ($valores = mysqli_fetch_array($mixes)) {
    $mix = $valores[0];
}

$mix_ed = $conexion->query("SELECT * FROM mix_ed WHERE ID = (SELECT MAX(ID) FROM mix_ed);");

while ($valores = mysqli_fetch_array($mix_ed)) {
    $mix2 = $valores[0];
}


echo $sql1;
echo "<br>";
echo $sql2;
echo "<br>";
echo $sql4;
echo "<br>";
echo $sql5 . "<br>";
echo $idEmision;
echo "<br>";
echo $idPendientes;
echo "<br>OP-";
echo $op;
echo "<br>OP1-";
echo $op1;
echo "<br>ED-";
echo $ed;
echo "<br>ED1-";
echo $ed1;
echo "<br>";
echo $mix;
echo "<br>";
echo $mix2;
echo "<br>";
echo $link;
echo "<br> Temporada ID";
echo $temp;
echo "<br>";
echo $tempo;
echo "<br>";
echo "Num_Horario:" . $num_horario . "<br>";




if ($op > $op1) {
    echo "OP Mayor a Resultado";
    echo "<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO  op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`) 
        VALUES('" . $nombre . " " . $temps . "', '" . $idRegistros . "','" . $op . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix . "')";
        $conn->exec($sql);
        echo $sql;
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
} else {

    echo "Iguales";
    echo "<br>";
}

if ($ed > $ed1) {
    echo "OP Mayor a Resultado";
    echo "<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO  ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`) 
        VALUES('" . $nombre . " " . $temps . "', '" . $idRegistros . "','" . $ed . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix2 . "')";
        $conn->exec($sql);
        echo $sql;
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
} else {

    echo "Iguales";
    echo "<br>";
}
echo "<br>";

function DELETE_EMISION()
{

    include 'bd.php';
    $idEmision      = $_POST['emision'];

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM `emision` where ID_Emision='" . $idEmision . "'";
        $conn->exec($sql);
        $last_id1 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id1;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $sql;
        echo $e;
        echo "<br>";
    }
}

function DELETE_PENDIENTES()
{

    include 'bd.php';
    $idPendientes   = $_POST['pendientes'];

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM pendientes WHERE ID_Pendientes ='" . $idPendientes . "'";
        $conn->exec($sql);
        $last_id2 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id2;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
}

function UPDATE_ANIME_Solo()
{
    include 'bd.php';
    $nombre         = $_POST['anime'];
    $temps          = $_POST['temps'];
    $peli           = $_POST['peli'];
    $spin           = $_POST['spin'];
    $estado         = $_POST['estado'];
    $fecha          = $_POST['fecha'];
    $temp           = $_POST['temp'];
    $idRegistros    = $_POST['id'];

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE anime SET 
        Anime ='" . $nombre . "',
        Temporadas ='" . $temps . "',
        Peliculas ='" . $peli . "',
        Spin_Off ='" . $spin . "',
        Estado ='" . $estado . "',
       `id_Emision`=1,
       `id_Pendientes`=1,
        Ano ='" . $fecha . "',
        Id_Temporada ='" . $temp . "'
        WHERE id='" . $idRegistros . "'";
        $conn->exec($sql);
        $last_id3 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id3;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
    }
}

function UPDATE_ANIME_ID()
{

    include 'bd.php';
    $idCambiado    = $_POST['id2'];
    $nombre         = $_POST['anime'];
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE anime SET 
        id='" . $idCambiado . "'
        where Anime='" . $nombre . "'";
        $conn->exec($sql);
        $last_id4 = $conn->lastInsertId();
        echo $sql;
        echo 'ultimo anime insertado ' . $last_id4;
        echo "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
        echo $e;
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error al Actualizar ID de ' . $nombre . ' en Anime",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    }
}

//DELETE_EMISION();
//UPDATE_ANIME_Solo();
//DELETE_PENDIENTES();
//UPDATE_ANIME_ID();



if (mysqli_num_rows($emision) == 0) {

    echo "No existe en Emision";
    echo "<br>";
    if (mysqli_num_rows($pendientes) == 0) {
        echo "No existe en Pendientes";
        if ($estado === "Emision") {

            echo "<br>";
            echo "Estado Emision";
            echo "<br>";
            if (mysqli_num_rows($eliminados_emision) == 0) {
                echo "No existe en eliminados emision<br>";
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','Indefinido','00:24:00')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            } else {
                echo "Existe en eliminados emision<br>";
                while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
                    $dato1 = $mostrar['ID_Emision'];
                    $dato2 = $mostrar['Estado'];
                    $dato3 = $mostrar['Nombre'];
                    $dato4 = $mostrar['Capitulos'];
                    $dato5 = $mostrar['Totales'];
                    $dato6 = $mostrar['Dia'];
                    $dato8 = $mostrar['Duracion'];
                }
                echo "ELIMINADOS_EMISION<BR>";
                echo $dato1;
                echo "<br>";
                echo $dato2;
                echo "<br>";
                echo $dato3;
                echo "<br>";
                echo $dato4;
                echo "<br>";
                echo $dato5;
                echo "<br>";
                echo $dato6;
                echo "<br>";
                echo $dato8 . "<br>";

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`ID_Emision`,`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '$dato1','$dato2','$dato3','$dato4','$dato5','$dato6','$dato8')";
                    $conn->exec($sql);
                    $last_id1 = $dato1;
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
                
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "DELETE FROM `eliminados_emision` where ID_Emision='$dato1'";
                    $conn->exec($sql);
                    echo $sql;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }

            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET 
                Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $last_id1 . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . '  en Emision y Actualizando en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Finalizado") {
            echo "<br>";
            echo "Estado Finalizado";
            echo "<br>";

            UPDATE_ANIME_Solo();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . '  en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pausado") {
            echo "<br>";
            echo "Estado Pausado";
            echo "<br>";
            if (mysqli_num_rows($eliminados_emision) == 0) {
                echo "No existe en eliminados emision<br>";
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','Indefinido','00:24:00')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            } else {
                echo "Existe en eliminados emision<br>";
                while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
                    $dato1 = $mostrar['ID_Emision'];
                    $dato2 = $mostrar['Estado'];
                    $dato3 = $mostrar['Nombre'];
                    $dato4 = $mostrar['Capitulos'];
                    $dato5 = $mostrar['Totales'];
                    $dato6 = $mostrar['Dia'];
                    $dato8 = $mostrar['Duracion'];
                }
                echo "ELIMINADOS_EMISION<BR>";
                echo $dato1;
                echo "<br>";
                echo $dato2;
                echo "<br>";
                echo $dato3;
                echo "<br>";
                echo $dato4;
                echo "<br>";
                echo $dato5;
                echo "<br>";
                echo $dato6;
                echo "<br>";
                echo $dato8 . "<br>";

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`ID_Emision`,`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '$dato1','$dato2','$dato3','$dato4','$dato5','$dato6','$dato8')";
                    $conn->exec($sql);
                    $last_id1 = $dato1;
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "DELETE FROM `eliminados_emision` where ID_Emision='$dato1'";
                    $conn->exec($sql);
                    echo $sql;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $last_id1 . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . ' en Pausado y Actualizando en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pendiente") {
            echo "<br>";
            echo "Estado Pendiente";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO pendientes (`Nombre`,`Tipo`, `Vistos`, `Total`) 
                VALUES ( '" . $nombre . " " . $temps . "','Anime','1','12')";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos) where Vistos > 0;";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`=1,
               `id_Pendientes`='" . $last_id1 . "',
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . ' en Pendiente y Actualizando en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        }
    } else {
        echo "Existe en Pendientes";
        if ($estado === "Emision") {
            echo "<br>";
            echo "Estado Emision";
            echo "<br>";
            if (mysqli_num_rows($eliminados_emision) == 0) {
                echo "No existe en eliminados emision<br>";
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','Indefinido','00:24:00')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            } else {
                echo "Existe en eliminados emision<br>";
                while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
                    $dato1 = $mostrar['ID_Emision'];
                    $dato2 = $mostrar['Estado'];
                    $dato3 = $mostrar['Nombre'];
                    $dato4 = $mostrar['Capitulos'];
                    $dato5 = $mostrar['Totales'];
                    $dato6 = $mostrar['Dia'];
                    $dato8 = $mostrar['Duracion'];
                }
                echo "ELIMINADOS_EMISION<BR>";
                echo $dato1;
                echo "<br>";
                echo $dato2;
                echo "<br>";
                echo $dato3;
                echo "<br>";
                echo $dato4;
                echo "<br>";
                echo $dato5;
                echo "<br>";
                echo $dato6;
                echo "<br>";
                echo $dato8 . "<br>";

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`ID_Emision`,`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '$dato1','$dato2','$dato3','$dato4','$dato5','$dato6','$dato8')";
                    $conn->exec($sql);
                    $last_id1 = $dato1;
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "DELETE FROM `eliminados_emision` where ID_Emision='$dato1'";
                    $conn->exec($sql);
                    echo $sql;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $last_id1 . "',
               `id_Pendientes`=1,
                Ano ='" . $fecha . "',
                Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_PENDIENTES();

            UPDATE_ANIME_ID();

            //Eliminar en Pendientes
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . ' en Emision,Actualizando en Anime y Eliminando en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Finalizado") {
            echo "<br>";
            echo "Estado Finalizado";
            echo "<br>";

            UPDATE_ANIME_Solo();
            DELETE_PENDIENTES();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime y Eliminando en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pausado") {
            echo "<br>";
            echo "Estado Pausado";
            echo "<br>";

            if (mysqli_num_rows($eliminados_emision) == 0) {
                echo "No existe en eliminados emision<br>";
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','Indefinido','00:24:00')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            } else {
                echo "Existe en eliminados emision<br>";
                while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
                    $dato1 = $mostrar['ID_Emision'];
                    $dato2 = $mostrar['Estado'];
                    $dato3 = $mostrar['Nombre'];
                    $dato4 = $mostrar['Capitulos'];
                    $dato5 = $mostrar['Totales'];
                    $dato6 = $mostrar['Dia'];
                    $dato8 = $mostrar['Duracion'];
                }
                echo "ELIMINADOS_EMISION<BR>";
                echo $dato1;
                echo "<br>";
                echo $dato2;
                echo "<br>";
                echo $dato3;
                echo "<br>";
                echo $dato4;
                echo "<br>";
                echo $dato5;
                echo "<br>";
                echo $dato6;
                echo "<br>";
                echo $dato8 . "<br>";

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`ID_Emision`,`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '$dato1','$dato2','$dato3','$dato4','$dato5','$dato6','$dato8')";
                    $conn->exec($sql);
                    $last_id1 = $dato1;
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }

                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "DELETE FROM `eliminados_emision` where ID_Emision='$dato1'";
                    $conn->exec($sql);
                    echo $sql;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $last_id1 . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_PENDIENTES();
            UPDATE_ANIME_ID();

            //Eliminar Pendiente
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Creando registro de ' . $nombre . ' en Emision,Actualizando en Anime y Eliminando en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pendiente") {
            echo "<br>";
            echo "Estado Pendiente";
            echo "<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Nombre ='" . $nombre . " " . $temps . "', Tipo='Anime'
                WHERE ID_Pendientes='" . $idPendientes . "' and ID_Pendientes>1";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`=1,
               `id_Pendientes`='" . $idPendientes . "',
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos);";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();


            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando Registro de ' . $nombre . ' en Anime y Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        }
    }
} else {
    echo "Si existe en Emision";
    echo "<br>";
    if (mysqli_num_rows($pendientes) == 0) {
        echo "No existe en Pendientes";
        if ($estado === "Emision") {
            echo "<br>";
            echo "Estado Emision";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $idEmision . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE emision SET Nombre ='" . $nombre . " " . $temps . "',Emision='" . $estado . "'
                WHERE ID_Emision='" . $idEmision . "'and ID_Emision>1";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime y Emision ",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Finalizado") {
            echo "<br>";
            echo "Estado Finalizado";
            echo "<br>";
            UPDATE_ANIME_Solo();
            DELETE_EMISION();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime y Eliminando en Emision",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pausado") {

            echo "<br>";
            echo "Estado Pausado";
            echo "<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $idEmision . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE emision SET Nombre ='" . $nombre . " " . $temps . "',Emision='" . $estado . "'
                WHERE ID_Emision='" . $idEmision . "'and ID_Emision>1";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            UPDATE_ANIME_ID();


            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime y en Pausado",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pendiente") {

            echo "<br>";
            echo "Estado Pendiente";
            echo "<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //INSERT INTO `pendientes`(`ID`, `Nombre`, `Vistos`, `Total`, `Pendientes`, `Link`)
                $sql = "INSERT INTO pendientes (`Nombre`,`Tipo`, `Vistos`, `Total`) 
                VALUES ( '" . $nombre . " " . $temps . "','Anime','1','12')";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo "<br>";
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`=1,
               `id_Pendientes`='" . $last_id1 . "',
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo "<br>";
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos)";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_EMISION();
            UPDATE_ANIME_ID();



            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime,Eliminado en Emision y Creando en Pendiente",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        }
    } else {
        echo "Existe en Pendientes";

        if ($estado === "Emision") {
            echo "<br>";
            echo "Estado Emision";
            echo "<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $idEmision . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE emision SET Nombre ='" . $nombre . " " . $temps . "',Emision='" . $estado . "'
                WHERE ID_Emision='" . $idEmision . "'and ID_Emision>1";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_PENDIENTES();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Anime,Emision y Eliminando en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Finalizado") {
            echo "<br>";
            echo "Estado Finalizado";
            echo "<br>";
            //Try catch para eliminar emision y  Actualizar Anime
            UPDATE_ANIME_Solo();
            DELETE_EMISION();
            DELETE_PENDIENTES();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Eliminado Registro de ' . $nombre . ' en Emision y Pendientes, Actualizando en Anime",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pausado") {

            echo "<br>";
            echo "Estado Pausado";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`='" . $idEmision . "',
               `id_Pendientes`=1,
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE emision SET Emision ='" . $estado . "',
                Nombre ='" . $nombre . " " . $temps . "'
                WHERE ID_Emision='" . $idEmision . "'and ID_Emision>1";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_PENDIENTES();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . 'en Anime y Emision, Eliminando en Pendientes",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        } else if ($estado === "Pendiente") {
            echo "<br>";
            echo "Estado Pendiente";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Nombre ='" . $nombre . " " . $temps . "', Tipo='Anime'
                WHERE ID_Pendientes='" . $idPendientes . "' and ID_Pendientes>1";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                echo $sql;
                $conn = null;
                echo $e;
                echo "<br>";
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
               `id_Emision`=1,
               `id_Pendientes`='" . $idPendientes . "',
               Ano ='" . $fecha . "',
               Id_Temporada ='" . $temp . "'
                WHERE id='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo "<br>";
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total- Vistos)";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            DELETE_EMISION();
            UPDATE_ANIME_ID();

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro  de ' . $nombre . ' en Anime, en Pendientes y Eliminado en Emision",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "' . $link . '";
            });
            </script>';
        }
    }
}

