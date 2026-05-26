<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<?php
    $avatarColors = ['#f59e0b', '#6366f1', '#ec4899', '#14b8a6'];
?>

<!-- Page Title -->
<div class="d-flex align-items-center justify-content-between mb-1">
    <h1 class="page-title mb-0">Módulo de Liberación</h1>
</div>
<p class="page-subtitle">Alumnos que han completado el 100% de sus horas y tienen su expediente aprobado.</p>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/dgtyv/emitir_liberacion" id="formLiberacion">
    <!-- Search bar & Action Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="position-relative" style="flex: 0 1 320px;">
            <i class="bi bi-search position-absolute" style="left:12px; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>
            <input type="text" class="form-control" id="searchAlumnos" placeholder="Buscar alumno..."
                   style="padding-left:2.2rem; border-radius:8px; font-size:0.88rem;">
        </div>
        <button type="submit" class="btn btn-dark d-flex align-items-center gap-2">
            <i class="bi bi-printer"></i> Emitir Constancia de Liberación
        </button>
    </div>

    <!-- Table -->
    <div class="card-custom">
        <div class="card-body p-0">
            <?php if (!empty($alumnos)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="tablaAlumnos">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width:40px;">
                                    <input type="checkbox" class="form-check-input" id="selectAll" title="Seleccionar todos">
                                </th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">MATRÍCULA</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">NOMBRE</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">DEPENDENCIA</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">HORAS</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">ESTATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $i => $alumno): ?>
                                <?php
                                    $horas = (int)($alumno['horas_completadas'] ?? 0);
                                    $porcentaje = min(100, round(($horas / 500) * 100));
                                    $nombreCompleto = $alumno['nombre_completo'] ?? '';
                                    $partesNombre = explode(' ', trim($nombreCompleto));
                                    $iniciales = strtoupper(substr($partesNombre[0] ?? '', 0, 1) . substr($partesNombre[1] ?? $partesNombre[0] ?? '', 0, 1));
                                    $colorIndex = $i % count($avatarColors);
                                    $avatarBg = $avatarColors[$colorIndex];
                                ?>
                                <tr class="alumno-row">
                                    <td class="ps-3">
                                        <input type="checkbox" class="form-check-input alumno-check"
                                               name="alumnos[]" value="<?= (int)($alumno['id_alumno'] ?? 0) ?>">
                                    </td>
                                    <td>
                                        <span class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($alumno['no_control'] ?? '') ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:34px; height:34px; border-radius:50%; background:<?= $avatarBg ?>; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:0.75rem; flex-shrink:0;">
                                                <?= $iniciales ?>
                                            </div>
                                            <span class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($nombreCompleto) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($alumno['nombre_organizacion'] ?? '') ?></small>
                                    </td>
                                    <td style="min-width:140px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px; border-radius:3px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width:<?= $porcentaje ?>%"
                                                     aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="fw-semibold text-nowrap" style="font-size:0.78rem;">
                                                <?= $horas ?>/500
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($horas >= 480): ?>
                                            <span class="badge" style="background:#dcfce7; color:#166534; font-weight:600; font-size:0.75rem;">
                                                <i class="bi bi-check-circle me-1"></i>Aprobado
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background:#fef3c7; color:#92400e; font-weight:600; font-size:0.75rem;">
                                                En proceso
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2 border-top text-muted" style="font-size:0.82rem;">
                    Mostrando <?= count($alumnos) ?> de <?= count($alumnos) ?> estudiantes listos para liberación
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                    <p class="mb-0" style="font-size:0.95rem;">No hay alumnos que cumplan los requisitos para liberación.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
    // Select all checkboxes
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.alumno-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Search filter
    document.getElementById('searchAlumnos')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.alumno-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
