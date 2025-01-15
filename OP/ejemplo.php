<?php
require '../bd.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No se ha proporcionado el ID del OP.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <title>Copia al Portapapeles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #444;
        }

        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .buttons-container button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .buttons-container button:hover {
            background-color: #0056b3;
        }

        .buttons-container button i {
            font-size: 20px;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <?php
    $consulta = "SELECT * FROM `op`INNER JOIN autor ON op.ID_Autor=autor.ID WHERE op.ID='$id'";
    $resultados = $conexion->query($consulta);

    if ($resultados->num_rows > 0) {
        while ($row = $resultados->fetch_assoc()) {
            $cancion = $row["Cancion"] ?? "";
            $texto1 = $row["Nombre"] ?? "";
            $texto2 = $row["Opening"] ?? "";
            /*
            echo $cancion . "<br>";
            echo $texto1 . "<br>";
            echo $texto2 . "<br>";
            */



            echo "<div class='buttons-container'>";
            echo '<button title="Copiar Título" onclick="copyToClipboard(\'' . $cancion . '\')"><i class="fa-solid fa-music"></i> Título</button>';


            if ($row["Copia_Autor"] == "SI") {
                $autor = $row["Autor"];
                echo '<button title="Copiar Artista" onclick="copyToClipboard(\'' . $autor . '\')"><i class="fa-solid fa-user"></i> Artista</button>';
            } else {
                echo '<button title="Copiar Artista" onclick="copyToClipboard(\'' . $texto1 . ' OP ' . $texto2 . '\')"><i class="fa-solid fa-user"></i> Artista</button>';
            }


            $sql2 = "SELECT anime.Anime FROM `op` INNER JOIN anime ON op.ID_Anime = anime.id WHERE op.ID = '$id'";
            $result2 = $conexion->query($sql2);

            if ($result2->num_rows > 0) {
                $fila = $result2->fetch_assoc();
                $anime = $fila["Anime"];
                echo '<button title="Copiar Álbum" onclick="copyToClipboard(\'' . $anime . '\')"><i class="fa-solid fa-compact-disc"></i> Álbum</button>';
            } else {
                echo '<button title="Copiar Álbum" onclick="copyToClipboard(\'' . $texto1 . '\')"><i class="fa-solid fa-compact-disc"></i> Álbum</button>';
            }

            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron resultados en la base de datos.</p>";
    }
    ?>



    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Texto copiado al portapapeles: " + text);
            }).catch(err => {
                console.error('Error al copiar al portapapeles:', err);
            });
        }
    </script>
</body>

</html>