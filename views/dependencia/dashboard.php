<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dependencia | ITCH Servicio Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .topnav-search {
            flex: 0 1 380px; margin: 0 auto; position: relative;
        }
        .topnav-search input {
            width: 100%; border: none; border-radius: 20px;
            background: rgba(255,255,255,.12); color: #fff;
            padding: 6px 14px 6px 36px; font-size: .85rem;
        }
        .topnav-search input::placeholder { color: rgba(255,255,255,.55); }
        .topnav-search input:focus { outline: none; background: rgba(255,255,255,.2); }
        .topnav-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.55); font-size: .8rem; }
        .topnav-right { display: flex; align-items: center; gap: 16px; margin-left: auto; }
        .topnav-right i { color: rgba(255,255,255,.75); font-size: 1.1rem; cursor: pointer; }
        .topnav-right i:hover { color: #fff; }
        .topnav-avatar {
            width: 32px; height: 32px; border-radius: 50%; background: #3b82f6;
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem; border: 2px solid rgba(255,255,255,.3);
        }
        .topnav-label { color: rgba(255,255,255,.85); font-size: .75rem; text-align: right; line-height: 1.2; }
        .topnav-label strong { display: block; color: #fff; font-size: .8rem; }

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
        .sidebar-nav li a.active {
            background: #eef3fc; color: var(--navy); font-weight: 600;
            border-left-color: var(--navy);
        }
        .sidebar-nav li a i { width: 18px; text-align: center; font-size: .95rem; }
        .sidebar-bottom { border-top: 1px solid var(--border); padding: 12px 0; }
        .sidebar-bottom a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; font-size: .85rem; font-weight: 500;
            color: #dc3545; text-decoration: none;
        }
        .sidebar-bottom a:hover { background: #fef2f2; }

        /* ===== MAIN ===== */
        .main { margin-left: var(--sidebar-w); margin-top: var(--top-h); padding: 28px 32px; }

        /* ===== PAGE HEADER ===== */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .page-header p { font-size: .88rem; color: var(--text-muted); margin: 0; }
        .btn-accent {
            background: var(--navy); color: #fff; border: none; border-radius: 8px;
            padding: 10px 20px; font-size: .85rem; font-weight: 600;
            display: flex; align-items: center; gap: 8px; cursor: pointer;
        }
        .btn-accent:hover { background: var(--navy-light); color: #fff; }

        /* ===== STAT CARDS (Stitch: 3 tarjetas con barra superior de color) ===== */
        .stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px; }
        .stat-card {
            background: #fff; border-radius: 10px; border: 1px solid var(--border);
            padding: 24px; position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        }
        .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .stat-card.green::before { background: linear-gradient(90deg, #22c55e, #4ade80); }
        .stat-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .stat-card .stat-label {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: var(--text-muted); margin-bottom: 12px;
        }
        .stat-card .stat-value {
            font-size: 2.8rem; font-weight: 800; color: var(--text-main); line-height: 1;
        }
        .stat-card .stat-icon {
            position: absolute; top: 20px; right: 20px;
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .stat-card.blue .stat-icon { background: #eff6ff; color: #3b82f6; }
        .stat-card.green .stat-icon { background: #f0fdf4; color: #22c55e; }
        .stat-card.amber .stat-icon { background: #fffbeb; color: #f59e0b; }

        /* ===== ACTIVITY LIST (Stitch: Actividad Reciente) ===== */
        .activity-card {
            background: #fff; border-radius: 10px; border: 1px solid var(--border); overflow: hidden;
        }
        .activity-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 24px; border-bottom: 1px solid var(--border);
        }
        .activity-header h5 {
            font-size: .95rem; font-weight: 700; margin: 0;
            display: flex; align-items: center; gap: 8px;
        }
        .activity-header a { font-size: .82rem; color: var(--text-muted); text-decoration: none; font-weight: 500; }
        .activity-header a:hover { color: var(--navy); }
        .activity-item {
            display: flex; align-items: center; gap: 14px;
            padding: 16px 24px; border-bottom: 1px solid #f1f5f9;
            cursor: pointer; transition: background .15s;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #f8fafc; }
        .activity-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0;
        }
        .activity-icon.blue { background: #eff6ff; color: #3b82f6; }
        .activity-icon.gray { background: #f1f5f9; color: #64748b; }
        .activity-icon.green { background: #f0fdf4; color: #22c55e; }
        .activity-text { flex: 1; }
        .activity-text p { margin: 0; font-size: .88rem; color: var(--text-main); }
        .activity-text p strong { font-weight: 600; }
        .activity-text small { font-size: .76rem; color: var(--text-muted); }
        .activity-arrow { color: #cbd5e1; font-size: .85rem; }
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
        <div class="topnav-label">
            <strong>DEPENDENCIA: DIF Municipal</strong>
            Administrador
        </div>
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
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Resumen</h1>
            <p>Bienvenido al panel de control de su dependencia.</p>
        </div>
        <button class="btn-accent"><i class="fa-solid fa-plus"></i> Nuevo Trámite</button>
    </div>

    <!-- Stats Row -->
    <div class="stat-row">
        <div class="stat-card blue">
            <div class="stat-label">Alumnos Activos</div>
            <div class="stat-value"><?= htmlspecialchars($alumnos_activos) ?></div>
            <div class="stat-icon"><i class="fa-solid fa-user-group"></i></div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Programas Aprobados</div>
            <div class="stat-value"><?= htmlspecialchars($programas_aprobados) ?></div>
            <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
        </div>
        <div class="stat-card amber">
            <div class="stat-label">Evaluaciones Pendientes</div>
            <div class="stat-value"><?= htmlspecialchars($evaluaciones_pendientes) ?></div>
            <div class="stat-icon"><i class="fa-regular fa-file-lines"></i></div>
        </div>
    </div>

    <!-- Activity -->
    <div class="activity-card">
        <div class="activity-header">
            <h5><i class="fa-regular fa-clock"></i> Actividad Reciente</h5>
            <a href="#">Ver todo</a>
        </div>

        <?php if ($alumnos_activos > 0 || $evaluaciones_pendientes > 0): ?>
        <div class="activity-item">
            <div class="activity-icon blue"><i class="fa-regular fa-file"></i></div>
            <div class="activity-text">
                <p><strong>Juan Pérez</strong> subió su reporte bimestral para revisión.</p>
                <small>Hace 2 horas · Programa: Desarrollo Web Frontend</small>
            </div>
            <i class="fa-solid fa-chevron-right activity-arrow"></i>
        </div>
        <div class="activity-item">
            <div class="activity-icon gray"><i class="fa-solid fa-gear"></i></div>
            <div class="activity-text">
                <p><strong>Sistema:</strong> Nuevo alumno asignado al programa de <strong>Soporte Técnico.</strong></p>
                <small>Ayer, 14:30 hrs · Automático</small>
            </div>
            <i class="fa-solid fa-chevron-right activity-arrow"></i>
        </div>
        <div class="activity-item">
            <div class="activity-icon green"><i class="fa-regular fa-circle-check"></i></div>
            <div class="activity-text">
                <p>Evaluación final del alumno <strong>María García</strong> ha sido aprobada.</p>
                <small>Hace 2 días · Programa: Análisis de Datos</small>
            </div>
            <i class="fa-solid fa-chevron-right activity-arrow"></i>
        </div>
        <?php else: ?>
        <div class="activity-item" style="justify-content:center;">
            <div class="activity-text text-center">
                <p class="text-muted">No hay actividad reciente registrada.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
