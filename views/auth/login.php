<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión del Servicio Social ITCH</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 450px;
            margin-top: 80px;
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
        <div class="col-md-8 col-lg-6 login-container">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="brand-text">Servicio Social</h3>
                        <p class="text-muted">Instituto Tecnológico de Chetumal</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/login" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@itch.edu.mx" required>
                            <label for="correo">Correo Electrónico</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            <label for="password">Contraseña</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-0">¿No tienes cuenta? <a href="#" class="text-decoration-none">Regístrate como alumno</a></p>
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
