<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<div class="d-flex align-items-center justify-content-between mb-1">
    <h1 class="page-title mb-0">Bandeja de Entrada</h1>
</div>
<p class="page-subtitle">
    <?= count($programas ?? []) ?> programa<?= count($programas ?? []) !== 1 ? 's' : '' ?> pendiente<?= count($programas ?? []) !== 1 ? 's' : '' ?> de revisión
</p>

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

<div class="row g-4">
    <!-- Left: Program List -->
    <div class="col-lg-5">
        <div class="card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="font-size:0.75rem; font-weight:600; color:#6b7280;">DEPENDENCIA</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">NOMBRE DEL PROGRAMA</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">CUPOS</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">FECHA DE ENVÍO</th>
                                <th style="font-size:0.75rem; font-weight:600; color:#6b7280;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($programas)): ?>
                                <?php foreach ($programas as $prog): ?>
                                    <?php
                                        $isSelected = !empty($programaSeleccionado) && ($programaSeleccionado['id_programa'] ?? null) == ($prog['id_programa'] ?? null);
                                    ?>
                                    <tr style="<?= $isSelected ? 'background-color:#eff6ff;' : '' ?>">
                                        <td class="ps-3">
                                            <small class="text-muted"><?= htmlspecialchars($prog['nombre_dependencia'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold" style="font-size:0.85rem;"><?= htmlspecialchars($prog['nombre_programa'] ?? '') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?= (int)($prog['cupos'] ?? 0) ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($prog['fecha_envio'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/dgtyv/programas?id=<?= (int)($prog['id_programa'] ?? 0) ?>" class="btn btn-sm <?= $isSelected ? 'btn-primary' : 'btn-outline-primary' ?>">
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No hay programas pendientes de revisión.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Program Detail -->
    <div class="col-lg-7">
        <?php if (!empty($programaSeleccionado)): ?>
            <div class="card-custom">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge" style="background:#fef3c7; color:#92400e; font-weight:600; font-size:0.78rem; padding:0.4em 0.8em;">
                            <i class="bi bi-clock me-1"></i>EN REVISIÓN
                        </span>
                        <span class="text-muted" style="font-size:0.82rem;">
                            Folio: <strong><?= htmlspecialchars($programaSeleccionado['folio'] ?? 'N/A') ?></strong>
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($programaSeleccionado['nombre_programa'] ?? '') ?></h5>
                    <p class="text-muted mb-3" style="font-size:0.9rem;"><?= htmlspecialchars($programaSeleccionado['nombre_dependencia'] ?? '') ?></p>

                    <!-- Info Boxes -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#f8fafc; border:1px solid #e5e7eb;">
                                <small class="text-muted d-block mb-1">Modalidad</small>
                                <span class="fw-semibold"><?= htmlspecialchars($programaSeleccionado['modalidad'] ?? 'No especificada') ?></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#f8fafc; border:1px solid #e5e7eb;">
                                <small class="text-muted d-block mb-1">Cupos Solicitados</small>
                                <span class="fw-semibold"><?= (int)($programaSeleccionado['cupos'] ?? 0) ?> alumnos</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:0.05em; color:#6b7280;">Descripción del Programa</h6>
                        <p style="font-size:0.9rem; color:#374151; line-height:1.7;">
                            <?= nl2br(htmlspecialchars($programaSeleccionado['descripcion'] ?? 'Sin descripción disponible.')) ?>
                        </p>
                    </div>

                    <!-- Perfiles Requeridos -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:0.05em; color:#6b7280;">Perfiles Requeridos</h6>
                        <?php
                            $perfiles = $programaSeleccionado['perfiles_requeridos'] ?? '';
                            $lineas = array_filter(array_map('trim', explode("\n", $perfiles)));
                        ?>
                        <?php if (!empty($lineas)): ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($lineas as $linea): ?>
                                    <li class="d-flex align-items-start gap-2 mb-2" style="font-size:0.9rem;">
                                        <i class="bi bi-check-circle-fill text-success mt-1" style="font-size:0.85rem;"></i>
                                        <span><?= htmlspecialchars($linea) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted" style="font-size:0.9rem;">No se han especificado perfiles.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Responsable -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase" style="font-size:0.78rem; letter-spacing:0.05em; color:#6b7280;">Responsable del Programa</h6>
                        <div class="d-flex align-items-center gap-3">
                            <?php
                                $responsable = $programaSeleccionado['responsable_nombre'] ?? 'Sin asignar';
                                $partes = explode(' ', trim($responsable));
                                $iniciales = strtoupper(substr($partes[0] ?? '', 0, 1) . substr($partes[1] ?? $partes[0] ?? '', 0, 1));
                            ?>
                            <div style="width:40px; height:40px; border-radius:50%; background:var(--itch-primary); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:0.85rem;">
                                <?= $iniciales ?>
                            </div>
                            <div>
                                <span class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($responsable) ?></span>
                                <?php if (!empty($programaSeleccionado['responsable_cargo'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($programaSeleccionado['responsable_cargo']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <form method="POST" action="<?= BASE_URL ?>/dgtyv/aprobar_programa" class="flex-fill">
                            <input type="hidden" name="id_programa" value="<?= (int)($programaSeleccionado['id_programa'] ?? 0) ?>">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-lg me-1"></i>Aprobar Programa
                            </button>
                        </form>
                        <form method="POST" action="<?= BASE_URL ?>/dgtyv/rechazar_programa">
                            <input type="hidden" name="id_programa" value="<?= (int)($programaSeleccionado['id_programa'] ?? 0) ?>">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-lg me-1"></i>Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="card-custom">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard2-data" style="font-size:3rem; color:#d1d5db;"></i>
                    <h5 class="fw-bold mt-3 mb-2" style="color:#6b7280;">Selecciona un programa</h5>
                    <p class="text-muted" style="font-size:0.9rem;">Haz clic en "Ver" en un programa de la lista para revisar sus detalles.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
