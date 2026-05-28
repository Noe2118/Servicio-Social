<?php require APP_PATH . '/views/layouts/header.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Reporte Final y Liberación</h1>
<p class="page-subtitle">Sube tu formato de reporte final una vez concluidos tus 3 bimestres.</p>

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

<?php
    $estado_reportes_bimestrales = $asignacion['estado_reportes'] ?? 'Pendiente';
    $estado_final = $asignacion['estado_reporte_final'] ?? 'No Subido';
?>

<?php if ($estado_reportes_bimestrales !== 'Aprobado'): ?>
    <div class="alert alert-warning border-warning shadow-sm rounded-3">
        <h5 class="alert-heading fw-bold"><i class="bi bi-lock-fill me-2"></i>Sección Bloqueada</h5>
        <p class="mb-0">Aún no puedes subir tu reporte final. Debes tener tus 3 reportes bimestrales aprobados por la DGTyV para desbloquear esta sección.</p>
    </div>
<?php else: ?>

    <div class="row g-4">
        <!-- Izquierda: Formatos e Instrucciones -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-download text-primary me-2"></i>Descarga de Formatos</h5>
                    <p class="small text-muted mb-4">Descarga la plantilla oficial en Word para llenarla con tus datos y un ejemplo en PDF de cómo debe quedar.</p>
                    
                    <a href="<?= BASE_URL ?>/public/assets/Formato de Reporte Final de Servicio Social.docx" download class="btn btn-outline-primary mb-3 w-100 text-start">
                        <i class="bi bi-file-earmark-word me-2 fs-5 align-middle"></i>
                        Plantilla: Reporte Final (Word)
                    </a>
                    <a href="<?= BASE_URL ?>/public/assets/Ejemplo de Formato de Reporte Final de Servicio Social.pdf" download class="btn btn-outline-danger mb-3 w-100 text-start">
                        <i class="bi bi-file-earmark-pdf me-2 fs-5 align-middle"></i>
                        Ejemplo: Reporte Final (PDF)
                    </a>

                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-2"><i class="bi bi-info-circle-fill text-info me-2"></i>Instrucciones</h5>
                    <ul class="small text-muted mb-0">
                        <li>Llena el formato con los datos de tu servicio social.</li>
                        <li>Fírmalo y asegúrate de que tenga los sellos correspondientes de la dependencia.</li>
                        <li>Escanéalo y súbelo en formato <strong>PDF</strong>.</li>
                        <li>Espera la validación de la DGTyV. Si es aprobado, se te emitirá aquí mismo tu constancia de liberación oficial.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Derecha: Subida y Estatus -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-upload text-success me-2"></i>Subir Reporte Final</h5>
                    
                    <?php if ($estado_final === 'Rechazado'): ?>
                        <div class="alert alert-danger border-0 small mb-4">
                            <strong><i class="bi bi-x-circle-fill me-1"></i>Reporte Rechazado</strong><br>
                            Motivo: <?= htmlspecialchars($asignacion['motivo_rechazo_final'] ?? '') ?>
                            <br><br>Sube tu reporte corregido usando el formulario de abajo.
                        </div>
                    <?php endif; ?>

                    <?php if ($estado_final === 'Pendiente'): ?>
                        <div class="alert alert-warning border-0 text-center py-4 mb-0">
                            <i class="bi bi-clock-history fs-1 text-warning mb-2"></i>
                            <h5 class="fw-bold mb-1">En Revisión</h5>
                            <p class="small text-muted mb-3">Has subido tu reporte final. La DGTyV lo está evaluando.</p>
                            <?php if ($documentoFinalSubido): ?>
                                <a href="<?= BASE_URL ?><?= htmlspecialchars($documentoFinalSubido['ruta_servidor']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye me-1"></i>Ver documento subido
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($estado_final === 'Aprobado'): ?>
                        <div class="alert alert-success border-0 text-center py-4 mb-0">
                            <i class="bi bi-check-circle-fill fs-1 text-success mb-2"></i>
                            <h5 class="fw-bold mb-1">¡Servicio Social Liberado!</h5>
                            <p class="small text-muted mb-4">Tu reporte final fue aprobado y se ha emitido tu Constancia de Liberación oficial.</p>
                            
                            <?php if ($constanciaLiberacion): ?>
                                <a href="<?= BASE_URL ?><?= htmlspecialchars($constanciaLiberacion['ruta_servidor']) ?>" target="_blank" class="btn btn-success fw-bold py-2 px-4 shadow-sm">
                                    <i class="bi bi-download me-2"></i>Descargar Constancia
                                </a>
                            <?php else: ?>
                                <p class="text-danger small">Constancia en proceso de firma. Revisa más tarde.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>/alumno/subir_reporte_final" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Documento PDF (Reporte Final con firmas y sellos):</label>
                                <input type="file" name="reporte_final_pdf" class="form-control" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                                <i class="bi bi-cloud-arrow-up me-2"></i>Subir Reporte Final
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
