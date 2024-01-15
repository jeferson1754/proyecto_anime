<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include('bd.php');
$id         = $_REQUEST['id'];
$nombre     = $_REQUEST['anime'];
$estado     = $_REQUEST['estado'];
$temp       = $_REQUEST['temp'];
$fecha      = $_REQUEST['fecha'];
$dia_emision = $_REQUEST['dias'];
$link       = $_REQUEST['link'];

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


$sql = ("SELECT * FROM `anime` where Anime='$nombre';");
$sql1 = ("SELECT * FROM `emision` where Nombre ='$nombre';");
$sql2 = ("SELECT * FROM `pendientes`where Nombre='$nombre';");
$sql3 = ("SELECT * FROM `num_horario` where Temporada='$tempo' and Ano='$fecha' ;");

$anime      = mysqli_query($conexion, $sql);
$emision    = mysqli_query($conexion, $sql1);
$pendientes = mysqli_query($conexion, $sql2);
$horario    = mysqli_query($conexion, $sql3);

$mixes = $conexion->query("SELECT * FROM mix WHERE ID = (SELECT MAX(ID) FROM mix);");

while ($valores = mysqli_fetch_array($mixes)) {
    $mix = $valores[0];
}

$mix_ed = $conexion->query("SELECT * FROM mix_ed WHERE ID = (SELECT MAX(ID) FROM mix_ed);");

while ($valores = mysqli_fetch_array($mix_ed)) {
    $mix2 = $valores[0];
}




if ($estado = "Emision") {

    if (mysqli_num_rows($horario) == 0) {
        echo "Horario No Existe<br> Hay que crearlo y buscar el num horario<br> ";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO num_horario (`Temporada`, `Ano`)
        VALUES ( '" . $tempo . "','" . $fecha . "')";
            $conn->exec($sql);
            $num_horario = $conn->lastInsertId();
            echo $sql;
            echo 'Num_ Horario: ' . $num_horario;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }
        echo $sql3;
    } else {
        echo "Horario Existe<br> No Hacer Nada<br> ";
        echo $sql3 . "<br>";
        $query = $conexion->query($sql3);
        while ($valores = mysqli_fetch_array($query)) {
            $num_horario = $valores['Num'];
        }
    }
    echo $num_horario . "<br>";
}


echo $link;


//echo $mix."<br>";
//echo $mix2."<br>";
//echo $dia_emision;

if (mysqli_num_rows($anime) == 0) {
    echo "Anime no Existe<br>";

    if ($estado == "Emision") {
        echo "Anime En Emision<br>";

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
            VALUES ( '" . $estado . "','" . $nombre . "','1','12','" . $dia_emision . "','00:24:00')";
            $conn->exec($sql);
            $last_id1 = $conn->lastInsertId();
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
            $sql = "INSERT INTO anime (`id`,`Anime`,  `Estado`, `Spin_Off` , `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`)
             VALUES ( '" . $id . "','" . $nombre . "','" . $estado . "','NO','" . $last_id1 . "',1,'" . $fecha . "','" . $temp . "')";
            $conn->exec($sql);
            echo $sql;
            $last_id2 = $conn->lastInsertId();
            echo 'ultimo anime insertado ' . $last_id2;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        if (isset($_REQUEST["OP"])) {
            $OP = $_REQUEST["OP"];
            echo "OP_Verdadero";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO  op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`, `Estado_Link`,`Fecha_Ingreso`) 
                VALUES('" . $nombre . "', '" . $last_id2 . "','1','" . $fecha . "','" . $temp . "','Faltante','" . $mix . "','Faltante',NOW())";
                $conn->exec($sql);
                echo $sql;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }
        } else {
            $OP = "NO";
            echo "OP_Falso";
            echo "<br>";
        }

        if (isset($_REQUEST["ED"])) {
            $ED = $_REQUEST["ED"];
            echo "ED_Verdadero";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO  ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`, `Estado_Link`,`Fecha_Ingreso`) 
                VALUES('" . $nombre . "', '" . $last_id2 . "','1','" . $fecha . "','" . $temp . "','Faltante','" . $mix2 . "','Faltante',NOW())";
                $conn->exec($sql);
                echo $sql;
                echo "<br>";
                $conn = null;
            } catch (PDOException $e) {
                $conn = null;
                echo $e . "<br>";
                echo $sql;
            }
        } else {
            $ED = "NO";
            echo "ED_Falso";
            echo "<br>";
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`num_horario`)
            VALUES ( '" . $nombre . "','" . $dia_emision . "','00:24:00','" . $num_horario . "')";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_anime`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }



        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de  ' . $nombre . ' en Anime y en Emision",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    } else if ($estado == "Finalizado") {
        echo "Anime En Finalizado";
        echo $estado;
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO anime (`id`,`Anime`, `Estado`,`Spin_Off`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`)
            VALUES ( '" . $id . "','" . $nombre . "','" . $estado . "','NO',1,1,'" . $fecha . "','" . $temp . "')";

            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_anime`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de  ' . $nombre . ' en Anime",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
        echo "1.1";
        echo "<br>";
    } else if ($estado == "Pausado") {
        echo "Anime En Pausado";
        echo $estado;

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO emision (`Emision`, `Nombre`, `Capitulos`, `Totales`, `Dia`, `Duracion`)
            VALUES ( '" . $estado . "','" . $nombre . "','1','12','Indefinido','00:24:00')";
            $conn->exec($sql);
            $last_id1 = $conn->lastInsertId();
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
            $sql = "INSERT INTO anime (`id`,`Anime`, `Estado`, `Spin_Off` ,`id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`)
            VALUES ( '" . $id . "','" . $nombre . "','" . $estado . "','NO','" . $last_id1 . "',1,'" . $fecha . "','" . $temp . "')";

            $conn->exec($sql);
            echo $sql;
            $last_id2 = $conn->lastInsertId();
            echo 'ultimo anime insertado ' . $last_id2;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `horario`( `Nombre`, `Dia`, `Duracion`,`num_horario`)
            VALUES ( '" . $nombre . "','Indefinido','00:24:00','" . $num_horario . "')";
            $conn->exec($sql);
            //$last_id3 = $conn->lastInsertId();
            echo $sql;
            //echo 'ultimo anime insertado ' . $last_id3;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_anime`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }

        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre . ' en Emision y Anime",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    } else if ($estado == "Pendiente") {
        echo "Anime En Pendiente";
        echo $estado;

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
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO anime (`id`,`Anime`, `Spin_Off`, `Estado`, `id_Emision`, `id_Pendientes`, `Ano`, `Id_Temporada`)
            VALUES ( '" . $id . "','" . $nombre . "','NO','" . $estado . "',1,'" . $last_id1 . "','" . $fecha . "','" . $temp . "')";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
            echo $sql;
            echo $last_id1;
        }

        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "  DELETE FROM `id_anime`  WHERE `ID` = '" . $id . "'";
            $conn->exec($sql);
            echo $sql;
            echo "<br>";
            $conn = null;
        } catch (PDOException $e) {
            $conn = null;
            echo $e;
        }
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Creando registro de ' . $nombre . ' en Pendiente y Anime",
            confirmButtonText: "OK"
        }).then(function() {
            window.location = "' . $link . '";
        });
        </script>';
    }
} else {
    echo "Anime ya existe";
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Registro de ' . $nombre . ' Existe en Anime",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $link . '";
    });
    </script>';
}
