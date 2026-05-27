<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos y Evaluaciones - Dependencia | ITCH Servicio Social</title>
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

        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .page-header p { font-size: .88rem; color: var(--text-muted); margin: 0; }

        /* ===== MASTER-DETAIL LAYOUT ===== */
        .md-layout { display: flex; gap: 20px; align-items: flex-start; }

        /* Left: Student list */
        .student-list-panel {
            width: 260px; flex-shrink: 0; background: #fff;
            border-radius: 10px; border: 1px solid var(--border); overflow: hidden;
        }
        .student-search {
            padding: 12px; border-bottom: 1px solid var(--border);
        }
        .student-search input {
            width: 100%; border: 1px solid var(--border); border-radius: 6px;
            padding: 7px 10px 7px 32px; font-size: .82rem; background: #f8fafc;
        }
        .student-search input:focus { outline: none; border-color: #93c5fd; background: #fff; }
        .student-search { position: relative; }
        .student-search i { position: absolute; left: 22px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: .75rem; }

        .student-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; cursor: pointer;
            text-decoration: none; color: inherit;
            border-left: 3px solid transparent; transition: all .12s;
        }
        .student-item:hover { background: #f8fafc; }
        .student-item.active { background: var(--navy); color: #fff; border-left-color: var(--navy); }
        .student-initials {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .78rem; flex-shrink: 0;
        }
        .student-item:not(.active) .student-initials { background: #e2e8f0; color: var(--navy); }
        .student-item.active .student-initials { background: rgba(255,255,255,.2); color: #fff; }
        .student-info h6 { margin: 0; font-size: .85rem; font-weight: 600; }
        .student-info small { font-size: .72rem; }
        .student-item:not(.active) .student-info small { color: var(--text-muted); }
        .student-item.active .student-info small { color: rgba(255,255,255,.7); }

        /* Right: Detail panels */
        .detail-panel { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 20px; }

        .detail-card {
            background: #fff; border-radius: 10px; border: 1px solid var(--border);
            padding: 24px; overflow: hidden;
        }

        /* Profile header */
        .profile-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .profile-left { display: flex; gap: 16px; align-items: center; }
        .profile-photo {
            width: 64px; height: 64px; border-radius: 50%; background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 700; color: var(--navy); flex-shrink: 0;
        }
        .profile-name { font-size: 1.25rem; font-weight: 800; margin: 0 0 2px; }
        .profile-career { font-size: .85rem; color: var(--text-muted); margin: 0; display: flex; align-items: center; gap: 6px; }
        .profile-right { text-align: right; }
        .profile-right .no-control { font-size: .78rem; color: var(--text-muted); margin: 0; }
        .btn-outline-navy {
            border: 1px solid var(--border); border-radius: 8px; padding: 8px 16px;
            font-size: .82rem; font-weight: 600; color: var(--navy);
            background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }
        .btn-outline-navy:hover { background: #f0f4fc; }

        /* Info row */
        .info-row {
            display: flex; gap: 0; margin-top: 20px; padding-top: 16px;
            border-top: 1px solid var(--border);
        }
        .info-col { flex: 1; padding: 0 16px; }
        .info-col:not(:last-child) { border-right: 1px solid var(--border); }
        .info-col:first-child { padding-left: 0; }
        .info-col:last-child { padding-right: 0; }
        .info-col label { font-size: .72rem; color: var(--text-muted); font-weight: 500; display: block; margin-bottom: 4px; }
        .info-col .value { font-size: .9rem; font-weight: 600; color: var(--text-main); }
        .badge-active {
            background: #dcfce7; color: #15803d; font-size: .78rem;
            font-weight: 600; padding: 3px 10px; border-radius: 20px; display: inline-block;
        }

        /* Eval form card */
        .eval-title {
            display: flex; align-items: center; gap: 8px;
            font-size: 1.05rem; font-weight: 700; margin-bottom: 20px;
        }
        .eval-title i { color: var(--navy); }

        .form-label-custom { font-size: .8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
        .form-control-custom, .form-select-custom {
            border: 1px solid var(--border); border-radius: 8px;
            padding: 10px 14px; font-size: .88rem; width: 100%;
            background: #fff;
        }
        .form-control-custom:focus, .form-select-custom:focus { outline: none; border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
        textarea.form-control-custom { resize: vertical; min-height: 100px; }
        .hint-text { font-size: .75rem; color: var(--text-muted); margin-top: 4px; }

        .btn-submit {
            background: #16a34a; color: #fff; border: none; border-radius: 10px;
            padding: 12px 28px; font-size: .92rem; font-weight: 700;
            cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s;
        }
        .btn-submit:hover { background: #15803d; }

        /* Empty state */
        .empty-detail {
            background: #fff; border-radius: 10px; border: 1px solid var(--border);
            padding: 60px 20px; text-align: center; color: var(--text-muted);
        }
        .empty-detail i { font-size: 2.5rem; color: #cbd5e1; margin-bottom: 16px; }
        .empty-detail h4 { font-weight: 700; margin-bottom: 8px; color: var(--text-main); }
        .empty-detail p { font-size: .88rem; }

        /* Alert override */
        .alert-success-custom {
            background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
            border-radius: 8px; padding: 12px 20px; font-size: .88rem;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .alert-success-custom .close-btn {
            background: none; border: none; color: #166534; font-size: 1rem; cursor: pointer;
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
        <li><a href="<?= BASE_URL ?>/dependencia/alumnos" class="active"><i class="fa-regular fa-address-book"></i> Alumnos y Evaluaciones</a></li>
    </ul>
    <div class="sidebar-bottom">
        <a href="<?= BASE_URL ?>/auth/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<div class="main">
    <div class="page-header">
        <h1>Alumnos y Evaluaciones (Seguimiento)</h1>
        <p>Gestiona el seguimiento y realiza la evaluación cualitativa de los estudiantes asignados a tus proyectos de vinculación.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success-custom">
            <span><i class="fa-solid fa-circle-check me-2"></i> Evaluación guardada correctamente.</span>
            <button class="close-btn" onclick="this.parentElement.remove();">✕</button>
        </div>
    <?php endif; ?>

    <div class="md-layout">
        <!-- Left panel: student list -->
        <div class="student-list-panel">
            <div class="student-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Filtrar alumnos..." id="filterInput" onkeyup="filterStudents()">
            </div>
            <?php if (!empty($alumnos)): ?>
                <?php foreach ($alumnos as $al): ?>
                    <?php
                        $isActive = ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']);
                        $parts = explode(' ', trim($al['nombre_completo']));
                        $initials = '';
                        if (count($parts) >= 2) {
                            $initials = strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
                        } else {
                            $initials = strtoupper(mb_substr($al['nombre_completo'], 0, 2));
                        }
                    ?>
                    <a href="?alumno_id=<?= $al['id_alumno'] ?>" class="student-item <?= $isActive ? 'active' : '' ?>" data-name="<?= strtolower($al['nombre_completo']) ?>">
                        <div class="student-initials"><?= $initials ?></div>
                        <div class="student-info">
                            <h6><?= htmlspecialchars($al['nombre_completo']) ?></h6>
                            <small><?= htmlspecialchars(mb_strimwidth($al['carrera'], 0, 24, '...')) ?></small>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="padding: 30px 16px; text-align: center; color: var(--text-muted); font-size: .85rem;">
                    No hay alumnos asignados.
                </div>
            <?php endif; ?>
        </div>

        <!-- Right panel: detail -->
        <?php if ($alumno_seleccionado): ?>
        <div class="detail-panel">
            <!-- Student profile card -->
            <div class="detail-card">
                <div class="profile-header">
                    <div class="profile-left">
                        <div class="profile-photo">
                            <?php
                                $pParts = explode(' ', trim($alumno_seleccionado['nombre_completo']));
                                echo strtoupper(mb_substr($pParts[0], 0, 1));
                            ?>
                        </div>
                        <div>
                            <h2 class="profile-name"><?= htmlspecialchars($alumno_seleccionado['nombre_completo']) ?></h2>
                            <p class="profile-career"><i class="fa-solid fa-graduation-cap"></i> <?= htmlspecialchars($alumno_seleccionado['carrera']) ?></p>
                        </div>
                    </div>
                    <div class="profile-right">
                        <a href="#" class="btn-outline-navy"><i class="fa-regular fa-file-lines"></i> Ver Reportes del Alumno</a>
                        <p class="no-control mt-2">No. Control: <?= htmlspecialchars($alumno_seleccionado['no_control']) ?></p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-col">
                        <label>Programa</label>
                        <span class="value"><?= htmlspecialchars($alumno_seleccionado['nombre_programa']) ?></span>
                    </div>
                    <div class="info-col">
                        <label>Horas Acumuladas</label>
                        <span class="value"><?= isset($alumno_seleccionado['horas_completadas']) ? $alumno_seleccionado['horas_completadas'] : 0 ?> / 480 hrs</span>
                    </div>
                    <div class="info-col">
                        <label>Periodo</label>
                        <span class="value"><?= isset($alumno_seleccionado['periodo_actual']) ? htmlspecialchars($alumno_seleccionado['periodo_actual']) : 'Ago-Dic 2023' ?></span>
                    </div>
                    <div class="info-col" style="text-align:center;">
                        <label>Estado</label>
                        <span class="badge-active"><?= isset($alumno_seleccionado['estado_servicio']) ? htmlspecialchars($alumno_seleccionado['estado_servicio']) : 'En curso' ?></span>
                    </div>
                </div>
            </div>

            <!-- Evaluation form card -->
            <div class="detail-card">
                <div class="eval-title"><i class="fa-regular fa-clipboard"></i> Evaluación Cualitativa</div>

                <form action="<?= BASE_URL ?>/dependencia/guardarEvaluacion" method="POST">
                    <input type="hidden" name="id_alumno" value="<?= $alumno_seleccionado['id_alumno'] ?>">
                    <input type="hidden" name="id_programa" value="<?= $alumno_seleccionado['id_programa'] ?>">

                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <div style="flex: 1;">
                            <label class="form-label-custom">Nivel de Desempeño General</label>
                            <select name="nivel_desempeno" class="form-select-custom" required>
                                <option value="">Seleccione una calificación...</option>
                                <option value="Excelente">Excelente</option>
                                <option value="Notable">Notable</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Suficiente">Suficiente</option>
                                <option value="Insuficiente">Insuficiente</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label-custom">Fecha de Evaluación</label>
                            <input type="date" name="fecha_evaluacion" class="form-control-custom" value="<?= date('Y-m-d') ?>" readonly style="background: #f8fafc;">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label-custom">Comentarios del Supervisor</label>
                        <textarea name="comentarios_supervisor" class="form-control-custom" placeholder="Describa el progreso, fortalezas y áreas de oportunidad del alumno..." required></textarea>
                        <p class="hint-text">Requerido para evaluaciones "Regular" o "Insuficiente"</p>
                    </div>

                    <div style="text-align: right; margin-top: 24px;">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-circle-check"></i> Guardar Evaluación
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="detail-panel">
            <div class="empty-detail">
                <i class="fa-regular fa-address-card"></i>
                <h4>Ningún alumno seleccionado</h4>
                <p>Seleccione un alumno de la lista lateral para visualizar sus detalles y realizar su evaluación cualitativa.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterStudents() {
    const filter = document.getElementById('filterInput').value.toLowerCase();
    document.querySelectorAll('.student-item').forEach(item => {
        const name = item.getAttribute('data-name') || '';
        item.style.display = name.includes(filter) ? '' : 'none';
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
