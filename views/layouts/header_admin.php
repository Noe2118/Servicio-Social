<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Portal Administrativo') ?> - ITCH Servicio Social</title>
    <meta name="description" content="Sistema de Gestión del Servicio Social - Instituto Tecnológico de Chetumal - Portal Administrativo">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --itch-primary: #0F2B5B;
            --itch-primary-light: #1a3f7a;
            --itch-accent: #0b5ed7;
            --itch-success: #198754;
            --itch-warning: #c9a825;
            --itch-sidebar-width: 240px;
            --itch-navbar-height: 56px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        /* ===== Top Navbar ===== */
        .top-navbar {
            background-color: var(--itch-primary);
            height: var(--itch-navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .top-navbar .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            text-decoration: none;
        }

        .top-navbar .navbar-search {
            flex: 0 1 360px;
        }

        .top-navbar .navbar-search .form-control {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 20px;
            padding-left: 2.2rem;
            font-size: 0.85rem;
        }

        .top-navbar .navbar-search .form-control::placeholder {
            color: rgba(255,255,255,0.55);
        }

        .top-navbar .navbar-search .form-control:focus {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.3);
            box-shadow: none;
            color: #fff;
        }

        .top-navbar .navbar-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.55);
            font-size: 0.9rem;
        }

        .top-navbar .navbar-actions a,
        .top-navbar .navbar-actions button {
            color: rgba(255,255,255,0.8);
            font-size: 1.2rem;
            margin-left: 0.75rem;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s;
        }

        .top-navbar .navbar-actions a:hover,
        .top-navbar .navbar-actions button:hover {
            color: #fff;
        }

        .top-navbar .navbar-actions .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--itch-accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 0.75rem;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: var(--itch-navbar-height);
            left: 0;
            width: var(--itch-sidebar-width);
            height: calc(100vh - var(--itch-navbar-height));
            background: #fff;
            border-right: 1px solid #e5e7eb;
            z-index: 1020;
            display: flex;
            flex-direction: column;
            padding-top: 1.25rem;
            overflow-y: auto;
        }

        .sidebar .sidebar-header {
            padding: 0 1.25rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0.5rem;
        }

        .sidebar .sidebar-header h6 {
            color: var(--itch-accent);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.1rem;
        }

        .sidebar .sidebar-header small {
            color: #6b7280;
            font-size: 0.75rem;
        }

        .sidebar .nav-link {
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.65rem 1.25rem;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: #f0f4ff;
            color: var(--itch-accent);
        }

        .sidebar .nav-link.active {
            color: var(--itch-accent);
            background-color: #e8f0fe;
            border-left-color: var(--itch-accent);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: var(--itch-sidebar-width);
            margin-top: var(--itch-navbar-height);
            padding: 2rem 2.5rem;
            min-height: calc(100vh - var(--itch-navbar-height));
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--itch-primary);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 1.75rem;
        }

        /* ===== Card Styles ===== */
        .card-custom {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* ===== Badge Styles ===== */
        .badge-modalidad-presencial {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-modalidad-virtual {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-modalidad-hibrida {
            background-color: #ede9fe;
            color: #5b21b6;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-estado-activo {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-estado-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-estado-concluido {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* ===== Admin-specific Styles ===== */
        .sidebar .btn-new-program {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1rem;
        }

        .kpi-card {
            border-radius: 12px;
            padding: 1.5rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .kpi-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .kpi-number {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .kpi-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
        }

        .kpi-sublabel {
            font-size: 0.78rem;
            color: #6b7280;
        }

        /* Review list items */
        .review-item {
            padding: 0.85rem 1rem;
            border-left: 3px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .review-item:hover {
            background-color: #f9fafb;
        }

        .review-item.active {
            background-color: #eff6ff;
            border-left-color: var(--itch-accent);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        /* PDF viewer */
        .pdf-viewer {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .pdf-viewer-header {
            background: #1f2937;
            color: #fff;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Timeline */
        .timeline-item {
            display: flex;
            gap: 0.75rem;
            padding-bottom: 1rem;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 7px;
            top: 20px;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 3px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- ===== Top Navbar ===== -->
<nav class="top-navbar" id="topNavbar">
    <div class="d-flex align-items-center">
        <!-- Mobile toggle -->
        <button class="d-lg-none me-3 text-white border-0 bg-transparent" type="button" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <a href="<?= BASE_URL ?>/dgtyv/dashboard" class="navbar-brand">ITCH / TecNM</a>
    </div>

    <div class="navbar-search position-relative d-none d-md-block">
        <i class="bi bi-search search-icon"></i>
        <input type="text" class="form-control" placeholder="Buscar alumnos, programas...">
    </div>

    <div class="navbar-actions d-flex align-items-center">
        <a href="#" title="Notificaciones"><i class="bi bi-bell"></i></a>
        <a href="#" title="Configuración"><i class="bi bi-gear"></i></a>
        <div class="user-avatar" title="Administrador">A</div>
    </div>
</nav>

<!-- ===== Sidebar ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h6><i class="bi bi-mortarboard me-1"></i> Servicio Social</h6>
        <small>Portal Administrativo</small>
    </div>
    <nav class="mt-1 flex-grow-1">
        <a href="<?= BASE_URL ?>/dgtyv/dashboard" class="nav-link <?= ($paginaActiva ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid"></i> Dashboard DGTyV
        </a>
        <a href="<?= BASE_URL ?>/dgtyv/programas" class="nav-link <?= ($paginaActiva ?? '') === 'programas' ? 'active' : '' ?>">
            <i class="bi bi-clipboard-check"></i> Aprobar Programas
        </a>
        <a href="<?= BASE_URL ?>/dgtyv/catalogo_programas" class="nav-link <?= ($paginaActiva ?? '') === 'catalogo_programas' ? 'active' : '' ?>">
            <i class="bi bi-collection"></i> Catálogo de Programas
        </a>
        <a href="<?= BASE_URL ?>/dgtyv/expedientes" class="nav-link <?= ($paginaActiva ?? '') === 'expedientes' ? 'active' : '' ?>">
            <i class="bi bi-folder2-open"></i> Revisión de Expedientes
        </a>
        <a href="<?= BASE_URL ?>/dgtyv/ciclos" class="nav-link <?= ($paginaActiva ?? '') === 'ciclos' ? 'active' : '' ?>">
            <i class="bi bi-calendar-range"></i> Gestión de Ciclos
        </a>
        <a href="<?= BASE_URL ?>/dgtyv/liberacion" class="nav-link <?= ($paginaActiva ?? '') === 'liberacion' ? 'active' : '' ?>">
            <i class="bi bi-award"></i> Liberación
        </a>
    </nav>
    <div class="mt-auto p-3 border-top">
        <a href="<?= BASE_URL ?>/dgtyv/nuevo_programa" class="btn btn-dark btn-new-program w-100 mb-2">
            <i class="bi bi-plus-lg"></i> Nuevo Programa
        </a>
        <a href="<?= BASE_URL ?>/auth/logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </div>
</aside>

<!-- ===== Main Content Area ===== -->
<main class="main-content" id="mainContent">
