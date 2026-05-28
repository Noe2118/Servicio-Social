<?php require APP_PATH . '/views/layouts/header.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Reportes Bimestrales</h1>
<p class="page-subtitle">Sube tus reportes y evaluaciones cualitativas para cada periodo.</p>

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

<div class="alert alert-info border-0 shadow-sm rounded-3">
    <h5 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Instrucciones:</h5>
    <ul class="mb-0">
        <li>Cada bimestre es la continuación del anterior. Debes completar el Bimestre 1 para desbloquear el 2, y el 2 para el 3.</li>
        <li>Por cada bimestre debes descargar el formato en Word, llenar los datos que correspondan al periodo, y subir <strong>dos archivos en PDF</strong>:
            <ol class="mt-1 mb-1">
                <li><strong>Reporte Bimestral</strong> (Con la firma y horas).</li>
                <li><strong>Evaluación Cualitativa</strong> (La tabla de evaluación llenada por ti).</li>
            </ol>
        </li>
    </ul>
</div>

<div class="row g-4 mt-2">
    <?php for ($i = 1; $i <= 3; $i++): 
        $estado = $estadoBimestres[$i];
        $completado = $estado['docs_subidos'] >= 2;
    ?>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 <?= $estado['bloqueado'] ? 'opacity-75 bg-light' : '' ?>">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">Bimestre <?= $i ?></h5>
                    <?php if ($completado): ?>
                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check2-all me-1"></i>Enviado</span>
                    <?php elseif ($estado['bloqueado']): ?>
                        <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bi bi-lock-fill me-1"></i>Bloqueado</span>
                    <?php elseif (!$estado['habilitado']): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-clock-history me-1"></i>No Habilitado</span>
                    <?php else: ?>
                        <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bi bi-cloud-arrow-up me-1"></i>Pendiente</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body p-4">
                <?php if (isset($estado['config']['fecha_inicio']) && isset($estado['config']['fecha_fin'])): ?>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-calendar3 me-1"></i> Periodo: 
                        <strong><?= date('d/m/Y', strtotime($estado['config']['fecha_inicio'])) ?></strong> - 
                        <strong><?= date('d/m/Y', strtotime($estado['config']['fecha_fin'])) ?></strong>
                    </p>
                <?php else: ?>
                    <p class="text-muted small mb-3">Periodo aún no definido por la dependencia.</p>
                <?php endif; ?>

                <?php if ($completado): ?>
                    <div class="alert alert-success bg-success bg-opacity-10 border-0 mt-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i> Documentos subidos correctamente.
                    </div>
                <?php elseif ($estado['bloqueado']): ?>
                    <div class="alert alert-secondary bg-secondary bg-opacity-10 border-0 mt-3 small">
                        <i class="bi bi-lock-fill me-1"></i> Debes completar el bimestre anterior para desbloquear este.
                    </div>
                <?php elseif (!$estado['habilitado']): ?>
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mt-3 small">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> La dependencia aún no ha habilitado este bimestre.
                    </div>
                <?php else: ?>
                    <!-- Formulario de Subida -->
                    <form method="POST" action="<?= BASE_URL ?>/alumno/subir_documentos_bimestre" enctype="multipart/form-data" class="mt-3">
                        <input type="hidden" name="numero_bimestre" value="<?= $i ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">1. Reporte Bimestral (PDF)</label>
                            <input class="form-control form-control-sm" type="file" name="reporte_pdf" accept=".pdf" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">2. Evaluación Cualitativa (PDF)</label>
                            <input class="form-control form-control-sm" type="file" name="evaluacion_pdf" accept=".pdf" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            <i class="bi bi-upload me-1"></i> Subir Documentos
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
