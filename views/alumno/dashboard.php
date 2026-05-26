<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    /* ===== Dashboard Specific Styles ===== */
    .progress-ring-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }
    .progress-ring-container svg {
        transform: rotate(-90deg);
    }
    .progress-ring-bg {
        fill: none;
        stroke: #e5e7eb;
        stroke-width: 14;
    }
    .progress-ring-fill {
        fill: none;
        stroke: #198754;
        stroke-width: 14;
        stroke-linecap: round;
        transition: stroke-dashoffset 1s ease-in-out;
    }
    .progress-ring-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .progress-ring-text .hours-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--itch-primary);
        line-height: 1;
    }
    .progress-ring-text .hours-label {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .program-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #e8f0fe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--itch-accent);
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .task-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        background: #fff;
        transition: box-shadow 0.2s;
    }
    .task-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .task-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-right: 1rem;
    }
    .task-icon-warning {
        background: #fef3c7;
        color: #d97706;
    }
    .task-icon-info {
        background: #dbeafe;
        color: #2563eb;
    }
</style>

<!-- Page Title -->
<h1 class="page-title">Mi Progreso</h1>
<p class="page-subtitle">Resumen de tu servicio social actual.</p>

<div class="row g-4">
    <!-- ===== Tarjeta: Horas Completadas ===== -->
    <div class="col-lg-5">
        <div class="card-custom p-4 h-100">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-clock-history me-1"></i> Horas Completadas
            </h6>
            <div class="progress-ring-container" id="progressRing">
                <svg viewBox="0 0 200 200" width="200" height="200">
                    <circle class="progress-ring-bg" cx="100" cy="100" r="80"></circle>
                    <circle class="progress-ring-fill" cx="100" cy="100" r="80"
                            stroke-dasharray="502.65"
                            stroke-dashoffset="502.65"
                            id="progressCircle"></circle>
                </svg>
                <div class="progress-ring-text">
                    <div class="hours-number" id="hoursDisplay">0</div>
                    <div class="hours-label">/ <?= $totalHorasMeta ?> hrs</div>
                </div>
            </div>
            <div class="text-center mt-3">
                <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                    <?= $porcentajeHoras ?>% Meta Alcanzada
                </span>
            </div>
        </div>
    </div>

    <!-- ===== Tarjeta: Programa Asignado ===== -->
    <div class="col-lg-7">
        <div class="card-custom p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold text-dark mb-0">Programa Asignado</h6>
                <?php if ($asignacion): ?>
                    <?php
                        $estadoBadge = 'badge-estado-pendiente';
                        $estadoTexto = $asignacion['estado_asignacion'];
                        if ($asignacion['estado_asignacion'] === 'Activo') {
                            $estadoBadge = 'badge-estado-activo';
                            $estadoTexto = '● Activo';
                        } elseif ($asignacion['estado_asignacion'] === 'Pendiente') {
                            $estadoTexto = '● Pendiente';
                        } elseif ($asignacion['estado_asignacion'] === 'Concluido') {
                            $estadoBadge = 'badge-estado-concluido';
                            $estadoTexto = '● Concluido';
                        }
                    ?>
                    <span class="badge <?= $estadoBadge ?> rounded-pill px-3 py-2">
                        <?= htmlspecialchars($estadoTexto) ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($asignacion): ?>
                <div class="d-flex gap-3 mb-3">
                    <div class="program-card-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($asignacion['nombre_programa']) ?></h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                            <?= htmlspecialchars($asignacion['nombre_organizacion']) ?>.
                            <?= htmlspecialchars($asignacion['descripcion'] ?? '') ?>
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-4 text-muted" style="font-size: 0.85rem;">
                    <span>
                        <i class="bi bi-calendar3 me-1"></i>
                        Inicio: <?= date('d M Y', strtotime($asignacion['fecha_asignacion'])) ?>
                    </span>
                    <span>
                        <i class="bi bi-person me-1"></i>
                        Resp: <?= htmlspecialchars($asignacion['responsable_nombre']) ?>
                    </span>
                    <span>
                        <i class="bi bi-geo-alt me-1"></i>
                        <?= htmlspecialchars($asignacion['ubicacion'] ?? 'No especificada') ?>
                        - <?= htmlspecialchars($asignacion['modalidad']) ?>
                    </span>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-3">Aún no tienes un programa asignado.</p>
                    <a href="<?= BASE_URL ?>/alumno/catalogo" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Explorar Catálogo
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ===== Sección: Tareas Pendientes ===== -->
<div class="card-custom p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-clipboard-check me-1"></i> Tareas Pendientes
        </h6>
        <a href="<?= BASE_URL ?>/alumno/expediente" class="text-decoration-none" style="font-size: 0.9rem;">Ir a Expediente</a>
    </div>

    <?php 
        $hasTasks = false;
        if (isset($estadoDocumentos)) {
            foreach ($estadoDocumentos as $docName => $status) {
                if ($status !== 'Aprobado') {
                    $hasTasks = true;
                    $isMissing = ($status === 'Falta Subir' || $status === 'Rechazado');
                    $iconClass = $isMissing ? 'task-icon-warning text-danger' : 'task-icon-info text-primary';
                    $iconName = $isMissing ? 'bi-exclamation-triangle' : 'bi-hourglass-split';
                    $statusText = $isMissing ? 'Pendiente de subir' : 'En revisión por DGTyV';
                    ?>
                    <div class="task-item">
                        <div class="task-icon <?= $iconClass ?>" style="background: <?= $isMissing ? '#fee2e2' : '#e0e7ff' ?>;">
                            <i class="bi <?= $iconName ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-dark"><?= $isMissing ? 'Subir: ' : '' ?><?= htmlspecialchars($docName) ?></div>
                            <div class="text-muted" style="font-size: 0.85rem;">Estado: <?= $statusText ?></div>
                        </div>
                        <a href="<?= BASE_URL ?>/alumno/expediente" class="btn <?= $isMissing ? 'btn-dark' : 'btn-outline-dark' ?> btn-sm px-3">
                            <?= $isMissing ? 'Subir Archivo' : 'Ver Detalle' ?>
                        </a>
                    </div>
                    <?php
                }
            }
        }
    ?>

    <?php if (!$hasTasks): ?>
        <div class="text-center py-4 text-muted">
            <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
            ¡Todo al día! No tienes tareas pendientes.
        </div>
    <?php endif; ?>
</div>

<!-- ===== JavaScript: Animación del Gráfico Circular ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const porcentaje = <?= $porcentajeHoras ?>;
    const horasCompletadas = <?= $horasCompletadas ?>;
    const circle = document.getElementById('progressCircle');
    const hoursDisplay = document.getElementById('hoursDisplay');
    
    // Circunferencia del círculo: 2 * PI * r (r=80)
    const circumference = 2 * Math.PI * 80; // 502.65
    const offset = circumference - (porcentaje / 100) * circumference;

    // Animación gradual
    setTimeout(() => {
        circle.style.strokeDashoffset = offset;
    }, 300);

    // Contador animado de horas
    let current = 0;
    const duration = 1200; // ms
    const step = Math.ceil(horasCompletadas / (duration / 16));
    
    function animateCounter() {
        current += step;
        if (current >= horasCompletadas) {
            current = horasCompletadas;
            hoursDisplay.textContent = current;
            return;
        }
        hoursDisplay.textContent = current;
        requestAnimationFrame(animateCounter);
    }
    
    setTimeout(animateCounter, 300);
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
