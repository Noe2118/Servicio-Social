<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Gestión de Ciclos</h1>
        <p class="page-subtitle">Crea y administra los ciclos para los programas de servicio social.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCiclo">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Ciclo
    </button>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NOMBRE DEL CICLO</th>
                        <th>FECHA INICIO</th>
                        <th>FECHA FIN</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ciclos)): ?>
                        <?php foreach ($ciclos as $c): ?>
                            <?php 
                                $hoy = date('Y-m-d');
                                $estado = ($hoy >= $c['fecha_inicio'] && $hoy <= $c['fecha_fin']) ? 'Activo' : ($hoy > $c['fecha_fin'] ? 'Finalizado' : 'Próximo');
                                $badgeClass = $estado === 'Activo' ? 'bg-success' : ($estado === 'Próximo' ? 'bg-info text-dark' : 'bg-secondary');
                            ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark"><?= htmlspecialchars($c['nombre_ciclo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['fecha_fin'])) ?></td>
                                <td><span class="badge <?= $badgeClass ?> rounded-pill"><?= $estado ?></span></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/dgtyv/eliminar_ciclo?id=<?= $c['id_ciclo'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este ciclo? Se eliminará la relación con los programas.');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3" style="color: #cbd5e1;"></i>
                                No hay ciclos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Ciclo -->
<div class="modal fade" id="modalNuevoCiclo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/dgtyv/guardar_ciclo" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color:var(--itch-primary);">Registrar Nuevo Ciclo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nombre del Ciclo</label>
                        <input type="text" name="nombre_ciclo" class="form-control" required placeholder="Ej. Enero - Junio 2026">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Ciclo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
