<?php require APP_PATH . '/views/layouts/header_dependencia.php'; ?>

<!-- Page Title -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Configurar Bimestres</h1>
        <p class="page-subtitle">Programa: <strong><?= htmlspecialchars($programa['nombre_programa']) ?></strong></p>
    </div>
    <a href="<?= BASE_URL ?>/dependencia/misProgramas" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Programas
    </a>
</div>

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

<div class="card-custom">
    <div class="card-body p-4">
        <form method="POST" action="<?= BASE_URL ?>/dependencia/guardar_configuracion_bimestres">
            <input type="hidden" name="id_programa" value="<?= (int)$programa['id_programa'] ?>">

            <div class="row g-4">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <?php 
                        $b = $bimestres[$i] ?? null; 
                        $inicio = $b ? $b['fecha_inicio'] : '';
                        $fin = $b ? $b['fecha_fin'] : '';
                        $habilitado = $b && $b['habilitado'] ? 'checked' : '';
                    ?>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 <?= $habilitado ? 'border-primary bg-primary bg-opacity-10' : 'bg-light' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-primary">Bimestre <?= $i ?></h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-5" type="checkbox" role="switch" name="habilitado_<?= $i ?>" id="habilitado_<?= $i ?>" <?= $habilitado ?>>
                                    <label class="form-check-label ms-1" for="habilitado_<?= $i ?>">Habilitado</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Fecha de Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio_<?= $i ?>" value="<?= htmlspecialchars($inicio) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Fecha de Término</label>
                                <input type="date" class="form-control" name="fecha_fin_<?= $i ?>" value="<?= htmlspecialchars($fin) ?>">
                            </div>
                            
                            <p class="small text-muted mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Al habilitar, los alumnos podrán subir sus 2 documentos correspondientes a este periodo.
                            </p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="mt-4 text-end border-top pt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_dependencia.php'; ?>
