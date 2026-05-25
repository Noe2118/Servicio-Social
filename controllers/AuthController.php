<?php
class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->verificarEInicializarAdmin();
    }

    /**
     * Verifica si existe un administrador, de lo contrario lo crea
     */
    private function verificarEInicializarAdmin() {
        try {
            $countAdmin = $this->usuarioModel->contarUsuariosPorRol('DGTyV');
            
            if ($countAdmin == 0) {
                // Crear administrador por defecto
                $correo = 'admin@itch.edu.mx';
                $password = password_hash('admin123', PASSWORD_DEFAULT);
                $this->usuarioModel->crearUsuario($correo, $password, 'DGTyV');
            }
        } catch (\PDOException $e) {
            // Manejar error silenciosamente si la tabla no existe o BD no está configurada
            error_log("Error inicializando admin: " . $e->getMessage());
        }
    }

    public function index() {
        $this->login();
    }

    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($correo) || empty($password)) {
                $error = "Por favor, complete todos los campos.";
            } else {
                $usuario = $this->usuarioModel->getUsuarioPorCorreo($correo);

                if ($usuario && password_verify($password, $usuario['password_hash'])) {
                    // Iniciar sesión
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    // Redirigir según el rol del usuario
                    if ($usuario['rol'] === 'DGTyV') {
                        $this->redirect('admin/dashboard'); // Rutas ficticias para las siguientes fases
                    } elseif ($usuario['rol'] === 'Alumno') {
                        $this->redirect('alumno/dashboard');
                    } else {
                        $this->redirect('dependencia/dashboard');
                    }
                } else {
                    $error = "Credenciales incorrectas.";
                }
            }
        }

        // Renderizar vista pasando posibles errores
        $this->view('auth/login', ['error' => $error]);
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->redirect('auth/login');
    }
}
