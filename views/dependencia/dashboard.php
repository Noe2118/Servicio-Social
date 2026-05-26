<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dependencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: white; border-right: 1px solid #ddd; }
        .navbar-custom { background-color: #0b3d91; color: white; }
        .stat-card { border-left: 5px solid; border-radius: 5px; background: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat-blue { border-left-color: #0d6efd; }
        .stat-green { border-left-color: #198754; }
        .stat-yellow { border-left-color: #ffc107; }
        .stat-card h5 { font-size: 0.9rem; color: #6c757d; font-weight: bold; text-transform: uppercase; }
        .stat-card h2 { font-size: 2.5rem; font-weight: bold; margin: 0; }
        .nav-link.active { background-color: #f0f4f8; color: #0b3d91; font-weight: bold; border-right: 4px solid #0b3d91; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom px-3 py-2">
        <a class="navbar-brand text-white fw-bold" href="#">ITCH/TecNM</a>
        <form class="d-flex ms-3">
            <input class="form-control form-control-sm" type="search" placeholder="Buscar..." aria-label="Buscar">
        </form>
        <div class="ms-auto text-white d-flex align-items-center">
            <div class="me-3 text-end">
                <small class="d-block lh-1 text-light">DEPENDENCIA: DIF Municipal</small>
                <small class="d-block lh-1">Administrador</small>
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
                        <a class="nav-link text-dark active" href="/dependencia/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="/dependencia/misProgramas">Mis Programas</a>
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
                        <h2 class="fw-bold text-dark">Resumen</h2>
                        <p class="text-muted">Bienvenido al panel de control de su dependencia.</p>
                    </div>
                    <button class="btn btn-primary fw-bold">+ Nuevo Trámite</button>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card stat-blue">
                            <h5>Alumnos Activos</h5>
                            <h2><?= htmlspecialchars($alumnos_activos) ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card stat-green">
                            <h5>Programas Aprobados</h5>
                            <h2><?= htmlspecialchars($programas_aprobados) ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card stat-yellow">
                            <h5>Evaluaciones Pendientes</h5>
                            <h2><?= htmlspecialchars($evaluaciones_pendientes) ?></h2>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 fw-bold">Actividad Reciente</h6>
                        <a href="#" class="text-decoration-none text-muted small">Ver todo</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3 text-muted text-center">No hay actividad reciente.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
