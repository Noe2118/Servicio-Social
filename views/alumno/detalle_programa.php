<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .detail-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        overflow: hidden;
    }
    .detail-header {
        background: linear-gradient(135deg, var(--itch-primary) 0%, var(--itch-primary-light) 100%);
        color: #fff;
        padding: 2rem 2rem 1.5rem;
    }
    .detail-header .badge-modalidad {
        font-size: 0.78rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
    }
    .detail-body {
        padding: 2rem;
    }
    .detail-section {
        margin-bottom: 1.5rem;
    }
    .detail-section h6 {
        font-weight: 700;
        color: var(--itch-primary);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .meta-item {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
    }
    .meta-item .meta-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--itch-accent);
        font-size: 1rem;
        flex-shrink: 0;
    }
    .meta-item .meta-label {
        font-size: 0.78rem;
        color: #6b7280;
        margin-bottom: 0.1rem;
    }
    .meta-item .meta-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9rem;
    }
    .cupos-bar {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .cupos-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    .btn-confirmar {
        background: var(--itch-accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .btn-confirmar:hover:not(:disabled) {
        background: #094db8;
        color: #fff;
        transform: translateY(-1px);
    }
    .btn-confirmar:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .alert-result {
        display: none;
        margin-top: 1rem;
    }
</style>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size: 0.88rem;">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/alumno/catalogo" class="text-decoration-none">Catálogo</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($programa['nombre_programa']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- ===== Main Detail Column ===== -->
    <div class="col-lg-8">
        <div class="detail-card">
            <!-- Header con gradiente -->
            <div class="detail-header">
                <?php
                    $modClass = 'bg-light text-dark';
                    if ($programa['modalidad'] === 'Presencial') $modClass = 'bg-success';
                    elseif ($programa['modalidad'] === 'Virtual') $modClass = 'bg-warning text-dark';
                    elseif ($programa['modalidad'] === 'Híbrida') $modClass = 'bg-info text-dark';
                ?>
                <span class="badge badge-modalidad <?= $modClass ?> mb-2">
                    <?= htmlspecialchars($programa['modalidad']) ?>
                </span>
                <h2 class="fw-bold mb-1" style="font-size: 1.5rem;">
                    <?= htmlspecialchars($programa['nombre_programa']) ?>
                </h2>
                <p class="mb-0 opacity-75" style="font-size: 0.95rem;">
                    <i class="bi bi-building me-1"></i>
                    <?= htmlspecialchars($programa['nombre_organizacion']) ?>
                </p>
            </div>

            <!-- Body -->
            <div class="detail-body">
                <!-- Descripción -->
                <div class="detail-section">
                    <h6><i class="bi bi-text-paragraph me-1"></i> Descripción del Programa</h6>
                    <p class="text-muted" style="line-height: 1.7; font-size: 0.92rem;">
                        <?= nl2br(htmlspecialchars($programa['descripcion'] ?? 'Sin descripción disponible.')) ?>
                    </p>
                </div>

                <!-- Perfiles Requeridos -->
                <?php if (!empty($programa['perfiles_requeridos'])): ?>
                    <div class="detail-section">
                        <h6><i class="bi bi-person-check me-1"></i> Perfiles Requeridos</h6>
                        <p class="text-muted" style="font-size: 0.92rem;">
                            <?= htmlspecialchars($programa['perfiles_requeridos']) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Metadata Grid -->
                <div class="detail-section">
                    <h6><i class="bi bi-info-circle me-1"></i> Información General</h6>
                    <div class="detail-meta-grid">
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="meta-label">Responsable</div>
                                <div class="meta-value"><?= htmlspecialchars($programa['responsable_nombre']) ?></div>
                            </div>
                        </div>
                        <?php if (!empty($programa['responsable_contacto'])): ?>
                            <div class="meta-item">
                                <div class="meta-icon"><i class="bi bi-envelope"></i></div>
                                <div>
                                    <div class="meta-label">Contacto</div>
                                    <div class="meta-value"><?= htmlspecialchars($programa['responsable_contacto']) ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($programa['horario'])): ?>
                            <div class="meta-item">
                                <div class="meta-icon"><i class="bi bi-clock"></i></div>
                                <div>
                                    <div class="meta-label">Horario</div>
                                    <div class="meta-value"><?= htmlspecialchars($programa['horario']) ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($programa['ubicacion'])): ?>
                            <div class="meta-item">
                                <div class="meta-icon"><i class="bi bi-geo-alt"></i></div>
                                <div>
                                    <div class="meta-label">Ubicación</div>
                                    <div class="meta-value"><?= htmlspecialchars($programa['ubicacion']) ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-hash"></i></div>
                            <div>
                                <div class="meta-label">Folio</div>
                                <div class="meta-value"><?= htmlspecialchars($programa['folio_programa']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Sidebar: Solicitud ===== -->
    <div class="col-lg-4">
        <!-- Cupos Card -->
        <div class="card-custom p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-people me-1"></i> Disponibilidad
            </h6>
            <?php
                $cuposDisponibles = (int) $programa['cupos_disponibles'];
                $cuposTotales = (int) $programa['cupos_totales'];
                $cuposOcupados = (int) $programa['cupos_ocupados'];
                $porcentajeOcupado = $cuposTotales > 0 ? round(($cuposOcupados / $cuposTotales) * 100) : 100;
                $barColor = $cuposDisponibles > 3 ? '#198754' : ($cuposDisponibles > 0 ? '#ffc107' : '#dc3545');
            ?>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted" style="font-size: 0.85rem;">Cupos ocupados</span>
                <strong style="font-size: 0.9rem;"><?= $cuposOcupados ?> / <?= $cuposTotales ?></strong>
            </div>
            <div class="cupos-bar">
                <div class="cupos-bar-fill" 
                     style="width: <?= $porcentajeOcupado ?>%; background: <?= $barColor ?>;"></div>
            </div>
            <p class="mt-2 mb-0 fw-semibold" style="font-size: 0.95rem; color: <?= $barColor ?>;">
                <?= $cuposDisponibles ?> cupos disponibles
            </p>
        </div>

        <!-- Solicitud Card -->
        <div class="card-custom p-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-send me-1"></i> Solicitar Inscripción
            </h6>

            <?php if ($yaSolicitado): ?>
                <div class="alert alert-info mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="bi bi-check-circle-fill"></i>
                    Ya has enviado una solicitud para este programa. Espera la confirmación.
                </div>
            <?php elseif ($cuposDisponibles <= 0): ?>
                <div class="alert alert-warning mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Este programa no tiene cupos disponibles actualmente.
                </div>
            <?php else: ?>
                <p class="text-muted mb-3" style="font-size: 0.88rem;">
                    Al confirmar, se enviará tu solicitud al área de DGTyV para revisión y aprobación.
                </p>
                <button type="button" class="btn-confirmar w-100" id="btnConfirmarSolicitud"
                        data-programa-id="<?= $programa['id_programa'] ?>">
                    <i class="bi bi-check2-circle me-1"></i> Confirmar Solicitud
                </button>
            <?php endif; ?>

            <!-- Resultado de la solicitud -->
            <div id="alertResult" class="alert-result"></div>
        </div>

        <!-- Regresar -->
        <div class="mt-3 text-center">
            <a href="<?= BASE_URL ?>/alumno/catalogo" class="text-decoration-none text-muted" style="font-size: 0.9rem;">
                <i class="bi bi-arrow-left me-1"></i> Volver al Catálogo
            </a>
        </div>
    </div>
</div>

<!-- ===== Modal de Confirmación ===== -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Confirmar Inscripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">¿Estás seguro de que deseas solicitar inscripción al programa:</p>
                <div class="p-3 rounded-3 mb-3" style="background: #f0f4ff;">
                    <strong class="text-dark"><?= htmlspecialchars($programa['nombre_programa']) ?></strong>
                    <br>
                    <small class="text-muted"><?= htmlspecialchars($programa['nombre_organizacion']) ?></small>
                </div>
                <div class="alert alert-warning d-flex align-items-center gap-2 py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Esta acción no se puede deshacer. Solo puedes estar inscrito en un programa a la vez.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary px-4 fw-semibold" id="btnConfirmarFinal">
                    <i class="bi bi-check2 me-1"></i> Sí, Solicitar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== JavaScript: Solicitud de Inscripción via Fetch ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSolicitar = document.getElementById('btnConfirmarSolicitud');
    const btnFinal = document.getElementById('btnConfirmarFinal');
    const alertResult = document.getElementById('alertResult');
    
    if (!btnSolicitar) return;

    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    const programaId = btnSolicitar.getAttribute('data-programa-id');

    // Abrir modal de confirmación
    btnSolicitar.addEventListener('click', function() {
        modal.show();
    });

    // Confirmar solicitud
    btnFinal.addEventListener('click', function() {
        btnFinal.disabled = true;
        btnFinal.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

        const formData = new FormData();
        formData.append('id_programa', programaId);

        fetch('<?= BASE_URL ?>/alumno/solicitar_inscripcion', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();

            if (data.status === 'success') {
                alertResult.className = 'alert alert-success alert-result d-flex align-items-center gap-2';
                alertResult.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + data.message;
                alertResult.style.display = 'flex';
                
                // Deshabilitar botón original
                btnSolicitar.disabled = true;
                btnSolicitar.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Solicitud Enviada';
                btnSolicitar.style.opacity = '0.6';
            } else {
                alertResult.className = 'alert alert-danger alert-result d-flex align-items-center gap-2';
                alertResult.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> ' + data.message;
                alertResult.style.display = 'flex';
                
                btnFinal.disabled = false;
                btnFinal.innerHTML = '<i class="bi bi-check2 me-1"></i> Sí, Solicitar';
            }
        })
        .catch(error => {
            modal.hide();
            alertResult.className = 'alert alert-danger alert-result d-flex align-items-center gap-2';
            alertResult.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Error de conexión. Intenta de nuevo.';
            alertResult.style.display = 'flex';
            
            btnFinal.disabled = false;
            btnFinal.innerHTML = '<i class="bi bi-check2 me-1"></i> Sí, Solicitar';
        });
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
