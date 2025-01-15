<!DOCTYPE html>
<html>

<head>
  <style>
    .info-modal {
      font-family: system-ui, -apple-system, sans-serif;
    }

    .info-modal .modal-content {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .info-modal .modal-header {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      padding: 1.5rem;
      border: none;
    }

    .info-modal .modal-title {
      color: white;
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0;
    }

    .info-modal .close {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      width: 32px;
      height: 32px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      border: none;
      transition: all 0.2s ease;
    }

    .info-modal .close:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: rotate(90deg);
    }

    .info-modal .modal-body {
      padding: 2rem;
    }

    .info-modal .song-info {
      text-align: center;
      margin-bottom: 2rem;
    }

    .info-modal .song-title {
      color: #1f2937;
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .info-modal .song-details {
      display: grid;
      gap: 1rem;
      padding: 1.5rem;
      background: #f8fafc;
      border-radius: 12px;
      margin-bottom: 2rem;
    }

    .info-modal .detail-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #4b5563;
      font-size: 1rem;
    }

    .info-modal .detail-label {
      font-weight: 600;
      color: #1f2937;
      min-width: 80px;
    }

    .info-modal .player-container {
      background: #f1f5f9;
      border-radius: 12px;
      padding: 1rem;
      margin-top: 1rem;
    }

    .info-modal .player-container iframe {
      border-radius: 8px;
      background: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Animations */
    @keyframes modalFade {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal.fade .modal-dialog {
      animation: modalFade 0.3s ease-out;
    }

    @media (max-width: 576px) {
      .info-modal .modal-body {
        padding: 1.5rem;
      }

      .info-modal .song-title {
        font-size: 1.5rem;
      }

      .info-modal .song-details {
        padding: 1rem;
      }
    }
  </style>
</head>

<body>

  <div class="modal fade info-modal" id="info<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="songInfoModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="songInfoModal">
            Info de la Canción
          </h6>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <?php include('regreso-modal.php');
        $op = $conexion->query("SELECT Anime FROM `anime` WHERE id='$mostrar[ID_Anime]';");
        while ($valores = mysqli_fetch_array($op)) {
          $album = $valores[0];
        }
        ?>

        <div class="modal-body">
          <div class="song-info text-center">
            <h1 class="song-title">
              <?php echo $mostrar['Cancion']; ?>
            </h1>

            <div class="song-details mb-4">
              <div class="detail-item">
                <span class="detail-label">Artista:</span>
                <span>
                  <?php
                  if ($copia_autor == "SI") {
                    echo $mostrar['Autor'];
                  } else {
                    echo $mostrar['Nombre'] . " OP " . $mostrar['Opening'];
                  }
                  ?>
                </span>
              </div>

              <div class="detail-item">
                <span class="detail-label">Álbum:</span>
                <span><?php echo $album; ?></span>
              </div>
            </div>

            <div class="player-container">
              <iframe src="./ejemplo.php?id=<?php echo $id_Registros; ?>" frameborder="0" style="width: 100%; height: 100px;"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>