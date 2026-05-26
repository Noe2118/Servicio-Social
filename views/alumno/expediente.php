<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        background: #fafbfc;
        text-align: center;
        padding: 2.5rem 2rem;
        transition: border-color 0.2s, background-color 0.2s;
        cursor: pointer;
    }
    .upload-zone:hover {
        border-color: var(--itch-accent);
        background-color: #f0f4ff;
    }
    .upload-zone .upload-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #e8f0fe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--itch-accent);
        font-size: 1.5rem;
    }
    .doc-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
    }
    .doc-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .doc-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #fee2e2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .progress-sidebar-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 1.5rem;
    }
    .doc-status-item {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        margin-bottom: 0.85rem;
    }
    .doc-status-item .status-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .status-icon-success {
        background: #dcfce7;
        color: #166534;
    }
    .status-icon-warning {
        background: #fef3c7;
        color: #92400e;
    }
    .status-icon-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    .help-card {
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: #eff6ff;
        padding: 1.25rem;
    }
</style>

<!-- Page Title -->
<h1 class="page-title">Mi Expediente y Seguimiento</h1>
<p class="page-subtitle">Sube y gestiona la documentación requerida para tu servicio social.</p>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tabDocIniciales"
                type="button" role="tab" aria-selected="true">
            Documentos Iniciales
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tabReportes"
                type="button" role="tab" aria-selected="false">
            Reportes Bimestrales
        </button>
    </li>
