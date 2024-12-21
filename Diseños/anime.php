<?php

require 'bd.php';
include 'update_emision.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");

// Obtener el día actual en español
$sqlDiaActual = "SELECT CASE WEEKDAY(DATE_SUB(NOW(), INTERVAL 5 HOUR))
                    WHEN 0 THEN 'Lunes'
                    WHEN 1 THEN 'Martes'
                    WHEN 2 THEN 'Miércoles'
                    WHEN 3 THEN 'Jueves'
                    WHEN 4 THEN 'Viernes'
                    WHEN 5 THEN 'Sábado'
                    WHEN 6 THEN 'Domingo'
                END AS DiaActual";

$resultDiaActual = mysqli_query($conexion, $sqlDiaActual);
$day = ($row = mysqli_fetch_assoc($resultDiaActual)) ? $row['DiaActual'] : null;

// Obtener el primer ID no presente en la tabla `anime`
$sqlAnimeId = "SELECT ID FROM id_anime WHERE ID NOT IN (SELECT id FROM anime) ORDER BY ID ASC LIMIT 1";
$resultAnimeId = mysqli_query($conexion, $sqlAnimeId);
$ani1 = ($row = mysqli_fetch_assoc($resultAnimeId)) ? $row['ID'] : 0;

// Determinar la temporada según el mes actual
$temporadas = [
    'January' => ['Invierno', 1],
    'February' => ['Invierno', 1],
    'March' => ['Invierno', 1],
    'April' => ['Primavera', 2],
    'May' => ['Primavera', 2],
    'June' => ['Primavera', 2],
    'July' => ['Verano', 3],
    'August' => ['Verano', 3],
    'September' => ['Verano', 3],
    'October' => ['Otoño', 4],
    'November' => ['Otoño', 4],
    'December' => ['Otoño', 4]
];

$tempo = $temporadas[$mes][0] ?? 'Desconocido';
$id_tempo = $temporadas[$mes][1] ?? 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Anime</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --background-color: #f9fafb;
            --card-background: #ffffff;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .main-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .action-bar {
            background: var(--card-background);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .btn-custom {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .anime-table {
            background: var(--card-background);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--background-color);
            padding: 1rem;
            font-weight: 600;
            color: #374151;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-en-emision {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-finalizado {
            background-color: #f1f5f9;
            color: #475569;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .search-filters {
                flex-direction: column;
            }

            .action-bar {
                padding: 1rem;
            }
        }

        /* Accesibilidad */
        .btn:focus,
        .form-control:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* DataTables personalización */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color);
            color: white !important;
            border: none;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--secondary-color);
            color: white !important;
            border: none;
        }
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="main-container">
        <!-- Barra de acciones -->
        <div class="action-bar">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="h4 mb-0">Lista de Anime</h1>
                <div class="action-buttons">
                    <button type="button" class="btn btn-custom btn-primary" data-bs-toggle="modal" data-bs-target="#NuevoAnime">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Anime
                    </button>
                    <button type="button" class="btn btn-custom btn-outline-secondary" onclick="toggleFilters()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros
                    </button>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="search-filters" id="filtersContainer" style="display: none;">
                <div class="flex-grow-1">
                    <input type="text" class="form-control" placeholder="Buscar anime...">
                </div>
                <select class="form-select" style="max-width: 200px;">
                    <option value="">Estado</option>
                    <option value="en-emision">En emisión</option>
                    <option value="finalizado">Finalizado</option>
                </select>
                <select class="form-select" style="max-width: 200px;">
                    <option value="">Temporada</option>
                    <option value="invierno">Invierno</option>
                    <option value="primavera">Primavera</option>
                    <option value="verano">Verano</option>
                    <option value="otono">Otoño</option>
                </select>
            </div>
        </div>
        <?php

        $where = "ORDER BY `anime`.`id` DESC LIMIT 10";

        if (isset($_GET['borrar'])) {
            $where = "ORDER BY `anime`.`id` DESC LIMIT 10";
        } elseif (isset($_GET['filtrar']) && isset($_GET['estado'])) {
            $estado = $_REQUEST['estado'];
            $where = "WHERE anime.Estado LIKE '%" . mysqli_real_escape_string($conexion, $estado) . "%' ORDER BY `anime`.`id` DESC LIMIT 10";
        } elseif (isset($_GET['buscar']) && isset($_GET['busqueda_anime'])) {
            $busqueda = $_REQUEST['busqueda_anime'];
            $where = "WHERE anime.Anime LIKE '%" . mysqli_real_escape_string($conexion, $busqueda) . "%' ORDER BY `anime`.`id` DESC LIMIT 10";
        }

        include('ModalCrear.php');
        ?>


        <!-- Tabla de anime -->
        <div class="anime-table">
            <table id="animeTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Anime</th>
                        <th>Temporadas</th>
                        <th>Estado</th>
                        <th>Año</th>
                        <th>Temporada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT anime.id,anime.Anime,anime.Temporadas,anime.Peliculas,anime.Spin_Off,anime.Estado,anime.id_Emision,anime.id_Pendientes,anime.Ano,anime.Id_Temporada,temporada.Temporada FROM `anime` JOIN temporada ON anime.Id_Temporada=temporada.ID $where";

                    $result = mysqli_query($conexion, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                        $iden = $mostrar['id']; ?>
                        <tr>
                            <td><?php echo $mostrar['id'] ?></td>
                            <td><?php echo $mostrar['Anime'] ?></td>
                            <td><?php echo $mostrar['Temporadas'] ?></td>
                            <td>
                                <span class="status-badge <?php echo ($mostrar['Estado']) == 'Emision' ? 'status-en-emision' : 'status-finalizado' ?>">
                                    <?php echo $mostrar['Estado'] ?>
                                </span>
                            </td>
                            <td><?php echo $mostrar['Ano'] ?></td>
                            <td><?php echo $mostrar['Temporada'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $mostrar['id']; ?>">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $mostrar['id']; ?>">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    <?php include('ModalEditar.php');
                        include('ModalDelete.php');
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#animeTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                pageLength: 10,
                dom: '<"top"f>rt<"bottom"lip><"clear">'
            });
        });

        function toggleFilters() {
            const filtersContainer = document.getElementById('filtersContainer');
            filtersContainer.style.display = filtersContainer.style.display === 'none' ? 'flex' : 'none';
        }
    </script>
</body>

</html>