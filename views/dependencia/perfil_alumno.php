<?php require APP_PATH . '/views/layouts/header_dependencia.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Perfil del Alumno</h1>
<p class="page-subtitle">Información detallada y documentos iniciales del alumno asignado.</p>

<div class="mb-4">
    <a href="<?= BASE_URL ?>/dependencia/alumnos" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver a la lista
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: Alumno Info -->
    <div class="col-lg-4">
        <div class="card-custom mb-4">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($alumno['nombre_completo'] ?? '') ?></h5>
                <p class="text-muted mb-3">No. Control: <?= htmlspecialchars($alumno['no_control'] ?? '') ?></p>
                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                    <?= htmlspecialchars($asignacion['estado_asignacion'] ?? 'Activo') ?>
                </span>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Detalles Académicos</h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Carrera</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($alumno['carrera'] ?? '') ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Semestre</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($alumno['semestre'] ?? '') ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Teléfono</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($alumno['telefono'] ?? 'N/A') ?></span>
                </div>
                
                <h6 class="fw-bold mb-3 mt-4 border-bottom pb-2">Detalles de Asignación</h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Programa</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($asignacion['nombre_programa'] ?? '') ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Folio de Programa</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($asignacion['folio_programa'] ?? '') ?></span>
                </div>
                <div>
                    <small class="text-muted d-block">Fecha de Asignación</small>
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($asignacion['fecha_asignacion'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Documentos -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-folder2-open text-primary me-2"></i>Expediente y Documentos</h5>
                <!-- Expediente de Documentos Iniciales -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Documento</th>
                                <th>Archivo</th>
                                <th>Fecha de Subida</th>
                                <th>Estado (DGTyV)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $docIniciales = array_filter($documentos, function($d) {
                                    return stripos($d['tipo_documento'], 'Bimestral') === false && stripos($d['tipo_documento'], 'Evaluación Cualitativa') === false;
                                });
                                $docBimestrales = array_filter($documentos, function($d) {
                                    return stripos($d['tipo_documento'], 'Bimestral') !== false || stripos($d['tipo_documento'], 'Evaluación Cualitativa') !== false;
                                });
                            ?>
                            <?php if (empty($docIniciales)): ?>
                                <tr><td colspan="4" class="text-center text-muted">Aún no hay documentos iniciales subidos.</td></tr>
                            <?php else: ?>
                                <?php foreach ($docIniciales as $doc): ?>
                                    <tr>
                                        <td class="fw-semibold text-dark"><?= htmlspecialchars($doc['tipo_documento'] ?? '') ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?><?= htmlspecialchars($doc['ruta_servidor'] ?? '') ?>" target="_blank" class="text-decoration-none btn btn-sm btn-light border">
                                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i> Ver PDF
                                            </a>
                                        </td>
                                        <td class="text-muted"><?= htmlspecialchars($doc['fecha_subida'] ?? '') ?></td>
                                        <td>
                                            <?php
                                                $bgClass = 'bg-secondary';
                                                if (($doc['estado_validacion'] ?? '') === 'Aprobado') $bgClass = 'bg-success';
                                                elseif (($doc['estado_validacion'] ?? '') === 'Rechazado') $bgClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $bgClass ?>"><?= htmlspecialchars($doc['estado_validacion'] ?? '') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Evaluaciones y Reportes Bimestrales -->
                <div class="mt-5 border-top pt-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-clipboard2-check text-success me-2"></i>Historial de Reportes Bimestrales</h5>
                    <p class="text-muted">A continuación se muestran los reportes y evaluaciones cualitativas subidas por el alumno por cada periodo.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Documento / Periodo</th>
                                    <th>Archivo</th>
                                    <th>Fecha de Subida</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($docBimestrales)): ?>
                                    <tr><td colspan="3" class="text-center text-muted">Aún no hay reportes bimestrales subidos.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($docBimestrales as $doc): ?>
                                        <tr>
                                            <td class="fw-semibold text-dark"><?= htmlspecialchars($doc['tipo_documento'] ?? '') ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?><?= htmlspecialchars($doc['ruta_servidor'] ?? '') ?>" target="_blank" class="text-decoration-none btn btn-sm btn-light border">
                                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i> Ver PDF
                                                </a>
                                            </td>
                                            <td class="text-muted"><?= htmlspecialchars($doc['fecha_subida'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_dependencia.php'; ?>
