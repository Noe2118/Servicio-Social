<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Expedientes Pendientes</h1>
<p class="page-subtitle">Revisa y aprueba los documentos enviados por los alumnos.</p>

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

<div class="row g-4">
    <!-- Left: Document List -->
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-body p-0">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-funnel me-1"></i>Documentos por revisar
                        <span class="badge bg-primary ms-2"><?= count($documentos ?? []) ?></span>
                    </h6>
                </div>
                <?php if (!empty($documentos)): ?>
                    <?php foreach ($documentos as $doc): ?>
                        <?php
                            $isSelected = !empty($documentoSeleccionado) && ($documentoSeleccionado['id_documento'] ?? null) == ($doc['id_documento'] ?? null);
                            $esCoreegido = strtolower($doc['estado_revision'] ?? '') === 'corregido';
                        ?>
                        <a href="<?= BASE_URL ?>/dgtyv/expedientes?id=<?= (int)($doc['id_documento'] ?? 0) ?>"
                           class="review-item d-block text-decoration-none <?= $isSelected ? 'active' : '' ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-bold d-block" style="font-size:0.88rem; color:#111827;">
                                        <?= htmlspecialchars($doc['nombre_completo'] ?? '') ?>
                                    </span>
                                    <small class="text-muted">No. Control: <?= htmlspecialchars($doc['no_control'] ?? '') ?></small>
                                </div>
                                <?php if ($esCoreegido): ?>
                                    <span class="badge" style="background:#dcfce7; color:#166534; font-size:0.7rem; font-weight:600;">CORREGIDO</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; font-weight:600;">NUEVO</span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-1 d-flex align-items-center gap-1" style="font-size:0.82rem; color:#6b7280;">
                                <i class="bi bi-file-earmark-text"></i>
                                <?= htmlspecialchars($doc['tipo_documento'] ?? '') ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success"></i>
                        <p class="mb-0" style="font-size:0.9rem;">No hay documentos pendientes.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right: Document Review Panel -->
    <div class="col-lg-8">
        <?php if (!empty($documentoSeleccionado)): ?>
            <div class="card-custom">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Revisión de Documento</h5>
                        <span class="badge bg-primary" style="font-size:0.75rem;">En Revisión</span>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1">
                            <span class="fw-semibold"><?= htmlspecialchars($documentoSeleccionado['nombre_completo'] ?? '') ?></span>
                            <span class="text-muted ms-2" style="font-size:0.85rem;">No. Control: <?= htmlspecialchars($documentoSeleccionado['no_control'] ?? '') ?></span>
                        </p>
                        <p class="text-muted mb-0" style="font-size:0.88rem;">
                            <i class="bi bi-file-earmark-text me-1"></i><?= htmlspecialchars($documentoSeleccionado['tipo_documento'] ?? '') ?>
                            <?php if (!empty($documentoSeleccionado['fecha_subida'])): ?>
                                <span class="ms-3"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($documentoSeleccionado['fecha_subida']) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- PDF Viewer -->
                    <div class="pdf-viewer mb-4">
                        <div class="pdf-viewer-header">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>VISTA PREVIA - <?= htmlspecialchars($documentoSeleccionado['nombre_archivo'] ?? 'documento.pdf') ?></span>
                        </div>
                        <iframe
                            src="<?= BASE_URL ?><?= htmlspecialchars($documentoSeleccionado['ruta_servidor'] ?? '') ?>"
                            style="width:100%; height:500px; border:none; background:#f9fafb;"
                            title="Vista previa del documento">
                        </iframe>
                    </div>

                    <!-- Comments -->
                    <div class="mb-4">
                        <label for="comentarios" class="form-label fw-semibold" style="font-size:0.88rem;">
                            <i class="bi bi-chat-left-text me-1"></i>Comentarios u Observaciones
                        </label>
                        <textarea class="form-control" id="comentarios" name="comentarios_preview" rows="3"
                                  placeholder="Escribe tus observaciones sobre el documento..." style="font-size:0.9rem;"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-3 border-top mb-4">
                        <form method="POST" action="<?= BASE_URL ?>/dgtyv/aprobar_documento" class="flex-fill">
                            <input type="hidden" name="id_documento" value="<?= (int)($documentoSeleccionado['id_documento'] ?? 0) ?>">
                            <textarea name="comentarios" class="d-none" id="comentarios_aprobar"></textarea>
                            <button type="submit" class="btn btn-success w-100" onclick="document.getElementById('comentarios_aprobar').value = document.getElementById('comentarios').value;">
                                <i class="bi bi-check-lg me-1"></i>Aprobar Documento
                            </button>
                        </form>
                        <form method="POST" action="<?= BASE_URL ?>/dgtyv/rechazar_documento">
                            <input type="hidden" name="id_documento" value="<?= (int)($documentoSeleccionado['id_documento'] ?? 0) ?>">
                            <textarea name="comentarios" class="d-none" id="comentarios_rechazar"></textarea>
                            <button type="submit" class="btn btn-outline-danger" onclick="document.getElementById('comentarios_rechazar').value = document.getElementById('comentarios').value;">
                                <i class="bi bi-x-lg me-1"></i>Rechazar Documento
                            </button>
                        </form>
                    </div>

                    <!-- Lógica de Inscripción / Baja del Alumno -->
                    <div class="border p-3 rounded bg-light">
                        <h6 class="fw-bold mb-3"><i class="bi bi-person-badge"></i> Estado en el Programa</h6>
                        
                        <p class="mb-1" style="font-size: 0.9rem;">
                            <strong>Programa Solicitado:</strong> <?= htmlspecialchars($documentoSeleccionado['nombre_programa'] ?? 'N/A') ?>
                        </p>
                        <p class="mb-3" style="font-size: 0.9rem;">
                            <strong>Créditos Completados:</strong> <?= htmlspecialchars($documentoSeleccionado['creditos_completados'] ?? '0') ?>%
                        </p>

                        <?php if (($documentoSeleccionado['estado_asignacion'] ?? '') === 'Pendiente'): ?>
                            <div class="alert alert-warning py-2 mb-2" style="font-size: 0.85rem;">
                                El alumno está pendiente de ser aceptado en el programa. Revisa que sus documentos iniciales sean correctos antes de aceptarlo.
                            </div>
                            <form method="POST" action="<?= BASE_URL ?>/dgtyv/aceptar_alumno">
                                <input type="hidden" name="id_alumno" value="<?= (int)($documentoSeleccionado['id_alumno'] ?? 0) ?>">
                                <input type="hidden" name="id_programa" value="<?= (int)($documentoSeleccionado['id_programa'] ?? 0) ?>">
                                <button type="submit" class="btn btn-primary w-100" onclick="return confirm('¿Confirmas que deseas aceptar al alumno en el programa?');">
                                    <i class="bi bi-person-check-fill me-1"></i> Aceptar Alumno en el Programa
                                </button>
                            </form>
                        <?php elseif (($documentoSeleccionado['estado_asignacion'] ?? '') === 'Activo'): ?>
                            <div class="alert alert-success py-2 mb-2" style="font-size: 0.85rem;">
                                El alumno ya está ACTIVO en este programa.
                            </div>
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalBajaAdmin">
                                <i class="bi bi-person-x-fill me-1"></i> Dar de Baja al Alumno
                            </button>

                            <!-- Modal de Baja Admin -->
                            <div class="modal fade" id="modalBajaAdmin" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="<?= BASE_URL ?>/dgtyv/dar_baja_alumno">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Dar de Baja</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Estás a punto de dar de baja a <strong><?= htmlspecialchars($documentoSeleccionado['nombre_completo']) ?></strong> del programa.</p>
                                                <input type="hidden" name="id_alumno" value="<?= (int)($documentoSeleccionado['id_alumno'] ?? 0) ?>">
                                                <input type="hidden" name="id_programa" value="<?= (int)($documentoSeleccionado['id_programa'] ?? 0) ?>">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Motivo de la baja (Obligatorio)</label>
                                                    <textarea name="motivo" class="form-control" rows="3" required></textarea>
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
                        <?php else: ?>
                            <span class="badge bg-secondary">Estado actual: <?= htmlspecialchars($documentoSeleccionado['estado_asignacion'] ?? 'Ninguno') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="card-custom">
                <div class="card-body text-center py-5">
                    <i class="bi bi-folder2-open" style="font-size:3rem; color:#d1d5db;"></i>
                    <h5 class="fw-bold mt-3 mb-2" style="color:#6b7280;">Selecciona un documento</h5>
                    <p class="text-muted" style="font-size:0.9rem;">Elige un expediente de la lista para revisar el documento y emitir tu resolución.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
