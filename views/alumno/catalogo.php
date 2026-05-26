<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .filter-bar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .program-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: box-shadow 0.2s ease, transform 0.15s ease;
    }
    .program-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .program-card .card-body-content {
        flex: 1;
    }
    .program-card .program-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--itch-primary);
        margin-bottom: 0.25rem;
    }
    .program-card .program-org {
        color: #6b7280;
        font-size: 0.88rem;
        margin-bottom: 1rem;
    }
    .program-card .program-meta {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .program-card .program-meta i {
        width: 18px;
        text-align: center;
    }
    .cupos-danger {
        color: #dc3545;
        font-weight: 700;
    }
    .btn-solicitar {
        background-color: var(--itch-primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.55rem;
        font-weight: 600;
        font-size: 0.88rem;
        width: 100%;
        transition: background-color 0.2s;
    }
    .btn-solicitar:hover {
        background-color: var(--itch-primary-light);
        color: #fff;
    }
    .btn-lista-espera {
        background-color: #e5e7eb;
        color: #6b7280;
        border: none;
        border-radius: 8px;
        padding: 0.55rem;
        font-weight: 600;
        font-size: 0.88rem;
        width: 100%;
        cursor: not-allowed;
    }
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #6b7280;
    }
    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
</style>

<!-- Page Title -->
<h1 class="page-title">Catálogo de Programas</h1>
<p class="page-subtitle">Explora y solicita inscripción a los programas de servicio social disponibles.</p>

<!-- ===== Filter Bar ===== -->
<div class="filter-bar">
    <form method="GET" action="<?= BASE_URL ?>/alumno/catalogo" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Modalidad</label>
            <select name="modalidad" class="form-select">
                <option value="">Todas las modalidades</option>
                <option value="Presencial" <?= ($filtroModalidad ?? '') === 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                <option value="Virtual" <?= ($filtroModalidad ?? '') === 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                <option value="Híbrida" <?= ($filtroModalidad ?? '') === 'Híbrida' ? 'selected' : '' ?>>Híbrida</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Dependencia</label>
            <select name="dependencia" class="form-select">
                <option value="">Todas las dependencias</option>
                <?php foreach ($dependencias as $dep): ?>
                    <option value="<?= $dep['id_dependencia'] ?>"
                        <?= ($filtroDependencia ?? 0) == $dep['id_dependencia'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dep['nombre_organizacion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 col-lg-2 ms-auto">
            <button type="submit" class="btn btn-dark w-100">
                <i class="bi bi-funnel me-1"></i> Aplicar Filtros
            </button>
        </div>
    </form>
</div>

<!-- ===== Program Grid ===== -->
<?php if (empty($programas)): ?>
    <div class="empty-state">
        <i class="bi bi-search d-block"></i>
        <h5 class="fw-bold text-dark">No se encontraron programas</h5>
        <p>No hay programas disponibles con los filtros seleccionados. Intenta cambiar los criterios de búsqueda.</p>
        <a href="<?= BASE_URL ?>/alumno/catalogo" class="btn btn-outline-primary btn-sm mt-2">Limpiar Filtros</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($programas as $prog): ?>
            <?php
                // Determinar badge de modalidad
                $badgeClass = 'badge-modalidad-presencial';
                if ($prog['modalidad'] === 'Virtual') {
                    $badgeClass = 'badge-modalidad-virtual';
                } elseif ($prog['modalidad'] === 'Híbrida') {
                    $badgeClass = 'badge-modalidad-hibrida';
                }

                $cuposDisponibles = (int) $prog['cupos_disponibles'];
                $cuposTotales = (int) $prog['cupos_totales'];
                $sinCupos = $cuposDisponibles <= 0;
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="program-card">
                    <div class="card-body-content">
                        <!-- Badge Modalidad -->
                        <span class="badge <?= $badgeClass ?> mb-2 px-2 py-1">
                            <?= htmlspecialchars($prog['modalidad']) ?>
                        </span>

                        <!-- Título y Organización -->
                        <div class="program-title"><?= htmlspecialchars($prog['nombre_programa']) ?></div>
                        <div class="program-org"><?= htmlspecialchars($prog['nombre_organizacion']) ?></div>

                        <!-- Metadata -->
                        <div class="program-meta d-flex flex-column gap-1 mb-3">
                            <span>
                                <i class="bi bi-people"></i>
                                Cupos: 
                                <strong class="<?= $sinCupos ? 'cupos-danger' : '' ?>">
                                    <?= $cuposDisponibles ?>/<?= $cuposTotales ?>
                                </strong> 
                                disponibles
                            </span>
                            <?php if (!empty($prog['horario'])): ?>
                                <span>
                                    <i class="bi bi-clock"></i>
                                    Horario: <?= htmlspecialchars($prog['horario']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <?php if ($sinCupos): ?>
                        <button class="btn-lista-espera" disabled>Lista de Espera</button>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/alumno/detalle_programa/<?= $prog['id_programa'] ?>" class="btn-solicitar text-center text-decoration-none d-block">
                            Solicitar Inscripción
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
