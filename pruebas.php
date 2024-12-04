<?php

function InfoListasSwal($title, $tipo, $location)
{
    echo '<script>
    Swal.fire({
        icon: "' . $title . '",
        title: "' . $tipo . '",
        confirmButtonText: "OK"
    }).then(function() {
        window.location = "' . $location . '";
    });
    </script>';
}

$sql1 = "
    SELECT mix.ID, COUNT(op.Mix) AS MixCount 
    FROM mix
    LEFT JOIN op ON mix.ID = op.Mix 
    WHERE mix.ID = (SELECT MAX(ID) FROM mix)
    GROUP BY mix.ID 
    ORDER BY mix.ID DESC;
";
//echo $sql1 . "<br>";

$mixes = $conexion->query($sql1);

// Procesar los resultados
while ($valores = mysqli_fetch_array($mixes)) {
    $mix = $valores['ID']; // o el índice correspondiente si necesitas otro valor
    $mixCount = $valores['MixCount'];
}

$sql2 = "
SELECT mix_ed.ID, COUNT(ed.Mix) AS MixCount FROM mix_ed LEFT JOIN ed ON mix_ed.ID = ed.Mix WHERE mix_ed.ID = (SELECT MAX(ID) FROM mix_ed) GROUP BY mix_ed.ID ORDER BY mix_ed.ID DESC;
";

//echo $sql2 . "<br>";

$mixes_ed = $conexion->query($sql2);

// Procesar los resultados
while ($valores2 = mysqli_fetch_array($mixes_ed)) {
    $mix2 = $valores2['ID']; // o el índice correspondiente si necesitas otro valor
    $mixCount2 = $valores2['MixCount'];
}

echo "<br>OP-";
echo $op;
echo "<br>OP1-";
echo $op1;
echo "<br>ED-";
echo $ed;
echo "<br>ED1-";
echo $ed1;
echo "<br> Mix OP-" . $mix;
echo "<br> Conteo-" . $mixCount;
echo "<br> Mix ED-" . $mix2;
echo "<br> Conteo-" . $mixCount2;
echo "<br>";

if ($mixCount < 50) {
    echo "Mix OP /$mixCount esta bien<br>";
    if ($op > $op1) {
        echo "OP Mayor a Resultado<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO op (`Nombre`, `ID_Anime`, `Opening`, `Ano`, `Temporada`, `Estado`, `Mix`,`Fecha_Ingreso`)
    VALUES('" . $nombre . "', '" . $IdAnime . "','" . $op . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix . "',NOW())";
            //$conn->exec($sql);
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
} else {

    echo "Mix OP supero limite<br>";
}

if ($mixCount2 < 30) {
    echo "Mix ED /$mixCount2 esta bien<br>";
    if ($ed > $ed1) {
        echo "ED Mayor a Resultado";
        echo "<br>";
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO ed (`Nombre`, `ID_Anime`, `Ending`, `Ano`, `Temporada`, `Estado`, `Mix`,`Fecha_Ingreso`)
        VALUES('" . $nombre . "', '" . $IdAnime . "','" . $ed . "','" . $fecha . "','" . $temp . "','Faltante','" . $mix2 . "',NOW())";
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
} else {
    echo "Mix ED supero limite<br>";
}

echo "<br>";
