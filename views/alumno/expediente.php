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

<div class="tab-content">
    <!-- ===== Tab: Documentos Iniciales ===== -->
    <div class="tab-pane fade show active" id="tabDocIniciales" role="tabpanel">
        <div class="row g-4">
            <!-- Main Column -->
            <div class="col-lg-8">
                <!-- Upload Zone -->
                <div class="card-custom p-4 mb-4">
                    <div class="upload-zone" id="uploadZone">
                        <div class="upload-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Arrastra y suelta tus archivos aquí</h6>
                        <p class="text-muted mb-3" style="font-size: 0.88rem;">
                            Formatos soportados: PDF. Tamaño máximo: 10MB. Asegúrate de que el documento sea legible antes de subirlo.
                        </p>
                        <button class="btn btn-dark px-4">
                            <i class="bi bi-upload me-1"></i> Seleccionar Archivo
                        </button>
                    </div>
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
                                <!-- Fila 1 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                            <span class="fw-medium">Carta de Aceptación.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">12 Oct 2023,<br>10:30 AM</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">● Aprobado</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-download"></i></button>
                                    </td>
                                </tr>
                                <!-- Fila 2 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                            <span class="fw-medium">Plan de Trabajo Inicial.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">15 Oct 2023,<br>02:15 PM</td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">● En Revisión</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Fila 3 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                            <span class="fw-medium">Constancia de Créditos.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">08 Oct 2023,<br>09:00 AM</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">● Aprobado</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-download"></i></button>
                                    </td>
                                </tr>
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
                        <span class="fw-bold" style="color: var(--itch-success);">66%</span>
                    </div>
                    <div class="progress mb-4" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Document Status Items -->
                    <div class="doc-status-item">
                        <div class="status-icon status-icon-success"><i class="bi bi-check"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;">Carta de Aceptación</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Revisado y aprobado</div>
                        </div>
                    </div>
                    <div class="doc-status-item">
                        <div class="status-icon status-icon-warning"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;">Plan de Trabajo</div>
                            <div class="text-muted" style="font-size: 0.8rem;">En proceso de revisión</div>
                        </div>
                    </div>
                    <div class="doc-status-item">
                        <div class="status-icon status-icon-danger"><i class="bi bi-exclamation"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;">Constancia de Plática</div>
                            <div style="font-size: 0.8rem; color: #dc2626;">Falta subir documento</div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="help-card">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-lightbulb me-1" style="color: #d97706;"></i> ¿Necesitas ayuda?
                    </h6>
                    <p class="text-muted mb-2" style="font-size: 0.88rem;">
                        Consulta la guía de formatos y requisitos para asegurar que tus documentos sean aceptados en la primera revisión.
                    </p>
                    <a href="#" class="text-decoration-none fw-semibold" style="font-size: 0.9rem;">
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
