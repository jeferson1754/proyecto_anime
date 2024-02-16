<!--cpmment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include 'bd.php';

// Variables
$idRegistros = $_POST['id'];
$idEmision = $_POST['emision'];
$idPendientes = $_POST['pendientes'];
$nombre = $_POST['anime'];
$temps = $_POST['temps'];
$peli = $_POST['peli'];
$spin = $_POST['spin'];
$estado = $_POST['estado'];
$fecha = $_POST['fecha'];
$temp = $_POST['temp'];
$link = $_POST['link'];
$op = $_POST['op'];
$ed = $_POST['ed'];

// Mapeo de valores de temporada
$temporadas = [
    1 => "Invierno",
    2 => "Primavera",
    3 => "Verano",
    4 => "Otoño",
    5 => "Desconocida"
];

// Obtener el nombre de la temporada
$tempo = isset($temporadas[$temp]) ? $temporadas[$temp] : "Desconocida";



$sql = "SELECT * FROM `anime` WHERE id='$idRegistros'";
$sql1 = "SELECT * FROM `emision` WHERE ID_Emision='$idEmision' AND ID_Emision>1";
$sql2 = "SELECT * FROM `pendientes` WHERE ID_Pendientes='$idPendientes' AND ID_Pendientes>1";
$sql4 = "SELECT * FROM `num_horario` WHERE Temporada='$tempo' AND Ano='$fecha'";
$sql5 = "SELECT * FROM `eliminados_emision` WHERE Nombre='$nombre $temps' LIMIT 1";

$anime = mysqli_query($conexion, $sql);
$emision = mysqli_query($conexion, $sql1);
$pendientes = mysqli_query($conexion, $sql2);
$num = mysqli_query($conexion, $sql4);
$eliminados_emision = mysqli_query($conexion, $sql5);


while ($mostrar = mysqli_fetch_array($eliminados_emision)) {
    $id_emision = $mostrar['ID_Emision'];
}

$opening = $conexion->query("SELECT COUNT(*) total FROM `op` WHERE ID_Anime='$idRegistros'");
$num_horario = null;

if ($num) {
    $valores = mysqli_fetch_array($num);
    $num_horario = $valores[0];
}

$op1 = null;

if ($opening) {
    $valores = mysqli_fetch_array($opening);
    $op1 = $valores['total'];
}



if ($estado != "Finalizado") {
    if (mysqli_num_rows($num) == 0) {
        echo "Horario No Existe<br> Hay que crearlo y buscar el num horario<br> ";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO num_horario (`Temporada`, `Ano`)
        VALUES ( '$tempo','$fecha')";
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



    $sql3 = "SELECT * FROM `horario` where Nombre='$nombre' AND num_horario='$num_horario' ";
    $horario = mysqli_query($conexion, $sql3);

    /*Deberia buscar las variables en horario en vez de emision*/
    $sql6 = "SELECT * FROM `horario` where Nombre='$nombre' ORDER BY `horario`.`num_horario` DESC limit 1";
    $info = mysqli_query($conexion, $sql6);
    echo $sql6 . "<br>";

    while ($valores = mysqli_fetch_array($info)) {
        $dia = $valores['Dia'];
        $duracion = $valores['Duracion'];
    }

    if (mysqli_num_rows($horario) == 0) {
        echo "No existe el anime en el horario, asi que lo creo:<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`num_horario`)
            VALUES ( '" . $nombre . " " . $temps . "', '$dia', '$duracion','$num_horario')";
            $conn->exec($sql);
            echo $sql . "<br>Demas<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e . "<br>" . $sql . "<br>";
        }
    } else {
        echo $sql3 . "<br>";
        echo "Si existe el anime en el horario, asi que nada:<br>Demas <br>";
    }
    echo $sql3;
    echo "<br>";
} else {
    echo "No hacer nada esta finalizado:<br>Demas <br>";
}

// Obtener el total de endings para el anime
$ending = $conexion->query("SELECT COUNT(*) total FROM `ed` WHERE ID_Anime='$idRegistros';");
$ed1 = mysqli_fetch_array($ending)[0];

// Obtener el mix más reciente
$mixes = $conexion->query("SELECT * FROM mix WHERE ID = (SELECT MAX(ID) FROM mix);");
$mix = mysqli_fetch_array($mixes)[0];

// Obtener el mix de ending más reciente
$mix_ed = $conexion->query("SELECT * FROM mix_ed WHERE ID = (SELECT MAX(ID) FROM mix_ed);");
$mix2 = mysqli_fetch_array($mix_ed)[0];

// Mostrar información para depuración
echo $sql1 . "<br>";
echo $sql2 . "<br>";
echo $sql4 . "<br>";
echo $sql5 . "<br>";
echo $idEmision . "<br>";
echo $idPendientes . "<br>OP-" . $op . "<br>OP1-" . $op1 . "<br>ED-" . $ed . "<br>ED1-" . $ed1 . "<br>";
echo $mix . "<br>";
echo $mix2 . "<br>";
echo $link . "<br> Temporada ID" . $temp . "<br>" . $tempo . "<br>";
echo "Num_Horario:" . $num_horario . "<br>";


if ($op > $op1) {
    echo "OP Mayor a Resultado<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO  op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`,`Fecha_Ingreso`) 
        VALUES('" . $nombre . " " . $temps . "', '" . $idRegistros . "','" . $op . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix . "',NOW())";
        $conn->exec($sql);
        echo $sql;
    } catch (PDOException $e) {
        echo $e;
    } finally {
        $conn = null;
    }
} else {
    echo "Iguales<br>";
}

