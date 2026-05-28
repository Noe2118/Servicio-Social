<?php require APP_PATH . '/views/layouts/header_admin.php'; ?>

<!-- Page Title -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Catálogo de Programas</h1>
        <p class="page-subtitle mb-0">Listado general de todos los programas de Servicio Social registrados.</p>
    </div>
    <a href="<?= BASE_URL ?>/dgtyv/nuevo_programa" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Programa
    </a>
</div>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Programa</th>
                        <th>Dependencia</th>
                        <th>Modalidad</th>
                        <th>Cupos</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($programas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No se encontraron programas registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($programas as $prog): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($prog['nombre_programa']) ?></div>
                                    <small class="text-muted"><i class="bi bi-hash me-1"></i><?= htmlspecialchars($prog['folio_programa']) ?></small>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:0.9rem;">
                                        <?= htmlspecialchars($prog['nombre_organizacion']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $modClass = 'badge-modalidad-presencial';
                                        if ($prog['modalidad'] === 'Virtual') $modClass = 'badge-modalidad-virtual';
                                        if ($prog['modalidad'] === 'Híbrida') $modClass = 'badge-modalidad-hibrida';
                                    ?>
                                    <span class="badge <?= $modClass ?> px-2 py-1">
                                        <?= htmlspecialchars($prog['modalidad']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem;">
                                        <span class="fw-semibold"><?= (int)$prog['cupos_ocupados'] ?></span> / <span class="text-muted"><?= (int)$prog['cupos_totales'] ?></span>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <?php 
                                            $porcentaje = ($prog['cupos_totales'] > 0) ? ($prog['cupos_ocupados'] / $prog['cupos_totales']) * 100 : 0;
                                            $colorProg = $porcentaje >= 100 ? 'bg-danger' : 'bg-primary';
                                        ?>
                                        <div class="progress-bar <?= $colorProg ?>" style="width: <?= $porcentaje ?>%"></div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $badgeEstado = 'bg-secondary';
                                        if ($prog['estado_aprobacion'] === 'Aprobado') $badgeEstado = 'bg-success';
                                        elseif ($prog['estado_aprobacion'] === 'En Revisión por DGTyV') $badgeEstado = 'bg-warning text-dark';
                                        elseif ($prog['estado_aprobacion'] === 'Rechazado') $badgeEstado = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeEstado ?> px-2 py-1" style="font-size:0.75rem;">
                                        <?= htmlspecialchars($prog['estado_aprobacion']) ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#modalPrograma<?= $prog['id_programa'] ?>">
                                        Ver Más
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Modal Detail -->
                            <div class="modal fade" id="modalPrograma<?= $prog['id_programa'] ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                  <div class="modal-header bg-light border-0">
                                    <h5 class="modal-title fw-bold" style="color: var(--itch-primary);">Detalles del Programa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body p-4">
                                      <h4 class="fw-bold mb-1"><?= htmlspecialchars($prog['nombre_programa']) ?></h4>
                                      <p class="text-muted mb-4"><i class="bi bi-building me-2"></i><?= htmlspecialchars($prog['nombre_organizacion']) ?></p>
                                      
                                      <div class="row g-4 mb-4">
                                          <div class="col-md-4">
                                              <div class="p-3 bg-light rounded">
                                                  <small class="text-muted d-block fw-semibold mb-1">Folio</small>
                                                  <span class="fw-bold"><?= htmlspecialchars($prog['folio_programa']) ?></span>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="p-3 bg-light rounded">
                                                  <small class="text-muted d-block fw-semibold mb-1">Modalidad</small>
                                                  <span class="fw-bold"><?= htmlspecialchars($prog['modalidad']) ?></span>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="p-3 bg-light rounded">
                                                  <small class="text-muted d-block fw-semibold mb-1">Horario</small>
                                                  <span class="fw-bold"><?= htmlspecialchars($prog['horarios_formateados'] ?? 'No definido') ?></span>
                                              </div>
                                          </div>
                                      </div>
                                      
                                      <h6 class="fw-bold mb-2">Descripción</h6>
                                      <p class="text-muted text-justify" style="font-size:0.9rem; line-height: 1.6;">
                                          <?= nl2br(htmlspecialchars($prog['descripcion'] ?? '')) ?>
                                      </p>
                                      
                                      <h6 class="fw-bold mb-2 mt-4">Perfiles Requeridos</h6>
                                      <p class="text-muted" style="font-size:0.9rem; line-height: 1.6;">
                                          <?= nl2br(htmlspecialchars($prog['perfiles_requeridos'] ?? '')) ?>
                                      </p>
                                      
                                      <div class="row mt-4 pt-4 border-top">
                                          <div class="col-md-6">
                                              <h6 class="fw-bold mb-2">Ubicación</h6>
                                              <p class="text-muted" style="font-size:0.9rem;"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($prog['ubicacion'] ?? 'No especificada') ?></p>
                                          </div>
                                          <div class="col-md-6">
                                              <h6 class="fw-bold mb-2">Responsable</h6>
                                              <p class="text-muted mb-1" style="font-size:0.9rem;"><i class="bi bi-person me-1"></i><?= htmlspecialchars($prog['responsable_nombre'] ?? 'No asignado') ?></p>
                                              <p class="text-muted" style="font-size:0.9rem;"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($prog['responsable_contacto'] ?? '') ?></p>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer border-0 bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer_admin.php'; ?>
