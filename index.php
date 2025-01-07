<?php

require 'bd.php';

setlocale(LC_ALL, "es_ES");
$año = date("Y");
$mes = date("F");

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
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Mobile adaptations */
        @media (max-width: 768px) {
            .table-card {
                display: block;
                margin-bottom: 1rem;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 1rem;
            }

            .table-responsive {
                border: none;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                text-align: center !important;
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 1rem;
            }

            .table td {
                display: flex;
                justify-content: center;
                padding: 0.5rem;
                border: none;
            }

            .table td:before {

                font-weight: 600;
            }


            .action-buttons {
                justify-content: flex-end;
                margin-top: 1rem;
            }

            .table td[data-label="Acciones"] {
                display: block;
                justify-content: flex-end;
                align-items: center;
            }

            .table td[data-label="Acciones"] svg {
                margin: 0 auto;
            }

            .table td[data-label="Acciones"]:before {
                display: none;
            }

            .action-buttons {
                flex-direction: row;
                width: 100%;
                justify-content: flex-end;
                gap: 0.5rem;
            }
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

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

        .status-pendiente {
            background-color: #fffbeb;
            /* Un fondo amarillo claro */
            color: #9a6b00;
            /* Un color de texto marrón oscuro */
        }

        .status-pausado {
            background-color: #e1e7ff;
            /* Un fondo azul claro */
            color: #4c6b8a;
            /* Un color de texto gris azulado */
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

        .main-container {
            max-width: 600%;
            margin: 30px 20px;

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

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #6b46c1 0%, #4299e1 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6b46c1;
            box-shadow: 0 0 0 2px rgba(107, 70, 193, 0.2);
        }

        .inline-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .inline-group .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /*

        .rating-box {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .rating-box header {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .fa-star {
            color: #cbd5e0;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .fa-star.active {
            color: #ecc94b;
        }

        */


        /* Contenedor principal del rating */
        .rating-box {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 1.5rem;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.05),
                -5px -5px 15px rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .rating-box:hover {
            transform: translateY(-2px);
        }

        /* Encabezado del rating */
        .rating-box header {
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .rating-box header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #ecc94b, #d69e2e);
            border-radius: 2px;
        }

        /* Contenedor de estrellas */
        .stars {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding: 0.5rem;
        }

        /* Estrellas individuales */
        .fa-star {
            color: #e2e8f0;
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        /* Efectos hover en las estrellas */
        .fa-star:hover {
            transform: scale(1.2);
        }

        .fa-star:hover~.fa-star {
            transform: scale(0.9);
            opacity: 0.8;
        }

        /* Estrellas activas */
        .fa-star.active {
            color: #fbbf24;
            filter: drop-shadow(0 0 6px rgba(251, 191, 36, 0.5));
            animation: starPulse 1s ease-in-out infinite;
        }

        /* Texto del rating */
        .rating-text {
            color: #4a5568;
            font-weight: 500;
            margin-top: 1rem;
        }

        .rating-value {
            background: linear-gradient(90deg, #fbbf24, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            font-size: 1.2rem;
            margin-left: 0.5rem;
        }

        /* Animación de pulso para las estrellas activas */
        @keyframes starPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Efecto de resplandor al hacer hover en el contenedor */
        .rating-box:hover .stars {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0.8;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilo para el botón de cambiar calificación */
        .rating-box .btn-secondary {
            background: linear-gradient(145deg, #718096, #4a5568);
            border: none;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
            margin-top: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .rating-box .btn-secondary:hover {
            background: linear-gradient(145deg, #4a5568, #2d3748);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Ajustes responsive */
        @media (max-width: 768px) {
            .rating-box {
                padding: 1.5rem;
            }

            .fa-star {
                font-size: 1.75rem;
            }

            .rating-box header {
                font-size: 1.1rem;
            }
        }


        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #6b46c1;
            border: none;
        }

        .btn-primary:hover {
            background: #553c9a;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #718096;
            border: none;
        }

        .btn-secondary:hover {
            background: #4a5568;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .inline-group {
                flex-direction: column;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Estilos para el modal de eliminación */
        .delete-modal .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .delete-modal .modal-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 1.5rem;
            position: relative;
        }

        .delete-modal .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            width: 100%;
        }

        .delete-modal .close {
            color: white;
            opacity: 1;
            text-shadow: none;
            transition: transform 0.3s ease;
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
        }

        .delete-modal .close:hover {
            transform: rotate(90deg);
            opacity: 1;
        }

        .delete-modal .modal-body {
            padding: 2rem;
            text-align: center;
            background: #fff;
        }

        .delete-modal .anime-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .delete-modal .anime-details {
            font-size: 1.25rem;
            color: #6b7280;
            margin: 0.5rem 0;
        }

        .delete-modal .warning-icon {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1.5rem;
            animation: warningPulse 2s infinite;
        }

        @keyframes warningPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .delete-modal .modal-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1.25rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .delete-modal .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .delete-modal .btn-cancel {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .delete-modal .btn-cancel:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .delete-modal .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
        }

        .delete-modal .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
        }

        /* Animación de entrada */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .delete-modal .modal-body {
                padding: 1.5rem;
            }

            .delete-modal .anime-title {
                font-size: 1.5rem;
            }

            .delete-modal .anime-details {
                font-size: 1rem;
            }

            .delete-modal .modal-footer {
                flex-direction: column;
            }

            .delete-modal .btn {
                width: 100%;
                justify-content: center;
            }

            .table td {
                border-top: none !important;
            }
        }

        .song-options {
            padding: 1.5rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .song-label {
            font-size: 1.2rem;
            font-weight: 600;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .song-label i {
            font-size: 1.4rem;
            color: #6366f1;
            -webkit-text-fill-color: #6366f1;
        }

        .checkbox-group {
            display: flex;
            gap: 2.5rem;
        }

        .custom-checkbox {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background: rgba(99, 102, 241, 0.03);
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .custom-checkbox:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
            background: rgba(99, 102, 241, 0.06);
        }

        .custom-checkbox input {
            appearance: none;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #6366f1;
            border-radius: 0.5rem;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .custom-checkbox input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .custom-checkbox input:checked::after {
            content: '';
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 0.3rem;
            height: 0.6rem;
            border: solid white;
            border-width: 0 2px 2px 0;
        }

        .custom-checkbox label {
            font-weight: 500;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #1f2937 0%, #4b5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .checkbox-group {
                flex-direction: column;
                gap: 1rem;
            }

            .custom-checkbox {
                width: 100%;
                justify-content: flex-start;
            }

        }

        /* Animación al hacer check */
        .custom-checkbox input:checked {
            animation: checkmark 0.2s ease-in-out;
        }

        @keyframes checkmark {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
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
            <form action="" method="GET">
                <div class="search-filters" id="filtersContainer" style="display: none;">
                    <div class="flex-grow-1">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar anime..."
                            name="busqueda_anime"
                            value="<?= htmlspecialchars($_GET['busqueda_anime'] ?? '', ENT_QUOTES) ?>">
                    </div>
                    <select class="form-select" style="max-width: 200px;" name="estado">
                        <option value="">Estado</option>
                        <option value="Emision" <?= (isset($_GET['estado']) && $_GET['estado'] === "Emision") ? 'selected' : '' ?>>En Emision</option>
                        <option value="Finalizado" <?= (isset($_GET['estado']) && $_GET['estado'] === "Finalizado") ? 'selected' : '' ?>>Finalizado</option>
                        <option value="Pausado" <?= (isset($_GET['estado']) && $_GET['estado'] === "Pausado") ? 'selected' : '' ?>>En Pausado</option>
                        <option value="Pendiente" <?= (isset($_GET['estado']) && $_GET['estado'] === "Pendiente") ? 'selected' : '' ?>>Pendiente</option>
                    </select>
                    <select class="form-select" style="max-width: 200px;" name="temporada">
                        <option value="">Temporada</option>
                        <option value="1" <?= (isset($_GET['temporada']) && $_GET['temporada'] === "1") ? 'selected' : '' ?>>Invierno</option>
                        <option value="2" <?= (isset($_GET['temporada']) && $_GET['temporada'] === "2") ? 'selected' : '' ?>>Primavera</option>
                        <option value="3" <?= (isset($_GET['temporada']) && $_GET['temporada'] === "3") ? 'selected' : '' ?>>Verano</option>
                        <option value="4" <?= (isset($_GET['temporada']) && $_GET['temporada'] === "4") ? 'selected' : '' ?>>Otoño</option>
                        <option value="5" <?= (isset($_GET['temporada']) && $_GET['temporada'] === "5") ? 'selected' : '' ?>>Desconocida</option>
                    </select>
                    <button class="btn btn-custom btn-outline-secondary" type="submit" name="buscar">
                        <b>Buscar</b>
                    </button>
                </div>
            </form>
        </div>
        <?php

        $where = "ORDER BY `anime`.`id` DESC LIMIT 10";

        // Limpiar parámetros GET y prevenir inyección SQL
        $estado = isset($_GET['estado']) ? mysqli_real_escape_string($conexion, $_GET['estado']) : '';
        $busqueda = isset($_GET['busqueda_anime']) ? mysqli_real_escape_string($conexion, $_GET['busqueda_anime']) : '';
        $temporada = isset($_GET['temporada']) ? mysqli_real_escape_string($conexion, $_GET['temporada']) : '';

        if (isset($_GET['borrar'])) {
            $where = "ORDER BY `anime`.`id` DESC LIMIT 10";
        } elseif (isset($_GET['buscar'])) {
            $conditions = [];

            if (!empty($busqueda)) {
                $conditions[] = "anime.Anime LIKE '%$busqueda%'";
            }
            if (!empty($estado)) {
                $conditions[] = "anime.Estado = '$estado'";
            }
            if (!empty($temporada)) {
                $conditions[] = "anime.ID_Temporada = '$temporada'";
            }

            if (!empty($conditions)) {
                $where = "WHERE " . implode(' AND ', $conditions) . " ORDER BY `anime`.`id` DESC LIMIT 10";
            }
        }



        include('ModalCrear.php');
        ?>


        <!-- Tabla de anime -->
        <div class="anime-table">
            <table id="animeTable" class="table">
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

                    //echo $sql;

                    $result = mysqli_query($conexion, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                        $iden = $mostrar['id']; ?>
                        <tr>
                            <td><?php echo $mostrar['id'] ?></td>
                            <td><?php echo $mostrar['Anime'] ?></td>
                            <td><?php echo $mostrar['Temporadas'] ?></td>
                            <td>
                                <span class="status-badge 
                                <?php
                                if ($mostrar['Estado'] == 'Emision') {
                                    echo 'status-en-emision';
                                } elseif ($mostrar['Estado'] == 'Finalizado') {
                                    echo 'status-finalizado';
                                } elseif ($mostrar['Estado'] == 'Pendiente') {
                                    echo 'status-pendiente';
                                } elseif ($mostrar['Estado'] == 'Pausado') {
                                    echo 'status-pausado';
                                }
                                ?>">
                                    <?php echo $mostrar['Estado']; ?>
                                </span>

                            </td>
                            <td><?php echo $mostrar['Ano'] ?></td>
                            <td><?php echo $mostrar['Temporada'] ?></td>
                            <td data-label="Acciones">
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
    <!-- Bootstrap 5 -->
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