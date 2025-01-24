<?php

require '../bd.php';

// Establecer la zona horaria predeterminada y el año en español
setlocale(LC_ALL, "es_ES");
$año = date("Y");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endigns Dashboard</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
    <style>
        .search-filter {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .search-filter.active {
            max-height: 200px;
            transition: max-height 0.3s ease-in;
        }

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

            .table td[data-label="Acciones"] {
                justify-content: flex-end;
                align-items: center;
            }

            .action-buttons {
                width: 100%;
                justify-content: flex-start;
                /* Alineación hacia el inicio de la columna */
                flex-direction: column;
                /* Apila los botones verticalmente */
            }

            .action-button {
                height: 35px;
                /* Los botones ocupan todo el ancho disponible */
                justify-content: center;
                /* Centra los botones dentro de su contenedor */

                /* Asegura que el contenido dentro del botón esté alineado correctamente */
            }


            .custom-table thead th {
                border: none;
            }

            .custom-table tbody td {
                border: none !important;
            }

            .form-select {
                max-width: 100% !important;
            }

            .progress-cell {
                display: none !important;
            }

            .estado {
                width: 100%;
                margin: 0 auto;
            }

        }

        .estado {
            width: 130px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

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
            max-width: 100%;
            padding: 0 1rem;
        }

        .actions-panel {
            background: var(--card-background);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .button-group {
            padding: 1rem;
            border-radius: 12px;
            background: #f8f9fa;
        }

        .btn-custom {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-custom:active {
            transform: translateY(0);
        }

        .btn-custom i {
            font-size: 1rem;
        }

        /* Estilos específicos para cada botón */
        .btn-custom.btn-primary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn-custom.btn-secondary {
            background: linear-gradient(135deg, #757575, #616161);
            color: white;
        }

        .btn-custom.btn-warning {
            background: linear-gradient(135deg, #FFA726, #F57C00);
            color: white;
        }

        .btn-custom.btn-info {
            background: linear-gradient(135deg, #26C6DA, #00ACC1);
            color: white;
        }

        .btn-custom.btn-success {
            background: linear-gradient(135deg, #66BB6A, #43A047);
            color: white;
        }

        /* Efectos hover específicos */
        .btn-custom.btn-primary:hover {
            background: linear-gradient(135deg, #1976D2, #1565C0);
        }

        .btn-custom.btn-secondary:hover {
            background: linear-gradient(135deg, #616161, #424242);
        }

        .btn-custom.btn-warning:hover {
            background: linear-gradient(135deg, #F57C00, #EF6C00) !important;
            color: var(--background-color)
        }

        .btn-custom.btn-info:hover {
            background: linear-gradient(135deg, #00ACC1, #0097A7);
        }

        .btn-custom.btn-success:hover {
            background: linear-gradient(135deg, #43A047, #388E3C);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .button-group {
                padding: 0.75rem;
            }

            .btn-custom {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .d-flex.gap-2 {
                gap: 0.5rem !important;
            }
        }

        /* Animación de ripple */
        .btn-custom {
            position: relative;
            overflow: hidden;
        }

        .btn-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease-out, height 0.3s ease-out;
        }

        .btn-custom:active::after {
            width: 200%;
            height: 200%;
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

        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border: none;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .close {
            color: white;
            opacity: 0.8;
            transition: opacity 0.3s;
            background: none;
            border: none;
            font-size: 1.5rem;
            padding: 0.5rem;
        }

        .close:hover {
            opacity: 1;
        }

        .anime-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }

        .anime-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .anime-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .episodes-watched {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-control-number {
            max-width: 120px;
            margin: 0 auto;
            padding: 0.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1rem;
            transition: border-color 0.3s;
        }

        .form-control-number:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-secondary {
            background-color: #94a3b8;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #64748b;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
            transform: translateY(-1px);
        }

        .progress-celu {
            display: none !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .anime-item {
                padding: 1rem;
            }

            .anime-title {
                font-size: 1.25rem;
            }

            .progress-celu {
                display: block !important;
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

        .modal-custom {
            font-family: system-ui, -apple-system, sans-serif;
        }

        .modal-custom .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-custom .modal-header {
            background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            border-bottom: none;
            position: relative;
        }

        .modal-custom .modal-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            width: 100%;
            text-align: center;
            margin: 0;
        }

        .modal-custom .close {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .modal-custom .close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-custom .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .modal-custom .modal-body h1 {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .modal-custom .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .modal-custom .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            font-size: 1rem;
        }

        .modal-custom .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .modal-custom .btn-secondary:hover {
            background-color: #cbd5e0;
        }

        .modal-custom .btn-primary {
            background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);
            color: white;
        }

        .modal-custom .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
        }

        /* Animation */
        .modal.fade .modal-dialog {
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        @media (max-width: 576px) {
            .modal-custom .modal-header {
                padding: 1rem;
            }

            .modal-custom .modal-body {
                padding: 1.5rem;
            }

            .modal-custom .modal-footer {
                padding: 1rem;
                flex-direction: column;
            }

            .modal-custom .btn {
                width: 100%;
            }
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }


        .status-finalizado {
            background-color: #f1f5f9;
            color: rgb(11, 162, 69);
        }

        .status-faltante {
            background-color: #fffbeb;
            /* Un fondo amarillo claro */
            color: #9a6b00;
            /* Un color de texto marrón oscuro */
        }

        .status-opening {
            background-color: #e1e7ff;
            /* Un fondo azul claro */
            color: rgb(8, 72, 137);
            /* Un color de texto gris azulado */
        }
    </style>
</head>

<body>
    <?php
    include '../menu.php';
    ?>

    <div class="main-container">
        <!--- Formulario para registrar Cliente --->
        <div class="actions-panel button-group">

            <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                <button type="button" onclick="toggleFilter('mix')" class="btn btn-primary btn-custom">
                    <i class="fas fa-filter mr-2"></i> Filtrar por Mix
                </button>
                <button type="button" onclick="toggleFilter('anime')" class="btn btn-info btn-custom">
                    <i class="fas fa-search mr-2"></i> Busqueda por Anime
                </button>
                <button type="button" onclick="toggleFilter('song')" class="btn btn-info btn-custom">
                    <i class="fas fa-user mr-2"></i> Búsqueda por Autor
                </button>

                <button type="submit" name="nombre" class="btn btn-warning btn-custom">
                    <i class="fas fa-exclamation-circle mr-2"></i> Sin Nombre
                </button>
                <button type="submit" name="link" class="btn btn-warning btn-custom" style="text-decoration: none;">
                    <i class="fas fa-link mr-2"></i> Link Faltantes
                </button>
                <button class="btn btn-secondary btn-custom " type="submit" name="borrar">
                    <i class="fas fa-eraser"></i>
                    <span>Borrar Filtros</span>
                </button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#NuevoMix" class="btn btn-primary btn-custom">
                    <i class="fas fa-plus mr-2"></i> Nuevo Mix
                </button>
            </form>


            <!-- Filter Forms -->
            <div id="mixFilter" class="search-filter mt-4 ">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-4 ">
                        <select name="estado" class="form-select" style="max-width: 100% !important;">
                            <option value="">Seleccionar Mix</option>
                            <?php
                            $query = $conexion->query("SELECT * FROM `mix_ed` ORDER BY `mix_ed`.`ID`  DESC;");
                            while ($valores = mysqli_fetch_array($query)): ?>
                                <option value="<?php echo $valores['ID']; ?>"><?php echo $valores['ID']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary mr-2" type="submit" name="filtrar">
                            <i class="fas fa-check"></i> Aplicar Filtro
                        </button>
                        <button class="btn btn-secondary" type="submit" name="borrar">
                            <i class="fas fa-times"></i> Borrar
                        </button>
                    </div>
                </form>
            </div>

            <div id="animeFilter" class="search-filter">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="busqueda_ed" class="form-control" value="<?= htmlspecialchars($_GET['busqueda_ed'] ?? '', ENT_QUOTES) ?>" placeholder="Nombre del Anime">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="buscar" class="btn btn-info mr-2">Buscar</button>
                        <button type="submit" name="borrar" class="btn btn-danger">Borrar</button>
                    </div>
                </form>
            </div>

            <div id="songFilter" class="search-filter">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="busqueda_cancion_ed" class="form-control w-64" value="<?= htmlspecialchars($_GET['busqueda_cancion_ed'] ?? '', ENT_QUOTES) ?>" placeholder="Nombre de la Cancion">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="buscar1" class="btn btn-info mr-2">Buscar</button>
                        <button type="submit" name="borrar" class="btn btn-danger">Borrar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        include('ModalMix-ED.php');

        $where = " ORDER BY `ed`.`ID` DESC limit 10 ";


        if (isset($_GET['borrar'])) {

            $where = "ORDER BY `ed`.`ID` DESC limit 10 ";
        } else  if (isset($_GET['filtrar'])) {

            if (isset($_GET['estado'])) {
                $estado   = $_REQUEST['estado'];

                $where = "WHERE ed.Mix = $estado ORDER BY `ed`.`ID` DESC";
            }
        } else if (isset($_GET['link'])) {

            $where = "WHERE Link='' OR Estado_link!='Correcto' OR Link_Iframe='' ORDER BY `ed`.`ID` DESC limit 10";
        } else if (isset($_GET['nombre'])) {

            $where = "WHERE Cancion='' OR Autor='' ORDER BY `ed`.`ID` DESC limit 10";
        } else if (isset($_GET['buscar'])) {

            if (isset($_GET['busqueda_ed'])) {

                $busqueda   = $_REQUEST['busqueda_ed'];

                $where = "WHERE anime.Nombre LIKE '%$busqueda%' ORDER BY `ed`.`ID` DESC limit 10";
            }
        } else if (isset($_GET['buscar1'])) {

            if (isset($_GET['busqueda_cancion_ed'])) {

                $busqueda   = $_REQUEST['busqueda_cancion_ed'];

                $where = "WHERE autor.Autor  LIKE '%$busqueda%' ORDER BY `ed`.`ID` DESC limit 10";
            }
        }

        ?>
    </div>

    <div class="content-card">
        <div class="table-container table-responsive">
            <table id="animeTable" class="table custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ending</th>
                        <th>Cancion</th>
                        <th>Autor</th>
                        <th>Año</th>
                        <th>Temporada</th>
                        <th style="width:80px !important">Estado</th>
                        <th>N° Mix</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = "SELECT ed.ID,CONCAT(anime.Nombre, ' ',ed.Temporada) as Nombre, anime.Nombre as album,ed.ID_Anime,ed.Ending,ed.Cancion,autor.Autor,autor.Copia_Autor,ed.Ano,ed.Temporada_Emision as Temporada,ed.Estado,ed.Link,ed.Link_Iframe,ed.Mix,ed.Estado_Link FROM `ed`  JOIN autor ON ed.ID_Autor=autor.ID JOIN anime ON ed.ID_Anime=anime.id $where";

                    $result = mysqli_query($conexion, $sql);


                    while ($mostrar = mysqli_fetch_array($result)) {
                        $id_Registros = $mostrar['ID'];
                        $iden = $mostrar['ID_Anime'];
                        $name = $mostrar['Nombre'];

                        $copia_autor = $mostrar['Copia_Autor'];
                        $name2 = substr($name, 0, 8);
                        //echo $name2;
                        //echo "<br>";


                    ?>
                        <tr>
                            <td><?php echo $mostrar['ID'] ?></td>
                            <td class="op"><?php echo $mostrar['Nombre'] ?></td>
                            <td>ED <?php echo $mostrar['Ending'] ?></td>
                            <td><a href="<?php echo $mostrar['Link'] ?>" class="enlace" title="<?php echo $mostrar['Estado_Link'] ?>" target="_blank"><?php echo $mostrar['Cancion'] ?></a></td>
                            <td><?php echo $mostrar['Autor'] ?></td>
                            <td><?php echo $mostrar['Ano'] ?></td>
                            <td><?php echo $mostrar['Temporada'] ?></td>
                            <td>
                                <span class="status-badge 
                                <?php
                                if ($mostrar['Estado'] == 'Faltante') {
                                    echo 'status-faltante';
                                } elseif ($mostrar['Estado'] == 'ED Listo') {
                                    echo 'status-opening';
                                } elseif ($mostrar['Estado'] == 'Mix Listo') {
                                    echo 'status-finalizado';
                                }
                                ?>">
                                    <?php echo $mostrar['Estado']; ?>
                                </span>

                            </td>
                            <td><?php echo $mostrar['Mix'] ?></td>
                            <td class="action-buttons">
                                <button type="button" class="action-button bg-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#info<?php echo $mostrar['ID']; ?>">
                                    <i class="fa-solid fa-circle-info"></i>
                                </button>
                                <button type="button" class="action-button bg-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editpeli3<?php echo $mostrar['ID']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="action-button bg-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editpeli2<?php echo $mostrar['ID']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!--Ventana Modal para Actualizar--->
                    <?php
                        include('ModalEditar-ED.php');
                        include('ModalInfo-ED.php');
                        include('ModalDelete-ED.php');
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        function toggleFilter(type) {
            const filters = {
                mix: document.getElementById('mixFilter'),
                anime: document.getElementById('animeFilter'),
                song: document.getElementById('songFilter')
            };

            Object.entries(filters).forEach(([key, element]) => {
                if (key === type) {
                    element.classList.toggle('active');
                } else {
                    element.classList.remove('active');
                }
            });
        }

        $(document).ready(function() {
            $('#animeTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                order: [],
                pageLength: 10,
                responsive: true
            });
        });
    </script>
</body>



</html>