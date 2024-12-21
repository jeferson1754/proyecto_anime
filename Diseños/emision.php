<?php

require '../bd.php';

include '../update_emision.php';
// Establecer la zona horaria para Santiago de Chile.
date_default_timezone_set('America/Santiago');

// Obtener la fecha y hora actual con 5 horas de retraso.
$fecha_actual_retrasada = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Array con los nombres de los días en español.
$nombres_dias = array(
    'domingo',
    'lunes',
    'martes',
    'miércoles',
    'jueves',
    'viernes',
    'sábado'
);

// Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.).
$numero_dia = date('w', strtotime($fecha_actual_retrasada));

// Obtener el nombre del día actual en español.
$nombre_dia = $nombres_dias[$numero_dia];

//echo 'Hoy es ' . $nombre_dia;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emisión de Anime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #4f46e5;
            --success-color: #16a34a;
            --warning-color: #ca8a04;
            --danger-color: #dc2626;
            --background-color: #f8fafc;
            --card-background: #ffffff;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1f2937;
        }

        .main-container {
            max-width: 1400px;
            margin: 1.5rem auto;
            padding: 0 1rem;
        }

        .actions-panel {
            background: var(--card-background);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .btn-custom {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .content-card {
            background: var(--card-background);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
            margin: 1rem 0;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background-color: #f8fafc;
            padding: 1rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .custom-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .progress-cell {
            width: 150px;
        }

        .progress {
            height: 0.75rem;
            border-radius: 9999px;
            background-color: #e5e7eb;
        }

        .progress-bar {
            border-radius: 9999px;
            transition: width 0.3s ease;
        }

        .episode-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .episode-watched {
            background-color: #dcfce7;
            color: #166534;
        }

        .episode-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .day-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .filter-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .form-select {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            max-width: 200px;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 0.75rem;
                margin: 1rem auto;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
            }

            .content-card {
                padding: 1rem;
            }

            .episode-badge {
                font-size: 0.75rem;
            }

            .progress-cell {
                width: 100px;
            }
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color);
            color: white !important;
            border: none;
            border-radius: 6px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-button {
            padding: 0.5rem;
            border-radius: 6px;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <?php include '../menu.php'; ?>

    <div class="main-container">
        <!-- Panel de Acciones -->
        <div class="actions-panel">
            <h2 class="mb-4">Seguimiento de Emisiones</h2>

            <div class="button-group">
                <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-custom btn-primary" type="submit" name="enviar">
                        <i class="fas fa-calendar"></i> Hoy

                    </button>
                    <button class="btn btn-custom btn-secondary" type="submit" name="borrar">
                        <i class="fas fa-eraser"></i> Borrar Filtros
                    </button>
                    <button class="btn btn-custom btn-warning" type="submit" name="faltantes">
                        <i class="fas fa-clock"></i> Pendientes
                    </button>
                    <button type="button" class="btn btn-custom btn-info" onclick="myFunction()">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <button class="btn btn-custom btn-success" type="button" id="miBoton">
                        <i class="fas fa-check"></i> Marcar Todos Vistos

                    </button>
                </form>
            </div>

            <!-- Sección de Filtros -->
            <div id="myDIV" class="filter-section" style="display:none;">
                <form action="" method="GET" class="d-flex gap-3 align-items-center flex-wrap">
                    <select name="dias" class="form-select">
                        <option value="">Seleccionar día</option>
                        <?php
                        $query = $conexion->query("SELECT DISTINCT(e.Dia) FROM emision e INNER JOIN dias ot ON e.Dia = ot.Dia ORDER BY ot.ID ASC;");
                        while ($valores = mysqli_fetch_array($query)) {
                            echo '<option value="' . $valores['Dia'] . '">' . $valores['Dia'] . '</option>';
                        }
                        ?>
                    </select>
                    <button class="btn btn-custom btn-primary" type="submit" name="enviar2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabla de Anime -->
        <div class="content-card">
            <div class="table-container">
                <table id="animeTable" class="table custom-table">
                    <thead>
                        <tr>
                            <th>Anime</th>
                            <th>Progreso</th>
                            <th>Estado</th>
                            <th>Día</th>
                            <th>Duración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql1 = "SELECT * FROM `emision`";
                        $result = mysqli_query($conexion, $sql1);
                        //echo $sql1;
                        while ($mostrar = mysqli_fetch_array($result)) {
                            if ($mostrar['Totales'] > 0) {
                                $faltantes = $mostrar['Faltantes'] - $mostrar['Capitulos'];
                                $porcentaje = ($mostrar['Capitulos'] / $mostrar['Totales']) * 100;
                            } else {
                                $faltantes = $mostrar['Faltantes'] - $mostrar['Capitulos']; // O el valor por defecto que desees
                                $porcentaje = 0; // O un mensaje o acción específica en caso de Totales = 0
                            }
                        ?>
                            <tr>
                                <td class="fw-500"><?php echo $mostrar['Nombre'] ?></td>
                                <td class="progress-cell">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar bg-<?php echo $porcentaje == 100 ? 'success' : 'primary' ?>"
                                                role="progressbar"
                                                style="width: <?php echo $porcentaje ?>%"
                                                aria-valuenow="<?php echo $porcentaje ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small"><?php echo $mostrar['Capitulos'] ?>/<?php echo $mostrar['Totales'] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="episode-badge <?php echo $faltantes > 0 ? 'episode-pending' : 'episode-watched' ?>">
                                        <?php echo $faltantes > 0 ? $faltantes . ' pendientes' : 'Al día' ?>
                                    </span>
                                </td>
                                <td><span class="day-badge"><?php echo $mostrar['Dia'] ?></span></td>
                                <td><?php echo substr($mostrar['Duracion'], 3); ?> min</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button"
                                            class="action-button bg-success"
                                            data-tooltip="Aprobar"
                                            data-toggle="modal"
                                            data-target="#editChildresn7<?php echo $mostrar['ID_Emision']; ?>"
                                            aria-label="Aprobar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button"
                                            class="action-button bg-primary"
                                            data-tooltip="Editar"
                                            data-toggle="modal"
                                            data-target="#editChildresn5<?php echo $mostrar['ID_Emision']; ?>"
                                            aria-label="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="action-button bg-danger"
                                            data-tooltip="Eliminar"
                                            data-toggle="modal"
                                            data-target="#editChildresn6<?php echo $mostrar['ID_Emision']; ?>"
                                            aria-label="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        <?php
                            include('ModalEditar-Emision.php');
                            include('Modal-Caps.php');
                            include('ModalDelete-Emision.php');
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#animeTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                responsive: true,
                order: [],
            });
        });

        function myFunction() {
            const filterSection = document.getElementById("myDIV");
            filterSection.style.display = filterSection.style.display === "none" ? "block" : "none";
        }

        // Tu código existente para el botón de marcar todos como vistos
        document.getElementById('miBoton').addEventListener('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Consulta!',
                text: '¿Desea marcar como vistos todos los animes del dia <?php echo $nombre_dia ?> en Emision',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Mensaje importante',
                        text: 'Serás redirigido en 3 segundos...',
                        icon: 'warning',
                        showConfirmButton: false, // Oculta los botones
                        timer: 3000, // Tiempo en milisegundos (5 segundos en este caso)
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                        onClose: () => {
                            // Redirige a otra página después de que termine el temporizador
                            window.location.href = 'vistos.php';
                        }
                    });

                    // Redirige a otra página después de 5 segundos incluso si el usuario no cierra la alerta
                    setTimeout(() => {
                        window.location.href = 'vistos.php';
                    }, 3000);
                }
            })
        });
    </script>
</body>



</html>