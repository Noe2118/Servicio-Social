<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dependencia | ITCH Servicio Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f2b5b;
            --navy-light: #1a3a6e;
            --sidebar-w: 220px;
            --top-h: 52px;
            --gray-bg: #f5f7fa;
            --border: #e2e6ed;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-bg); color: var(--text-main); }

        /* ===== TOP NAV ===== */
        .topnav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            height: var(--top-h); background: var(--navy);
            display: flex; align-items: center; padding: 0 20px; gap: 20px;
        }
        .topnav-brand { color: #fff; font-weight: 700; font-size: .95rem; white-space: nowrap; }
        .topnav-search { flex: 0 1 480px; margin: 0 auto; position: relative; }
        .topnav-search input {
            width: 100%; border: none; border-radius: 20px;
            background: rgba(255,255,255,.12); color: #fff;
            padding: 6px 14px 6px 36px; font-size: .85rem;
        }
        .topnav-search input::placeholder { color: rgba(255,255,255,.55); }
        .topnav-search input:focus { outline: none; background: rgba(255,255,255,.2); }
        .topnav-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.55); font-size: .8rem; }
        .topnav-right { display: flex; align-items: center; gap: 16px; margin-left: auto; }
        .topnav-right > i { color: rgba(255,255,255,.75); font-size: 1.1rem; cursor: pointer; }
        .topnav-avatar {
            width: 32px; height: 32px; border-radius: 50%; background: #3b82f6;
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem; border: 2px solid rgba(255,255,255,.3);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed; top: var(--top-h); left: 0; bottom: 0;
            width: var(--sidebar-w); background: #fff; border-right: 1px solid var(--border);
            display: flex; flex-direction: column; padding-top: 20px; z-index: 90;
        }
        .sidebar-header { padding: 0 20px 16px; border-bottom: 1px solid var(--border); margin-bottom: 8px; }
        .sidebar-header h6 { font-size: .8rem; font-weight: 700; color: var(--navy); margin: 0; }
        .sidebar-header span { font-size: .7rem; color: var(--text-muted); }
        .sidebar-nav { list-style: none; padding: 0; flex: 1; }
        .sidebar-nav li a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 20px; font-size: .87rem; font-weight: 500;
            color: var(--text-muted); text-decoration: none;
            border-left: 3px solid transparent; transition: all .15s;
        }
        .sidebar-nav li a:hover { background: #f0f4fc; color: var(--navy); }
        .sidebar-nav li a.active { background: #eef3fc; color: var(--navy); font-weight: 600; border-left-color: var(--navy); }
        .sidebar-nav li a i { width: 18px; text-align: center; font-size: .95rem; }
        .sidebar-bottom { border-top: 1px solid var(--border); padding: 12px 0; }
        .sidebar-bottom a { display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: .85rem; font-weight: 500; color: #dc3545; text-decoration: none; }
        .sidebar-bottom a:hover { background: #fef2f2; }

        /* ===== MAIN ===== */
        .main { margin-left: var(--sidebar-w); margin-top: var(--top-h); padding: 28px 32px; }

        .card-custom {
            background: #fff; border-radius: 10px; border: 1px solid var(--border);
            overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .page-title {
            font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px;
        }
        .page-subtitle {
            font-size: .88rem; color: var(--text-muted); margin-bottom: 24px;
        }
    </style>
</head>
<body>

<!-- TOP NAV -->
<nav class="topnav">
    <span class="topnav-brand">ITCH/TecNM</span>
    <div class="topnav-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Buscar...">
    </div>
    <div class="topnav-right">
        <i class="fa-regular fa-bell"></i>
        <div class="topnav-avatar">D</div>
    </div>
</nav>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h6>Portal Vinculación</h6>
        <span>Organización Externa</span>
    </div>
    <ul class="sidebar-nav">
        <li><a href="<?= BASE_URL ?>/dependencia/dashboard"><i class="fa-regular fa-rectangle-list"></i> Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>/dependencia/misProgramas"><i class="fa-regular fa-folder-open"></i> Mis Programas</a></li>
        <li><a href="<?= BASE_URL ?>/dependencia/alumnos" class="<?= ($paginaActiva ?? '') === 'alumnos' ? 'active' : '' ?>"><i class="fa-solid fa-user-check"></i> Alumnos Aceptados</a></li>
        <li><a href="<?= BASE_URL ?>/dependencia/reportes_bimestrales" class="<?= ($paginaActiva ?? '') === 'reportes_bimestrales' ? 'active' : '' ?>"><i class="fa-regular fa-address-book"></i> Reportes Bimestrales</a></li>
    </ul>
    <div class="sidebar-bottom">
        <a href="<?= BASE_URL ?>/auth/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<div class="main">
