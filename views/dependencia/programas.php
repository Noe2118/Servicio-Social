<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Programas - Dependencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: white; border-right: 1px solid #ddd; }
        .navbar-custom { background-color: #0b3d91; color: white; }
        .nav-link.active { background-color: #f0f4f8; color: #0b3d91; font-weight: bold; border-right: 4px solid #0b3d91; }
        .badge-aprobado { background-color: #d1e7dd; color: #0f5132; }
        .badge-revision { background-color: #e2e3e5; color: #41464b; }
        .progress { height: 6px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom px-3 py-2">
        <a class="navbar-brand text-white fw-bold" href="#">ITCH/TecNM</a>
        <form class="d-flex ms-3">
            <input class="form-control form-control-sm" type="search" placeholder="Buscar programas..." aria-label="Buscar">
        </form>
        <div class="ms-auto text-white d-flex align-items-center">
            <div class="me-3 text-end">
                <small class="d-block lh-1 text-light">DEPENDENCIA: DIF Municipal</small>
            </div>
            <div class="bg-light rounded-circle text-dark d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">D</div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar pt-3">
                <div class="px-3 mb-3">
                    <h6 class="text-primary fw-bold mb-0">Portal Vinculación</h6>
                    <small class="text-muted">Organización Externa</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="/dependencia/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="/dependencia/misProgramas">Mis Programas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="/dependencia/alumnos">Alumnos y Evaluaciones</a>
                    </li>
                    <li class="nav-item mt-5">
                        <a class="nav-link text-danger fw-bold" href="/auth/logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark">Gestión de Cupos</h2>
                        <p class="text-muted">Administra los programas activos y revisa la disponibilidad de cupos.</p>
                    </div>
                    <button class="btn btn-primary fw-bold">+ Proponer Nuevo Programa</button>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Programas Actuales</h6>
                        <button class="btn btn-sm btn-light text-muted">Filtrar</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="text-muted" style="font-size: 0.9rem;">
                                <tr>
                                    <th>Nombre del Programa</th>
                                    <th>Modalidad</th>
                                    <th>Cupos Ocupados/Totales</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($programas)): ?>
                                    <?php foreach ($programas as $p): ?>
                                        <?php 
                                            $porcentaje = ($p['cupos_totales'] > 0) ? ($p['cupos_ocupados'] / $p['cupos_totales']) * 100 : 0;
                                            $color = 'bg-success';
                                            if ($porcentaje >= 100) $color = 'bg-danger';
                                            elseif ($porcentaje >= 80) $color = 'bg-warning';
                                        ?>
                                        <tr>
                                            <td class="fw-bold text-primary"><?= htmlspecialchars($p['nombre_programa']) ?></td>
                                            <td><?= htmlspecialchars($p['modalidad']) ?></td>
                                            <td>
                                                <span class="<?= ($porcentaje >= 100) ? 'text-danger fw-bold' : '' ?>">
                                                    <?= htmlspecialchars($p['cupos_ocupados']) ?> / <?= htmlspecialchars($p['cupos_totales']) ?>
                                                </span>
                                                <div class="progress mt-2" style="width: 120px;">
                                                    <div class="progress-bar <?= $color ?>" role="progressbar" style="width: <?= $porcentaje ?>%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($p['estado_aprobacion'] === 'Aprobado'): ?>
                                                    <span class="badge badge-aprobado rounded-pill px-3 py-2">● Aprobado</span>
                                                <?php else: ?>
                                                    <span class="badge badge-revision rounded-pill px-3 py-2">● <?= htmlspecialchars($p['estado_aprobacion']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><button class="btn btn-sm btn-light">⋮</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-4">No hay programas registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-muted d-flex justify-content-between py-3">
                        <small>Mostrando programas</small>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary px-2 py-0">&lt;</button>
                            <button class="btn btn-sm btn-outline-secondary px-2 py-0">&gt;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
