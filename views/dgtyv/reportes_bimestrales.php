<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- MAIN CONTENT -->
<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Revisión de Reportes Bimestrales</h2>
            <p class="text-muted mb-0">Evalúa el expediente bimestral de los alumnos, revisa la evaluación de la dependencia y emite la resolución final.</p>
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
        <!-- Left panel: Filters and Student List -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-2 fw-bold text-dark"><i class="bi bi-building me-1"></i> 1. Organización</h6>
                    <form method="GET" action="<?= BASE_URL ?>/dgtyv/reportes_bimestrales" id="filterFormOrg">
                        <select name="id_dependencia" class="form-select form-select-sm" onchange="document.getElementById('filterFormOrg').submit()">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($dependencias as $dep): ?>
                                <option value="<?= $dep['id_dependencia'] ?>" <?= $id_dependencia_sel == $dep['id_dependencia'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dep['nombre_organizacion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                
                <?php if ($id_dependencia_sel > 0): ?>
                <div class="card-header bg-light border-top border-bottom-0 py-3">
                    <h6 class="mb-2 fw-bold text-dark"><i class="bi bi-folder me-1"></i> 2. Programa</h6>
                    <form method="GET" action="<?= BASE_URL ?>/dgtyv/reportes_bimestrales" id="filterFormProg">
                        <input type="hidden" name="id_dependencia" value="<?= $id_dependencia_sel ?>">
                        <select name="id_programa" class="form-select form-select-sm" onchange="document.getElementById('filterFormProg').submit()">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($programas as $prog): ?>
                                <option value="<?= $prog['id_programa'] ?>" <?= $id_programa_sel == $prog['id_programa'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prog['nombre_programa']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                <?php endif; ?>
                
                <?php if ($id_programa_sel > 0): ?>
                <div class="card-body p-0 border-top">
                    <div class="p-3 border-bottom bg-white">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" placeholder="Buscar alumno..." id="searchInput" onkeyup="filterStudents()">
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush" id="studentList" style="max-height: 400px; overflow-y: auto;">
                        <?php if (empty($alumnos)): ?>
                            <div class="text-center p-4 text-muted small">
                                <i class="bi bi-person-x fs-3 d-block mb-2 text-light"></i>
                                No hay alumnos activos.
                            </div>
                        <?php else: ?>
                            <?php foreach ($alumnos as $al): ?>
                                <?php
                                    $isActive = ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']);
                                    $parts = explode(' ', trim($al['nombre_completo']));
                                    $initials = strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                                ?>
                                <a href="<?= BASE_URL ?>/dgtyv/reportes_bimestrales?id_dependencia=<?= $id_dependencia_sel ?>&id_programa=<?= $id_programa_sel ?>&id_alumno=<?= $al['id_alumno'] ?>" 
                                   class="list-group-item list-group-item-action d-flex align-items-center p-3 <?= $isActive ? 'active bg-primary text-white border-primary' : '' ?>"
                                   data-name="<?= strtolower($al['nombre_completo']) ?>">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                         style="width: 35px; height: 35px; font-weight: bold; font-size: 0.8rem; <?= $isActive ? 'background-color: rgba(255,255,255,0.2); color: white;' : 'background-color: #e9ecef; color: var(--itch-primary);' ?>">
                                        <?= $initials ?>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-1 text-truncate" style="font-size: 0.85rem;"><?= htmlspecialchars($al['nombre_completo']) ?></h6>
                                        <small class="text-truncate d-block <?= $isActive ? 'text-white-50' : 'text-muted' ?>" style="font-size: 0.7rem;">
                                            <?= htmlspecialchars($al['no_control']) ?>
                                        </small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right panel: Detail -->
        <div class="col-md-8 col-lg-9 mb-4">
            <?php if ($alumno_seleccionado): ?>
                
                <?php
                // Contar cuántos reportes bimestrales ha subido el alumno (deberían ser 6 en total si ya acabó: 3 reportes + 3 cualitativas)
                $docsAlumno = 0;
                $docsDep = 0;
                foreach($documentos_bimestrales as $doc) {
                    if (stripos($doc['tipo_documento'], 'OFICINA DE SERVICIO SOCIAL') !== false) continue; // Final DGTyV doc
                    if (stripos($doc['tipo_documento'], 'EVALUACIÓN CUALITATIVA DEL PRESTADOR DE SERVICIO SOCIAL') !== false) {
                        $docsDep++;
                    } else {
                        $docsAlumno++;
                    }
                }
                
                $estadoActual = $alumno_seleccionado['estado_reportes'] ?? 'Pendiente';
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
                                    <p class="text-muted mb-0 small"><i class="bi bi-person-badge me-1"></i> <?= htmlspecialchars($alumno_seleccionado['no_control']) ?> | <i class="bi bi-mortarboard me-1"></i> <?= htmlspecialchars($alumno_seleccionado['carrera']) ?></p>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <span class="d-block small text-muted mb-1">Estado de Reportes Bimestrales</span>
                                <?php if($estadoActual === 'Aprobado'): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Aprobado</span>
                                <?php elseif($estadoActual === 'Rechazado'): ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i> Rechazado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6"><i class="bi bi-clock-history me-1"></i> En Revisión</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Historial de documentos -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-folder2-open text-primary me-2"></i>Historial de Archivos Bimestrales</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <?php if(empty($documentos_bimestrales)): ?>
                                        <div class="p-4 text-center text-muted small">No hay reportes subidos aún.</div>
                                    <?php else: ?>
                                        <?php foreach($documentos_bimestrales as $doc): ?>
                                            <div class="list-group-item p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 small fw-bold">
                                                            <?php if(stripos($doc['tipo_documento'], 'EVALUACIÓN CUALITATIVA DEL PRESTADOR') !== false): ?>
                                                                <span class="badge bg-info text-dark me-1" title="Subido por la Dependencia">Dep</span>
                                                            <?php elseif(stripos($doc['tipo_documento'], 'OFICINA DE SERVICIO SOCIAL') !== false): ?>
                                                                <span class="badge bg-primary me-1" title="Subido por DGTyV">DGTyV</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary me-1" title="Subido por Alumno">Alu</span>
                                                            <?php endif; ?>
                                                            <?= htmlspecialchars($doc['tipo_documento']) ?>
                                                        </h6>
                                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> Subido: <?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?></small>
                                                    </div>
                                                    <a href="<?= BASE_URL ?><?= htmlspecialchars($doc['ruta_servidor']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolución Final DGTyV -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 h-100 <?= $estadoActual === 'Aprobado' ? 'bg-success bg-opacity-10 border border-success' : '' ?>">
                            <div class="card-header bg-transparent border-bottom py-3">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i>Resolución DGTyV</h6>
                            </div>
                            <div class="card-body p-4">
                                <?php if($estadoActual === 'Aprobado'): ?>
                                    <div class="text-center mb-3">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                        <h5 class="fw-bold text-success mt-3">Expediente Aprobado</h5>
                                        <p class="text-muted small">Has aprobado satisfactoriamente los reportes bimestrales de este alumno.</p>
                                    </div>
                                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="document.getElementById('formReevaluar').style.display = 'block'; this.style.display='none';">Reevaluar (Deshacer)</button>
                                <?php endif; ?>

                                <div id="formReevaluar" style="<?= $estadoActual === 'Aprobado' ? 'display: none;' : '' ?>">
                                    <?php if($estadoActual === 'Rechazado'): ?>
                                        <div class="alert alert-danger bg-danger bg-opacity-10 border-0 small mb-3">
                                            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Actualmente Rechazado</strong><br>
                                            Motivo: <?= htmlspecialchars($alumno_seleccionado['motivo_rechazo_reportes'] ?? '') ?><br>
                                            <span class="text-muted" style="font-size: 0.75rem;">El alumno fue notificado para re-subir sus archivos. Puedes aprobarlo ahora si ya corrigió el error.</span>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" action="<?= BASE_URL ?>/dgtyv/evaluar_reportes_alumno" enctype="multipart/form-data">
                                        <input type="hidden" name="id_alumno" value="<?= $alumno_seleccionado['id_alumno'] ?>">
                                        <input type="hidden" name="id_programa" value="<?= $id_programa_sel ?>">
                                        <input type="hidden" name="id_dependencia" value="<?= $id_dependencia_sel ?>">
                                        
                                        <!-- Rechazo -->
                                        <div class="mb-4 p-3 bg-light rounded border border-danger border-opacity-25">
                                            <label class="form-label small fw-bold text-danger"><i class="bi bi-x-circle me-1"></i> Rechazar Paquete</label>
                                            <textarea name="motivo_rechazo" class="form-control form-control-sm mb-2" rows="2" placeholder="Explique por qué se rechazan los reportes (necesario si va a rechazar)..."></textarea>
                                            <button type="submit" name="accion" value="Rechazar" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                                                Rechazar y Notificar al Alumno
                                            </button>
                                        </div>

                                        <hr>

                                        <!-- Aprobacion -->
                                        <div class="mb-3 p-3 bg-light rounded border border-success border-opacity-25">
                                            <label class="form-label small fw-bold text-success"><i class="bi bi-check-circle me-1"></i> Aprobar Paquete (Evaluación Final)</label>
                                            <p class="small text-muted mb-2">Para aprobar, suba la <strong>EVALUACIÓN CUALITATIVA POR LA OFICINA DE SERVICIO SOCIAL Y DESARROLLO COMUNITARIO</strong> debidamente llenada.</p>
                                            <input type="file" name="evaluacion_oficina" class="form-control form-control-sm mb-3" accept=".pdf">
                                            <button type="submit" name="accion" value="Aprobar" class="btn btn-success btn-sm w-100 fw-bold">
                                                Aprobar y Subir Evaluación
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="card shadow-sm border-0 h-100 d-flex align-items-center justify-content-center bg-light">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-folder-symlink text-muted" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mt-3 text-dark">Ningún alumno seleccionado</h4>
                        <p class="text-muted">Utiliza los filtros de la izquierda para seleccionar una organización, luego un programa y finalmente a un alumno.</p>
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

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
