<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumno - Sistema de Gestión del Servicio Social ITCH</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 550px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .brand-text {
            color: #0b5ed7; /* Azul Tecnológico */
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 register-container">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="brand-text">Registro de Alumno</h3>
                        <p class="text-muted">Instituto Tecnológico de Chetumal</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/auth/guardar_registro" method="POST">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Información de la Cuenta</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@itch.edu.mx" required>
                                    <label for="correo">Correo Institucional *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                    <label for="password">Contraseña *</label>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-muted mb-3 border-bottom pb-2">Información del Estudiante</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="no_control" name="no_control" placeholder="Ej. 22560001" required>
                                    <label for="no_control">No. Control *</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Nombre completo" required>
                                    <label for="nombre_completo">Nombre Completo *</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="carrera" name="carrera" placeholder="Ingeniería..." required>
                                    <label for="carrera">Carrera *</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" id="porcentaje_creditos" name="porcentaje_creditos" placeholder="0.00" required>
                                    <label for="porcentaje_creditos">% Créditos (Ej. 70.5) *</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-0">¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none">Inicia Sesión</a></p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 text-muted">
                <small>&copy; <?= date('Y') ?> Instituto Tecnológico de Chetumal. Todos los derechos reservados.</small>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
