<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Resumen Operativo</h1>
<p class="page-subtitle">Bienvenido, Jefe del DGTyV. Aquí tienes un panorama general de las actividades pendientes.</p>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <!-- Programas por Aprobar -->
    <div class="col-lg-4">
        <a href="<?= BASE_URL ?>/dgtyv/programas" class="kpi-card text-decoration-none d-block" style="color: inherit;">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="kpi-label mb-1">Programas por Aprobar</p>
                    <h2 class="kpi-number" style="color: var(--itch-accent);"><?= (int)($programasPendientes ?? 0) ?></h2>
                    <span class="kpi-sublabel text-muted">requieren atención</span>
                </div>
                <div class="kpi-icon" style="background: #dbeafe; color: var(--itch-accent);">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>
        </a>
    </div>
    <!-- Documentos Pendientes -->
    <div class="col-lg-4">
        <a href="<?= BASE_URL ?>/dgtyv/expedientes" class="kpi-card text-decoration-none d-block" style="color: inherit;">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="kpi-label mb-1">Documentos Pendientes</p>
                    <h2 class="kpi-number" style="color: var(--itch-warning);"><?= (int)($documentosPendientes ?? 0) ?></h2>
                    <span class="kpi-sublabel text-muted">en revisión</span>
                </div>
                <div class="kpi-icon" style="background: #fef3c7; color: var(--itch-warning);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </a>
    </div>
    <!-- Alumnos para Liberación -->
    <div class="col-lg-4">
        <a href="<?= BASE_URL ?>/dgtyv/liberacion" class="kpi-card text-decoration-none d-block" style="color: inherit;">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="kpi-label mb-1">Alumnos para Liberación</p>
                    <h2 class="kpi-number" style="color: #0d9488;"><?= (int)($alumnosLiberacion ?? 0) ?></h2>
                    <span class="kpi-sublabel text-muted">listos para trámite final</span>
                </div>
                <div class="kpi-icon" style="background: #ccfbf1; color: #0d9488;">
                    <i class="bi bi-award"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Alerts & Activity -->
<div class="row g-4">
    <!-- Alertas Urgentes -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-body p-0">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-circle text-danger me-2"></i>Alertas Urgentes</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="font-size:0.78rem; font-weight:600; color:#6b7280;">ALUMNO</th>
                                <th style="font-size:0.78rem; font-weight:600; color:#6b7280;">DOCUMENTO</th>
                                <th style="font-size:0.78rem; font-weight:600; color:#6b7280;">RETRASO</th>
                                <th style="font-size:0.78rem; font-weight:600; color:#6b7280;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($alertasUrgentes)): ?>
                                <?php foreach ($alertasUrgentes as $alerta): ?>
                                    <?php
                                        // Calcular días de retraso usando la fecha de subida real
                                        $dias = max(0, floor((time() - strtotime($alerta['fecha_subida'])) / 86400));
                                        $badgeClass = $dias > 3 ? 'bg-danger' : 'bg-warning text-dark';
                                    ?>
                                    <tr>
                                        <td class="ps-3">
                                            <span class="fw-semibold"><?= htmlspecialchars($alerta['nombre_completo'] ?? '') ?></span>
                                            <br><small class="text-muted"><?= htmlspecialchars($alerta['no_control']) ?></small>
                                        </td>
                                        <td><span class="text-muted"><?= htmlspecialchars($alerta['tipo_documento']) ?></span></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= $dias ?> días</span></td>
                                        <td><a href="<?= BASE_URL ?>/dgtyv/expedientes?id=<?= $alerta['id_documento'] ?>" class="btn btn-sm btn-outline-primary">Revisar</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No hay alertas urgentes.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Actividad Reciente</h6>

                <?php if (!empty($actividades)): ?>
                    <?php foreach ($actividades as $actividad): ?>
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background: <?= $actividad['color'] ?>;"></div>
                            <div>
                                <p class="mb-0 fw-semibold" style="font-size:0.85rem;"><?= htmlspecialchars($actividad['titulo']) ?></p>
                                <small class="text-muted"><?= htmlspecialchars($actividad['descripcion']) ?></small>
                                <br><small class="text-muted"><?= $actividad['fecha_relativa'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No hay actividad reciente.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
