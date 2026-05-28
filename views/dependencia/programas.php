<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Programas - Dependencia | ITCH Servicio Social</title>
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
        .topnav-search { flex: 0 1 380px; margin: 0 auto; position: relative; }
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

        /* ===== PAGE HEADER ===== */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .page-header h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .page-header p { font-size: .88rem; color: var(--text-muted); margin: 0; }
        .btn-accent {
            background: var(--navy); color: #fff; border: none; border-radius: 8px;
            padding: 10px 20px; font-size: .85rem; font-weight: 600;
            display: flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap;
        }
        .btn-accent:hover { background: var(--navy-light); color: #fff; }

        /* ===== TABLE CARD (Stitch mockup) ===== */
        .table-card {
            background: #fff; border-radius: 10px; border: 1px solid var(--border); overflow: hidden;
        }
        .table-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 24px; border-bottom: 1px solid var(--border);
        }
        .table-header h5 { font-size: 1rem; font-weight: 700; margin: 0; }
        .table-header .filter-btn {
            background: none; border: 1px solid var(--border); border-radius: 6px;
            padding: 5px 14px; font-size: .82rem; color: var(--text-muted);
            cursor: pointer; display: flex; align-items: center; gap: 6px;
        }
        .table-header .filter-btn:hover { background: #f8fafc; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            font-size: .72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .3px; color: var(--text-muted);
            padding: 12px 24px; border-bottom: 1px solid var(--border);
            text-align: left;
        }
        .data-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: #fafbfd; }
        .data-table tbody td { padding: 16px 24px; font-size: .88rem; vertical-align: middle; }
        .program-name { color: var(--navy); font-weight: 600; }

        /* Progress bar inline */
        .cupos-cell { display: flex; align-items: center; gap: 10px; }
        .cupos-text { font-size: .88rem; white-space: nowrap; }
        .cupos-text.full { color: #dc3545; font-weight: 600; }
        .progress-inline { width: 100px; height: 5px; border-radius: 3px; background: #e9ecef; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 3px; transition: width .3s; }
        .progress-fill.green { background: #22c55e; }
        .progress-fill.red { background: #dc3545; }
        .progress-fill.gray { background: #94a3b8; }

        /* Badge */
        .badge-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 20px;
            font-size: .78rem; font-weight: 600;
        }
        .badge-aprobado { background: #dcfce7; color: #15803d; }
        .badge-revision { background: #f1f5f9; color: #64748b; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-aprobado .badge-dot { background: #22c55e; }
        .badge-revision .badge-dot { background: #94a3b8; }

        .actions-btn {
            background: none; border: none; color: var(--text-muted);
            font-size: 1.1rem; cursor: pointer; padding: 4px 8px; border-radius: 4px;
        }
        .actions-btn:hover { background: #f1f5f9; }

        /* Footer */
        .table-footer {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 24px; border-top: 1px solid var(--border);
            font-size: .82rem; color: var(--text-muted);
        }
        .table-footer .pagination-btns { display: flex; gap: 6px; }
        .table-footer .pagination-btns button {
            background: #fff; border: 1px solid var(--border); border-radius: 4px;
            width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-muted); font-size: .8rem;
        }
        .table-footer .pagination-btns button:hover { background: #f8fafc; }
    </style>
</head>
<body>

<!-- TOP NAV -->
<nav class="topnav">
    <span class="topnav-brand">ITCH/TecNM</span>
    <div class="topnav-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Buscar programas...">
    </div>
    <div class="topnav-right">
        <i class="fa-regular fa-bell"></i>
        <div class="topnav-avatar">O</div>
        <span style="color:rgba(255,255,255,.85); font-size:.82rem;">Organización <i class="fa-solid fa-chevron-down" style="font-size:.65rem;"></i></span>
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
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Gestión de Cupos</h1>
            <p>Administra los programas activos y revisa la disponibilidad de cupos.</p>
        </div>
        <a href="<?= BASE_URL ?>/dependencia/crearPrograma" class="btn-accent" style="text-decoration:none;"><i class="fa-solid fa-plus"></i> Proponer Nuevo Programa</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Acción realizada con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['error']) && $_GET['error'] == 'mes_invalido'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> Solo se permite desactivar programas en periodo inhábil (Agosto o Diciembre).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h5>Programas Actuales</h5>
            <button class="filter-btn"><i class="fa-solid fa-filter"></i> Filtrar</button>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre del Programa</th>
                    <th>Modalidad</th>
                    <th>Cupos Ocupados/Totales</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($programas)): ?>
                    <?php foreach ($programas as $p): ?>
                        <?php
                            $pct = ($p['cupos_totales'] > 0) ? ($p['cupos_ocupados'] / $p['cupos_totales']) * 100 : 0;
                            $isFull = ($pct >= 100);
                            $barClass = $isFull ? 'red' : ($pct > 0 ? 'green' : 'gray');
                        ?>
                        <tr>
                            <td class="program-name"><?= htmlspecialchars($p['nombre_programa']) ?></td>
                            <td><?= htmlspecialchars($p['modalidad']) ?></td>
                            <td>
                                <div class="cupos-cell">
                                    <span class="cupos-text <?= $isFull ? 'full' : '' ?>"><?= (int)$p['cupos_ocupados'] ?> / <?= (int)$p['cupos_totales'] ?></span>
                                    <div class="progress-inline">
                                        <div class="progress-fill <?= $barClass ?>" style="width: <?= min($pct, 100) ?>%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($p['estado_aprobacion'] === 'Aprobado'): ?>
                                    <span class="badge-status badge-aprobado"><span class="badge-dot"></span> Aprobado</span>
                                <?php else: ?>
                                    <span class="badge-status badge-revision"><span class="badge-dot"></span> En Revisión por DGTyV</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="actions-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/dependencia/configurar_bimestres?id=<?= $p['id_programa'] ?>"><i class="fa-regular fa-calendar-check text-success me-2"></i> Configurar Bimestres</a></li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/dependencia/editarPrograma?id=<?= $p['id_programa'] ?>"><i class="fa-solid fa-pen text-primary me-2"></i> Editar</a></li>
                                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/dependencia/eliminarPrograma?id=<?= $p['id_programa'] ?>" onclick="return confirm('¿Estás seguro de desactivar y eliminar este programa? Todos los alumnos inscritos serán desvinculados.')"><i class="fa-solid fa-trash-can me-2"></i> Desactivar</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">No hay programas registrados para esta dependencia.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="table-footer">
            <span>Mostrando 1-<?= count($programas) ?> de <?= count($programas) ?> programas</span>
            <div class="pagination-btns">
                <button><i class="fa-solid fa-chevron-left"></i></button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