</ul>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success mt-3"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger mt-3"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="tab-content">
    <!-- ===== Tab: Documentos Iniciales ===== -->
    <div class="tab-pane fade show active" id="tabDocIniciales" role="tabpanel">
        <div class="row g-4">
            <!-- Main Column -->
            <div class="col-lg-8">
                <!-- Upload Zone -->
                <div class="card-custom p-4 mb-4">
                    <form action="<?= BASE_URL ?>/alumno/subir_documento" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Documento</label>
                            <select name="tipo_documento" class="form-select" required>
                                <option value="">Selecciona el documento que vas a subir...</option>
                                <option value="Carta de Aceptación">Carta de Aceptación</option>
                                <option value="Plan de Trabajo Inicial">Plan de Trabajo Inicial</option>
                                <option value="Constancia de Créditos">Constancia de Créditos</option>
                            </select>
                        </div>
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Arrastra y suelta tus archivos aquí o haz clic para seleccionar</h6>
                            <p class="text-muted mb-3" style="font-size: 0.88rem;">
                                Formatos soportados: PDF. Tamaño máximo: 10MB.
                            </p>
                            <input type="file" name="archivo" id="fileInput" accept=".pdf" style="display: none;" required onchange="document.getElementById('submitBtn').style.display='block'; document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : '';">
                            <div id="fileName" class="text-primary fw-semibold mt-2"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-dark px-4" id="submitBtn" style="display: none;">
                                <i class="bi bi-upload me-1"></i> Subir Archivo
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Archivos Subidos -->
                <div class="card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Archivos Subidos</h6>
                        <span class="badge bg-light text-dark border px-3 py-1">3 Documentos</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table doc-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Fecha de Subida</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($documentos)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No has subido ningún documento aún.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($doc['tipo_documento']) ?></span>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <div class="doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                                        <span class="text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($doc['nombre_archivo_original']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted"><?= date('d M Y, h:i A', strtotime($doc['fecha_subida'])) ?></td>
                                            <td>
                                                <?php
                                                    $bgClass = 'bg-secondary text-secondary border-secondary';
                                                    if ($doc['estado_validacion'] === 'Aprobado') $bgClass = 'bg-success text-success border-success';
                                                    elseif ($doc['estado_validacion'] === 'Rechazado') $bgClass = 'bg-danger text-danger border-danger';
                                                    elseif ($doc['estado_validacion'] === 'Nuevo') $bgClass = 'bg-primary text-primary border-primary';
                                                    elseif ($doc['estado_validacion'] === 'En Revisión') $bgClass = 'bg-warning text-dark border-warning';
                                                ?>
                                                <span class="badge bg-opacity-10 border px-2 py-1 <?= $bgClass ?>">
                                                    ● <?= htmlspecialchars($doc['estado_validacion']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= BASE_URL ?><?= htmlspecialchars($doc['ruta_servidor']) ?>" target="_blank" class="btn btn-sm btn-light" title="Ver/Descargar"><i class="bi bi-download"></i></a>
                                                    <?php if (in_array($doc['estado_validacion'], ['Nuevo', 'Rechazado'])): ?>
                                                        <form action="<?= BASE_URL ?>/alumno/eliminar_documento" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este documento?');">
                                                            <input type="hidden" name="id_documento" value="<?= $doc['id_documento'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if (!empty($doc['comentarios_dgtyv'])): ?>
                                        <tr>
                                            <td colspan="4" class="bg-light border-0 py-2 px-3">
                                                <div class="d-flex gap-2 text-muted" style="font-size: 0.85rem;">
                                                    <i class="bi bi-chat-left-text-fill text-warning mt-1"></i>
                                                    <div>
                                                        <strong class="text-dark">Comentario de Revisión:</strong><br>
                                                        <?= nl2br(htmlspecialchars($doc['comentarios_dgtyv'])) ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Progreso del Expediente -->
                <div class="progress-sidebar-card mb-4">
                    <h6 class="fw-bold text-dark mb-3">Progreso del Expediente</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                            DOCUMENTOS INICIALES
                        </span>
                        <span class="fw-bold" style="color: var(--itch-success);"><?= $progresoExpediente ?>%</span>
                    </div>
                    <div class="progress mb-4" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progresoExpediente ?>%;" aria-valuenow="<?= $progresoExpediente ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Document Status Items -->
                    <?php if (isset($estadoDocumentos)): ?>
                        <?php foreach ($estadoDocumentos as $docName => $status): ?>
                            <?php
                                $statusIconClass = 'status-icon-danger';
                                $icon = 'bi-exclamation';
                                $statusText = 'Falta subir documento';
                                
                                if ($status === 'Aprobado') {
                                    $statusIconClass = 'status-icon-success';
                                    $icon = 'bi-check';
                                    $statusText = 'Revisado y aprobado';
                                } elseif (in_array($status, ['Nuevo', 'En Revisión'])) {
                                    $statusIconClass = 'status-icon-warning';
                                    $icon = 'bi-clock';
                                    $statusText = 'En proceso de revisión';
                                } elseif ($status === 'Rechazado') {
                                    $statusText = 'Rechazado. Por favor, vuelve a subirlo.';
                                }
                            ?>
                            <div class="doc-status-item">
                                <div class="status-icon <?= $statusIconClass ?>"><i class="bi <?= $icon ?>"></i></div>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;"><?= htmlspecialchars($docName) ?></div>
                                    <div style="font-size: 0.8rem; <?= $status === 'Rechazado' || $status === 'Falta Subir' ? 'color: #dc2626;' : 'color: #6b7280;' ?>"><?= $statusText ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Help Card -->
                <div class="help-card">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-lightbulb me-1" style="color: #d97706;"></i> ¿Necesitas ayuda?
                    </h6>
                    <p class="text-muted mb-2" style="font-size: 0.88rem;">
                        Consulta la guía de formatos y requisitos para asegurar que tus documentos sean aceptados en la primera revisión.
                    </p>
                    <a href="https://chetumal.tecnm.mx/notas/index.php/articulos/458-formatos-de-inscripcion-y-de-reportes" target="_blank" class="text-decoration-none fw-semibold" style="font-size: 0.9rem;">
                        Ver guía de documentos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Tab: Reportes Bimestrales ===== -->
    <div class="tab-pane fade" id="tabReportes" role="tabpanel">
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-bar-graph text-muted" style="font-size: 4rem;"></i>
            <h5 class="fw-bold text-dark mt-3">Reportes Bimestrales</h5>
            <p class="text-muted">Esta sección se habilitará cuando se cumplan los requisitos de documentos iniciales.</p>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
