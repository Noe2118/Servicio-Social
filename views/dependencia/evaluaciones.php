<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos y Evaluaciones - Dependencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: white; border-right: 1px solid #ddd; }
        .navbar-custom { background-color: #0b3d91; color: white; }
        .nav-link.active { background-color: #f0f4f8; color: #0b3d91; font-weight: bold; border-right: 4px solid #0b3d91; }
        .student-item { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; display: block; text-decoration: none; color: inherit; }
        .student-item:hover { background-color: #f8f9fa; }
        .student-item.active { background-color: #e6f0ff; border-left: 4px solid #0b3d91; }
        .student-initial { width: 45px; height: 45px; border-radius: 50%; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;}
        .card-header-white { background-color: white; border-bottom: 1px solid #eee; }
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
                <small class="d-block lh-1 text-light">DIF Municipal</small>
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
                        <a class="nav-link text-dark" href="/dependencia/misProgramas">Mis Programas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="/dependencia/alumnos">Alumnos y Evaluaciones</a>
                    </li>
                    <li class="nav-item mt-5">
                        <a class="nav-link text-danger fw-bold" href="/auth/logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-10 p-4">
                <h3 class="fw-bold mb-2">Alumnos y Evaluaciones (Seguimiento)</h3>
                <p class="text-muted mb-4">Gestiona el seguimiento y realiza la evaluación cualitativa de los estudiantes asignados a tus proyectos de vinculación.</p>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Evaluación guardada correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Lista de alumnos -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header card-header-white py-3">
                                <input type="text" class="form-control bg-light" placeholder="🔍 Filtrar alumnos...">
                            </div>
                            <div class="list-group list-group-flush">
                                <?php if (!empty($alumnos)): ?>
                                    <?php foreach ($alumnos as $al): ?>
                                        <a href="?alumno_id=<?= $al['id_alumno'] ?>" class="student-item <?= ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']) ? 'active' : '' ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="student-initial me-3 text-secondary">
                                                    <?= substr($al['nombre_completo'], 0, 1) ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold <?= ($alumno_seleccionado && $alumno_seleccionado['id_alumno'] == $al['id_alumno']) ? 'text-primary' : 'text-dark' ?>"><?= htmlspecialchars($al['nombre_completo']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($al['carrera']) ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted">No hay alumnos asignados en programas activos.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Detalle del alumno -->
                    <div class="col-md-8">
                        <?php if ($alumno_seleccionado): ?>
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="student-initial me-3 bg-secondary text-white" style="width: 70px; height: 70px; font-size: 2rem;">
                                                <?= substr($alumno_seleccionado['nombre_completo'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <h3 class="mb-1 fw-bold"><?= htmlspecialchars($alumno_seleccionado['nombre_completo']) ?></h3>
                                                <p class="text-muted mb-0">👁 <?= htmlspecialchars($alumno_seleccionado['carrera']) ?></p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-outline-primary fw-bold px-4 mb-2">📄 Ver Reportes del Alumno</button>
                                            <p class="text-muted small">No. Control: <?= htmlspecialchars($alumno_seleccionado['no_control']) ?></p>
                                        </div>
                                    </div>
                                    
                                    <hr class="text-muted">

                                    <div class="row text-muted mt-3">
                                        <div class="col-md-4 border-end">
                                            <small class="d-block mb-1">Programa</small>
                                            <p class="fw-bold text-dark mb-0"><?= htmlspecialchars($alumno_seleccionado['nombre_programa']) ?></p>
                                        </div>
                                        <div class="col-md-3 border-end">
                                            <small class="d-block mb-1">Horas Acumuladas</small>
                                            <p class="fw-bold text-dark mb-0">0 / 480 hrs</p>
                                        </div>
                                        <div class="col-md-3 border-end">
                                            <small class="d-block mb-1">Periodo</small>
                                            <p class="fw-bold text-dark mb-0">Ene-Jun 2026</p>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <small class="d-block mb-1">Estado</small>
                                            <span class="badge bg-success rounded-pill px-3">En curso</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de Evaluación -->
                            <div class="card shadow-sm border-0 border-top border-primary border-4">
                                <div class="card-header bg-white py-3 border-bottom-0">
                                    <h5 class="mb-0 fw-bold text-primary">📝 Evaluación Cualitativa</h5>
                                </div>
                                <div class="card-body p-4 pt-0">
                                    <form action="/dependencia/guardarEvaluacion" method="POST">
                                        <input type="hidden" name="id_alumno" value="<?= $alumno_seleccionado['id_alumno'] ?>">
                                        <input type="hidden" name="id_programa" value="<?= $alumno_seleccionado['id_programa'] ?>">
                                        
                                        <div class="row mb-4 mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Nivel de Desempeño General</label>
                                                <select name="nivel_desempeno" class="form-select form-select-lg" required>
                                                    <option value="">Seleccione una calificación...</option>
                                                    <option value="Excelente">Excelente</option>
                                                    <option value="Notable">Notable</option>
                                                    <option value="Bueno">Bueno</option>
                                                    <option value="Suficiente">Suficiente</option>
                                                    <option value="Insuficiente">Insuficiente</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Fecha de Evaluación</label>
                                                <input type="date" class="form-control form-control-lg bg-light" name="fecha_evaluacion" value="<?= date('Y-m-d') ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold">Comentarios del Supervisor</label>
                                            <textarea name="comentarios_supervisor" class="form-control" rows="5" placeholder="Describa el progreso, fortalezas y áreas de oportunidad del alumno..." required></textarea>
                                            <small class="text-muted d-block mt-1">Requerido para evaluaciones "Regular" o "Insuficiente"</small>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success btn-lg px-5 fw-bold">✔ Guardar Evaluación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card shadow-sm border-0 p-5 text-center text-muted d-flex justify-content-center align-items-center" style="min-height: 400px;">
                                <div>
                                    <h4 class="mb-3">Ningún alumno seleccionado</h4>
                                    <p>Seleccione un alumno de la lista lateral para visualizar sus detalles y realizar su evaluación cualitativa.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
