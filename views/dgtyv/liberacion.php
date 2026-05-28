<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- MAIN CONTENT -->
<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-award-fill text-primary me-2"></i>Reporte Final y Liberación</h2>
            <p class="text-muted mb-0">Evalúa el Reporte Final de los alumnos con 480 horas y emite su Constancia de Liberación Oficial.</p>
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

    <div class="row">
        <!-- Left panel: Student List -->
        <div class="col-md-4 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people me-1"></i> Alumnos Candidatos</h6>
                    <span class="badge bg-primary rounded-pill"><?= count($alumnos) ?></span>
                </div>
                
                <div class="card-body p-0">
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
                                No hay alumnos con 480 hrs en curso.
                            </div>
                        <?php else: ?>
                            <?php foreach ($alumnos as $al): ?>
                                <?php
                                    $isActive = ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']);
                                    $parts = explode(' ', trim($al['nombre_completo']));
                                    $initials = strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                                    
                                    $estadoFinal = $al['estado_reporte_final'] ?? 'No Subido';
                                ?>
                                <a href="<?= BASE_URL ?>/dgtyv/liberacion?id_alumno=<?= $al['id_alumno'] ?>" 
                                   class="list-group-item list-group-item-action d-flex align-items-center p-3 <?= $isActive ? 'active bg-primary text-white border-primary' : '' ?>"
                                   data-name="<?= strtolower($al['nombre_completo']) ?>">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                         style="width: 35px; height: 35px; font-weight: bold; font-size: 0.8rem; <?= $isActive ? 'background-color: rgba(255,255,255,0.2); color: white;' : 'background-color: #e9ecef; color: var(--itch-primary);' ?>">
                                        <?= $initials ?>
                                    </div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <h6 class="mb-1 text-truncate" style="font-size: 0.85rem;"><?= htmlspecialchars($al['nombre_completo']) ?></h6>
                                        <small class="text-truncate d-block <?= $isActive ? 'text-white-50' : 'text-muted' ?>" style="font-size: 0.7rem;">
                                            <?= htmlspecialchars($al['no_control']) ?> | <?= htmlspecialchars($al['nombre_programa']) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?php if ($estadoFinal === 'Pendiente'): ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i></span>
                                        <?php elseif ($estadoFinal === 'Aprobado'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                        <?php elseif ($estadoFinal === 'Rechazado'): ?>
                                            <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right panel: Detail -->
        <div class="col-md-8 col-lg-8 mb-4">
            <?php if ($alumno_seleccionado): ?>
                
                <?php
                $estadoActual = $alumno_seleccionado['estado_reporte_final'] ?? 'No Subido';
                $isAprobado = ($estadoActual === 'Aprobado' || $alumno_seleccionado['estado_servicio'] === 'Liberado');
                ?>

                <!-- Info Alumno -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 1.2rem; font-weight: bold;">
                                    <?php
                                        $pParts = explode(' ', trim($alumno_seleccionado['nombre_completo']));
                                        echo strtoupper(mb_substr($pParts[0], 0, 1));
                                    ?>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($alumno_seleccionado['nombre_completo']) ?></h4>
                                    <p class="text-muted mb-0 small"><i class="bi bi-person-badge me-1"></i> <?= htmlspecialchars($alumno_seleccionado['no_control']) ?> | <i class="bi bi-building me-1"></i> <?= htmlspecialchars($alumno_seleccionado['nombre_organizacion']) ?></p>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <span class="d-block small text-muted mb-1">Reporte Final</span>
                                <?php if($isAprobado): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Aprobado & Liberado</span>
                                <?php elseif($estadoActual === 'Rechazado'): ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i> Rechazado</span>
                                <?php elseif($estadoActual === 'Pendiente'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6"><i class="bi bi-clock-history me-1"></i> Por Revisar</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6"><i class="bi bi-dash-circle me-1"></i> No Subido</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Historial de documentos -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Documento del Alumno</h6>
                            </div>
                            <div class="card-body p-4 text-center">
                                <?php if($documentoFinal): ?>
                                    <i class="bi bi-file-earmark-pdf-fill text-danger mb-3" style="font-size: 4rem;"></i>
                                    <h5 class="fw-bold text-dark">Reporte Final de Servicio Social</h5>
                                    <p class="text-muted small mb-4">Subido el: <?= date('d/m/Y H:i', strtotime($documentoFinal['fecha_subida'])) ?></p>
                                    
                                    <a href="<?= BASE_URL ?><?= htmlspecialchars($documentoFinal['ruta_servidor']) ?>" target="_blank" class="btn btn-outline-danger w-100 fw-bold">
                                        <i class="bi bi-eye me-2"></i>Ver PDF Subido
                                    </a>
                                <?php else: ?>
                                    <i class="bi bi-file-earmark-x text-muted mb-3" style="font-size: 4rem;"></i>
                                    <h5 class="fw-bold text-muted">Aún no se ha subido</h5>
                                    <p class="text-muted small">El alumno todavía no carga su formato de Reporte Final en el sistema.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Resolución Final DGTyV -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100 <?= $isAprobado ? 'bg-success bg-opacity-10 border border-success' : '' ?>">
                            <div class="card-header bg-transparent border-bottom py-3">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i>Resolución y Liberación</h6>
                            </div>
                            <div class="card-body p-4">
                                <?php if($isAprobado): ?>
                                    <div class="text-center mb-3">
                                        <i class="bi bi-award-fill text-success" style="font-size: 4rem;"></i>
                                        <h5 class="fw-bold text-success mt-3">Alumno Liberado</h5>
                                        <p class="text-muted small">Has aprobado el reporte final y emitido su Constancia Oficial de Liberación.</p>
                                    </div>
                                <?php elseif(!$documentoFinal): ?>
                                    <div class="text-center mt-4">
                                        <i class="bi bi-lock-fill text-muted" style="font-size: 2.5rem;"></i>
                                        <p class="text-muted small mt-2">Debes esperar a que el alumno suba su documento para poder evaluarlo.</p>
                                    </div>
                                <?php else: ?>

                                    <?php if($estadoActual === 'Rechazado'): ?>
                                        <div class="alert alert-danger bg-danger bg-opacity-10 border-0 small mb-3">
                                            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Rechazado Anteriormente</strong><br>
                                            Motivo: <?= htmlspecialchars($alumno_seleccionado['motivo_rechazo_final'] ?? '') ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" action="<?= BASE_URL ?>/dgtyv/evaluar_reporte_final" enctype="multipart/form-data">
                                        <input type="hidden" name="id_alumno" value="<?= $alumno_seleccionado['id_alumno'] ?>">
                                        
                                        <!-- Rechazo -->
                                        <div class="mb-4 p-3 bg-light rounded border border-danger border-opacity-25">
                                            <label class="form-label small fw-bold text-danger"><i class="bi bi-x-circle me-1"></i> Rechazar Reporte Final</label>
                                            <textarea name="motivo_rechazo" class="form-control form-control-sm mb-2" rows="2" placeholder="Explique por qué se rechaza..."></textarea>
                                            <button type="submit" name="accion" value="Rechazar" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                                                Rechazar y Notificar
                                            </button>
                                        </div>

                                        <hr>

                                        <!-- Aprobacion y Constancia -->
                                        <div class="mb-3 p-3 bg-light rounded border border-success border-opacity-25">
                                            <label class="form-label small fw-bold text-success"><i class="bi bi-check-circle me-1"></i> Aprobar y Liberar</label>
                                            <p class="small text-muted mb-2">Sube la <strong>Constancia Oficial de Liberación</strong> en PDF firmada.</p>
                                            <input type="file" name="constancia_pdf" class="form-control form-control-sm mb-3" accept=".pdf">
                                            <button type="submit" name="accion" value="Aprobar" class="btn btn-success btn-sm w-100 fw-bold">
                                                Aprobar y Emitir Liberación
                                            </button>
                                        </div>
                                    </form>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="card shadow-sm border-0 h-100 d-flex align-items-center justify-content-center bg-light">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-person-bounding-box text-muted" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mt-3 text-dark">Ningún alumno seleccionado</h4>
                        <p class="text-muted">Selecciona un alumno de la lista de la izquierda para evaluar su Reporte Final y emitir su Constancia de Liberación.</p>
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

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
