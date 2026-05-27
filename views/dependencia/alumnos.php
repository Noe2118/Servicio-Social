<?php require APP_PATH . '/views/layouts/header_dependencia.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Alumnos Aceptados</h1>
<p class="page-subtitle">Gestiona a los alumnos que actualmente están realizando su servicio en tu dependencia.</p>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="card-custom">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="fw-semibold">No. Control</th>
                        <th class="fw-semibold">Nombre del Alumno</th>
                        <th class="fw-semibold">Carrera</th>
                        <th class="fw-semibold">Programa Asignado</th>
                        <th class="fw-semibold">Fecha de Inicio</th>
                        <th class="fw-semibold text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alumnos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No hay alumnos activos en tus programas actualmente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alumnos as $al): ?>
                            <tr>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($al['no_control'] ?? '') ?></td>
                                <td><?= htmlspecialchars($al['nombre_completo'] ?? '') ?></td>
                                <td><?= htmlspecialchars($al['carrera'] ?? '') ?></td>
                                <td>
                                    <span class="badge bg-light border text-dark">
                                        <?= htmlspecialchars($al['nombre_programa'] ?? '') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($al['fecha_asignacion'] ?? '') ?></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= BASE_URL ?>/dependencia/perfil_alumno/<?= $al['id_alumno'] ?>" class="btn btn-sm btn-outline-primary" title="Ver Perfil y Expediente">
                                            <i class="bi bi-person-lines-fill"></i> Perfil
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalBaja<?= $al['id_alumno'] ?>" title="Dar de Baja">
                                            <i class="bi bi-person-x-fill"></i> Baja
                                        </button>
                                    </div>

                                    <!-- Modal de Baja Dependencia -->
                                    <div class="modal fade" id="modalBaja<?= $al['id_alumno'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog text-start">
                                            <div class="modal-content">
                                                <form method="POST" action="<?= BASE_URL ?>/dependencia/dar_baja_alumno">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Dar de Baja</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Estás a punto de dar de baja a <strong><?= htmlspecialchars($al['nombre_completo']) ?></strong> del programa <em><?= htmlspecialchars($al['nombre_programa']) ?></em>.</p>
                                                        <input type="hidden" name="id_alumno" value="<?= (int)($al['id_alumno']) ?>">
                                                        <input type="hidden" name="id_programa" value="<?= (int)($al['id_programa']) ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Motivo de la baja (Obligatorio)</label>
                                                            <textarea name="motivo" class="form-control" rows="3" required placeholder="Escribe la justificación de la baja..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Confirmar Baja</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_dependencia.php'; ?>
