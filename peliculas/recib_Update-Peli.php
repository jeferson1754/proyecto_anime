<!--coment-->
<header>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</header>

<?php
include '../bd.php';
$idRegistros    = $_POST['id'];
$idPendientes    = $_POST['pendi'];
$nombre  = $_REQUEST['nombre'];
$estado   = $_REQUEST['estado'];
$fecha = $_REQUEST['fecha'];

$sql = ("SELECT * FROM `peliculas` where ID='$idRegistros';");
$sql2 = ("SELECT * FROM `pendientes`where ID_Pendientes='$idPendientes' and  ID_Pendientes>1;");
$sql3 = ("SELECT * FROM `peliculas` where Nombre='$nombre';");
$peli      = mysqli_query($conexion, $sql);
$pendientes = mysqli_query($conexion, $sql2);
$name = mysqli_query($conexion, $sql3);


echo $sql;
echo "<br>";
echo $sql2;
echo "<br>";
echo $sql3;
echo "<br>";
echo $estado;

if (mysqli_num_rows($peli) == 0) {

    echo '<script>
        Swal.fire({
            icon: "error",
            title: "No se puede editar porque ' . $nombre . ' no existe en Peliculas",
            confirmButtonText: "OK"
    
        }).then(function() {
            window.location = "../peliculas.php";
        });
        </script>';
} else {
    echo "Existe en Peliculas";
    echo "<br>";
    if ($estado == "Finalizado") {
        if (mysqli_num_rows($pendientes) == 0) {
            echo $estado;
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE peliculas SET 
                    Nombre ='" . $nombre . "',
                    Ano ='" . $fecha . "',
                    Estado ='" . $estado . "',
                    ID_Pendientes ='" . $idPendientes . "'
                    WHERE ID='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . '  en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido ' . $nombre . ' ",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }
        } else {

            echo $estado;
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE peliculas SET 
                    Nombre ='" . $nombre . "',
                    Ano ='" . $fecha . "',
                    Estado ='" . $estado . "',
                    ID_Pendientes ='1'
                    WHERE ID='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido ' . $nombre . ' ",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM pendientes WHERE ID_Pendientes ='" . $idPendientes . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }


            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
        }
    } else if ($estado == "Pendiente") {

        echo $estado;
        if (mysqli_num_rows($pendientes) == 0) {
            echo "No Existe en Pendientes";
            echo "<br>";

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE peliculas SET 
                    Nombre ='" . $nombre . "',
                    Ano ='" . $fecha . "',
                    Estado ='" . $estado . "'
                    WHERE ID='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido de ' . $nombre . '",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //INSERT INTO `pendientes`(`ID`, `Nombre`, `Vistos`, `Total`, `Pendientes`, `Link`)
                $sql = "INSERT INTO pendientes (`Nombre`, `Tipo`, `Vistos`, `Total`) 
                VALUES ( '" . $nombre . "','Pelicula','0','1')";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) where Vistos >-1;";
                $conn->exec($sql);
                $last_id3 = $conn->lastInsertId();
                echo $sql;
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
            }

            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                confirmButtonText: "OK"
            }).then(function() {
                window.location = "../peliculas.php";
            });
            </script>';
        } else {
            echo "Existe en Pendientes";
            echo "<br>";
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Nombre ='" . $nombre . "',
                    Vistos ='0',
                    Total ='1',
                    Tipo ='Pelicula'
                    WHERE ID_Pendientes='" . $idPendientes . "'";
                $conn->exec($sql);
                $last_id1 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id1;
                echo "<br>";

                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido de ' . $nombre . ' en Pendientes",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }
            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE peliculas SET 
                    Nombre ='" . $nombre . "',
                    Ano ='" . $fecha . "',
                    Estado ='" . $estado . "',
                    ID_Pendientes ='" . $idPendientes . "'
                    WHERE ID='" . $idRegistros . "'";
                $conn->exec($sql);
                $last_id2 = $conn->lastInsertId();
                echo $sql;
                echo 'ultimo anime insertado ' . $last_id2;
                echo "<br>";
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido de ' . $nombre . '",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }

            try {
                $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE pendientes SET Pendientes = (Total - Vistos) where Vistos >-1;";
                $conn->exec($sql);
                $last_id3 = $conn->lastInsertId();
                echo $sql;
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizando registro de ' . $nombre . ' en Peliculas ",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location = "../peliculas.php";
                });
                </script>';
            } catch (PDOException $e) {
                $conn = null;
                echo $e;
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Nombre Repetido en Pendientes",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location = "../peliculas.php";
                    });
                    </script>';
            }
        }
    }
}
    

//UPDATE `emision` SET `Capitulos` = '1' WHERE `emision`.`ID` = 19;
//SELECT SUM(Capitulos)+1 total FROM emision Where Nombre="Dragon Ball";




//$result_update = mysqli_query($conexion, $update);

//header("location:index.php");