if ($ed > $ed1) {
    echo "ED Mayor a Resultado<br>";
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO  ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`,`Fecha_Ingreso`) 
        VALUES('" . $nombre . " " . $temps . "', '" . $idRegistros . "','" . $ed . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix2 . "',NOW())";
        $conn->exec($sql);
        echo $sql;
    } catch (PDOException $e) {
        echo $e;
    } finally {
        $conn = null;
    }
} else {
    echo "Iguales<br>";
}
echo "<br>";


function ejecutarConsulta($sql)
{
    global $servidor, $basededatos, $usuario, $password;

    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec($sql);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        echo $e;
        return false;
    } finally {
        $conn = null;
    }
}

function DELETE_EMISION()
{
    global $servidor, $basededatos, $usuario, $password;

    $idEmision = $_POST['emision'];
    $sql = "DELETE FROM `emision` WHERE ID_Emision = '$idEmision'";
    $last_id1 = ejecutarConsulta($sql);
    if ($last_id1 !== false) {
        echo $sql;
        echo 'último anime insertado ' . $last_id1 . "<br>";
    }
}

function DELETE_PENDIENTES()
{
    global $servidor, $basededatos, $usuario, $password;

    $idPendientes = $_POST['pendientes'];
    $sql = "DELETE FROM `pendientes` WHERE ID_Pendientes = '$idPendientes'";
    $last_id2 = ejecutarConsulta($sql);
    if ($last_id2 !== false) {
        echo $sql;
        echo 'último anime insertado ' . $last_id2 . "<br>";
    }
}

function UPDATE_ANIME_Solo()
{
    global $servidor, $basededatos, $usuario, $password;

    $nombre = $_POST['anime'];
    $temps = $_POST['temps'];
    $peli = $_POST['peli'];
    $spin = $_POST['spin'];
    $estado = $_POST['estado'];
    $fecha = $_POST['fecha'];
    $temp = $_POST['temp'];
    $idRegistros = $_POST['id'];

    $sql = "UPDATE anime SET 
        Anime = '$nombre',
        Temporadas = '$temps',
        Peliculas = '$peli',
        Spin_Off = '$spin',
        Estado = '$estado',
        id_Emision = 1,
        id_Pendientes = 1,
        Ano = '$fecha',
        Id_Temporada = '$temp'
        WHERE id = '$idRegistros'";
    $last_id3 = ejecutarConsulta($sql);
    if ($last_id3 !== false) {
        echo $sql;
        echo 'último anime insertado ' . $last_id3 . "<br>";
    }
}

function UPDATE_ANIME_ID()
{
    global $servidor, $basededatos, $usuario, $password;

    $idCambiado = $_POST['id2'];
    $nombre = $_POST['anime'];

    $sql = "UPDATE anime SET 
        id = '$idCambiado'
        WHERE Anime = '$nombre'";
    $last_id4 = ejecutarConsulta($sql);
    if ($last_id4 !== false) {
        echo $sql;
        echo 'último anime insertado ' . $last_id4 . "<br>";
    }
}



if (mysqli_num_rows($emision) == 0) {

    echo "No existe en Emision";
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
                $sql = "UPDATE anime SET 
                Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
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

            if (mysqli_num_rows($eliminados_emision) == 0) {
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Creando registro de ' . $nombre . '  en Emision y Actualizando en Anime",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "' . $link . '";
                });
                </script>';
            } else {
                echo "Existe en eliminados emision<br>";
                echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "El anime ' . $nombre . ' tiene registros en Eliminados de Emision",
                    text:"Quiere usarlos?",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "./update_swal.php?variable=' . urlencode($id_emision) . '";
                });
                </script>';
            }
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
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','" . $dia . "','" . $duracion . "')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Creando registro de ' . $nombre . ' en Pausado y Actualizando en Anime",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "' . $link . '";
                });
                </script>';
            } else {
                echo "Existe en eliminados emision<br>";
                echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "El anime ' . $nombre . ' tiene registros en Eliminados de Emision",
                    text:"Quiere usarlos?",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "./update_swal.php?variable=' . urlencode($id_emision) . '";
                });
                </script>';
            }


            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE anime SET Anime ='" . $nombre . "',
                Temporadas ='" . $temps . "',
                Peliculas ='" . $peli . "',
                Spin_Off ='" . $spin . "',
                Estado ='" . $estado . "',
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
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','" . $dia . "','" . $duracion . "')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Creando registro de ' . $nombre . ' en Emision,Actualizando en Anime y Eliminando en Pendientes",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "' . $link . '";
                });
                </script>';
            } else {
                echo "Existe en eliminados emision<br>";
                echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "El anime ' . $nombre . ' tiene registros en Eliminados de Emision",
                    text:"Quiere usarlos?",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "./update_swal.php?variable=' . urlencode($id_emision) . '";
                });
                </script>';
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
                try {
                    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
                    VALUES ( '" . $estado . "','" . $nombre . " " . $temps . "','1','12','" . $dia . "','" . $duracion . "')";
                    $conn->exec($sql);
                    $last_id1 = $conn->lastInsertId();
                    echo $sql;
                    echo 'ultimo anime insertado ' . $last_id1;
                    echo "<br>";
                    $conn = null;
                } catch (PDOException $e) {
                    $conn = null;
                }
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Creando registro de ' . $nombre . ' en Emision,Actualizando en Anime y Eliminando en Pendientes",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "' . $link . '";
                });
                </script>';
            } else {
                echo "Existe en eliminados emision<br>";
                echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "El anime ' . $nombre . ' tiene registros en Eliminados de Emision",
                    text:"Quiere usarlos?",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "./update_swal.php?variable=' . urlencode($id_emision) . '";
                });
                </script>';
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
