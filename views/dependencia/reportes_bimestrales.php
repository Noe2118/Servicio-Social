<?php
$paginaActiva = 'reportes_bimestrales';
require APP_PATH . '/views/layouts/header_dependencia.php';
?>

<!-- MAIN CONTENT -->
<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Reportes y Evaluaciones Bimestrales</h2>
            <p class="text-muted mb-0">Revisa el progreso de tus alumnos asignados y sube las evaluaciones cualitativas por cada bimestre.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 shadow-sm rounded-3 mb-4">
        <h5 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Instrucciones para el Responsable:</h5>
        <ul class="mb-0">
            <li>Debe descargar el formato de Evaluación en Word, llenar los datos de la evaluación correspondiente al bimestre en curso (columnas 1, 2 o 3) y subirlo en formato PDF firmado.</li>
            <li>El mismo documento es continuación del anterior, por lo que el alumno también verá sus avances.</li>
            <li>Solo podrá subir la evaluación del Bimestre 2 si ya ha subido la del Bimestre 1.</li>
        </ul>
    </div>

    <div class="row">
        <!-- Left panel: Filters and Student List -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-2 fw-bold text-dark">Filtrar por Programa</h6>
                    <form method="GET" action="<?= BASE_URL ?>/dependencia/reportes_bimestrales" id="filterForm">
                        <select name="id_programa" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                            <?php if (empty($programas)): ?>
                                <option value="">No hay programas</option>
                            <?php else: ?>
                                <?php foreach ($programas as $prog): ?>
                                    <option value="<?= $prog['id_programa'] ?>" <?= $id_programa_seleccionado == $prog['id_programa'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($prog['nombre_programa']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
                
                <div class="card-body p-0 border-top">
                    <div class="p-3 border-bottom bg-white">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" placeholder="Buscar alumno..." id="searchInput" onkeyup="filterStudents()">
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush" id="studentList" style="max-height: 500px; overflow-y: auto;">
                        <?php if (empty($alumnos)): ?>
                            <div class="text-center p-4 text-muted small">
                                <i class="bi bi-person-x fs-3 d-block mb-2 text-light"></i>
                                No hay alumnos activos en este programa.
                            </div>
                        <?php else: ?>
                            <?php foreach ($alumnos as $al): ?>
                                <?php
                                    $isActive = ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']);
                                    $parts = explode(' ', trim($al['nombre_completo']));
                                    $initials = strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                                ?>
                                <a href="<?= BASE_URL ?>/dependencia/reportes_bimestrales?id_programa=<?= $id_programa_seleccionado ?>&id_alumno=<?= $al['id_alumno'] ?>" 
                                   class="list-group-item list-group-item-action d-flex align-items-center p-3 <?= $isActive ? 'active bg-primary text-white border-primary' : '' ?>"
                                   data-name="<?= strtolower($al['nombre_completo']) ?>">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                         style="width: 40px; height: 40px; font-weight: bold; font-size: 0.9rem; <?= $isActive ? 'background-color: rgba(255,255,255,0.2); color: white;' : 'background-color: #e9ecef; color: var(--bs-primary);' ?>">
                                        <?= $initials ?>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-1 text-truncate" style="font-size: 0.9rem;"><?= htmlspecialchars($al['nombre_completo']) ?></h6>
                                        <small class="text-truncate d-block <?= $isActive ? 'text-white-50' : 'text-muted' ?>" style="font-size: 0.75rem;">
                                            <?= htmlspecialchars($al['carrera']) ?>
                                        </small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right panel: Detail -->
        <div class="col-md-8 col-lg-9 mb-4">
            <?php if ($alumno_seleccionado): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                                <?php
                                    $pParts = explode(' ', trim($alumno_seleccionado['nombre_completo']));
                                    echo strtoupper(mb_substr($pParts[0], 0, 1));
                                ?>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1"><?= htmlspecialchars($alumno_seleccionado['nombre_completo']) ?></h4>
                                <p class="text-muted mb-0"><i class="bi bi-person-badge me-1"></i> No. Control: <?= htmlspecialchars($alumno_seleccionado['no_control']) ?> | <i class="bi bi-mortarboard me-1"></i> <?= htmlspecialchars($alumno_seleccionado['carrera']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3"><i class="bi bi-clipboard2-check text-primary me-2"></i>Evaluaciones del Responsable</h5>
                
                <div class="row g-4">
                    <?php for ($i = 1; $i <= 3; $i++): 
                        $estado = $estadoBimestres[$i];
                        $completado = $estado['evaluacion_subida'];
                    ?>
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 <?= $estado['bloqueado'] ? 'opacity-75 bg-light' : '' ?>">
                            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0 text-primary">Bimestre <?= $i ?></h5>
                                    <?php if ($completado): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>Evaluado</span>
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
                                <?php if ($completado): ?>
                                    <div class="alert alert-success bg-success bg-opacity-10 border-0 mt-2 mb-3 small">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i> Evaluación subida correctamente.
                                    </div>
                                    <a href="<?= BASE_URL ?><?= htmlspecialchars($estado['documento']['ruta_servidor'] ?? '') ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 fw-bold">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Ver PDF de Evaluación
                                    </a>
                                <?php elseif ($estado['bloqueado']): ?>
                                    <div class="alert alert-secondary bg-secondary bg-opacity-10 border-0 mt-3 small text-center">
                                        <i class="bi bi-lock-fill d-block fs-4 mb-2"></i> 
                                        Debes subir la evaluación del bimestre anterior para desbloquear este.
                                    </div>
                                <?php elseif (!$estado['habilitado']): ?>
                                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mt-3 small text-center">
                                        <i class="bi bi-exclamation-triangle-fill d-block fs-4 mb-2"></i> 
                                        El bimestre no está habilitado en la configuración de este programa.
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="<?= BASE_URL ?>/dependencia/subir_evaluacion_bimestral" enctype="multipart/form-data" class="mt-3">
                                        <input type="hidden" name="id_alumno" value="<?= $alumno_seleccionado['id_alumno'] ?>">
                                        <input type="hidden" name="id_programa" value="<?= $id_programa_seleccionado ?>">
                                        <input type="hidden" name="numero_bimestre" value="<?= $i ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark">Subir Evaluación Cualitativa (PDF)</label>
                                            <input class="form-control form-control-sm" type="file" name="evaluacion_pdf" accept=".pdf" required>
                                            <div class="form-text" style="font-size: 0.7rem;">Sube el documento de evaluación llenado y firmado correspondiente al Bimestre <?= $i ?>.</div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                            <i class="bi bi-upload me-1"></i> Subir Evaluación
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

            <?php else: ?>
                <div class="card shadow-sm border-0 h-100 d-flex align-items-center justify-content-center bg-light">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-person-lines-fill text-muted" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mt-3 text-dark">Ningún alumno seleccionado</h4>
                        <p class="text-muted">Selecciona un alumno de la lista lateral para visualizar y subir sus evaluaciones bimestrales.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function filterStudents() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    const items = document.querySelectorAll('#studentList a');
    items.forEach(item => {
        const name = item.getAttribute('data-name') || '';
        if (name.includes(filter)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php require APP_PATH . '/views/layouts/footer_dependencia.php'; ?>
