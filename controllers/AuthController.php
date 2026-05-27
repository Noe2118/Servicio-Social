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
                        $this->redirect('dgtyv/dashboard');
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

    public function registro() {
        $this->view('auth/registro_alumno');
    }

    public function guardar_registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $no_control = $_POST['no_control'] ?? '';
            $nombre = $_POST['nombre_completo'] ?? '';
            $carrera = $_POST['carrera'] ?? '';
            $porcentaje = (float)($_POST['porcentaje_creditos'] ?? 0);

            if (empty($correo) || empty($password) || empty($no_control) || empty($nombre) || empty($carrera)) {
                $this->view('auth/registro_alumno', ['error' => 'Todos los campos marcados son obligatorios.']);
                return;
            }

            if ($this->usuarioModel->getUsuarioPorCorreo($correo)) {
                $this->view('auth/registro_alumno', ['error' => 'El correo ya está registrado.']);
                return;
            }

            // Validar No. Control
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare('SELECT id_alumno FROM alumnos WHERE no_control = ?');
            $stmt->execute([$no_control]);
            if ($stmt->fetch()) {
                $this->view('auth/registro_alumno', ['error' => 'El número de control ya está registrado.']);
                return;
            }

            try {
                $db->beginTransaction();
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $id_usuario = $this->usuarioModel->crearUsuario($correo, $password_hash, 'Alumno');

                $alumnoModel = new AlumnoModel();
                $alumnoModel->crearAlumno((int)$id_usuario, $no_control, $nombre, $carrera, $porcentaje);

                $db->commit();
                
                // Redirigir al login con éxito
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_success_auth'] = 'Registro exitoso. Ya puedes iniciar sesión.';
                $this->redirect('auth/login');
                
            } catch (Exception $e) {
                $db->rollBack();
                $this->view('auth/registro_alumno', ['error' => 'Hubo un error al crear la cuenta: ' . $e->getMessage()]);
            }
        } else {
            $this->redirect('auth/registro');
        }
    }
}
