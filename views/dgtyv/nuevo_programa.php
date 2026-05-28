<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<h1 class="page-title">Nuevo Programa</h1>
<p class="page-subtitle">Registra un nuevo programa de servicio social en nombre de una dependencia.</p>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/dgtyv/guardar_programa" method="POST">
                    <h5 class="fw-bold mb-4" style="color: var(--itch-primary);">Información General</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Dependencia <span class="text-danger">*</span></label>
                            <select name="id_dependencia" class="form-select" required>
                                <option value="">Selecciona una dependencia...</option>
                                <?php if (!empty($dependencias)): ?>
                                    <?php foreach ($dependencias as $dep): ?>
                                        <option value="<?= (int)$dep['id_dependencia'] ?>"><?= htmlspecialchars($dep['nombre_organizacion']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Folio del Programa <span class="text-danger">*</span></label>
                            <input type="text" name="folio_programa" class="form-control" placeholder="Ej. PRG-2023-001" required>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Nombre del Programa <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_programa" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Modalidad <span class="text-danger">*</span></label>
                            <select name="modalidad" class="form-select" required>
                                <option value="Presencial">Presencial</option>
                                <option value="Virtual">Virtual</option>
                                <option value="Híbrida">Híbrida</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Cupos Totales <span class="text-danger">*</span></label>
                            <input type="number" name="cupos_totales" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Día de la Semana <span class="text-danger">*</span></label>
                            <select name="dia_semana" class="form-select" required>
                                <option value="Lunes a Viernes">Lunes a Viernes</option>
                                <option value="Lunes a Sabado">Lunes a Sábado</option>
                                <option value="Fines de Semana">Fines de Semana</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miercoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Hora Inicio <span class="text-danger">*</span></label>
                            <input type="time" name="hora_inicio" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Hora Fin <span class="text-danger">*</span></label>
                            <input type="time" name="hora_fin" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.9rem;">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.9rem;">Perfiles Requeridos <span class="text-danger">*</span></label>
                        <textarea name="perfiles_requeridos" class="form-control" rows="3" placeholder="Ingeniería en Sistemas, Administración... (uno por línea)" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.9rem;">Ubicación <span class="text-danger">*</span></label>
                        <input type="text" name="ubicacion" class="form-control" required>
                    </div>

                    <h5 class="fw-bold mb-3 mt-5" style="color: var(--itch-primary);">Responsable del Programa</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Nombre del Responsable <span class="text-danger">*</span></label>
                            <input type="text" name="responsable_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">Contacto <span class="text-danger">*</span></label>
                            <input type="text" name="responsable_contacto" class="form-control" placeholder="Email o Teléfono" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="<?= BASE_URL ?>/dgtyv/dashboard" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Guardar Programa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
