<?php
class DependenciaController extends Controller {
    private $dependenciaModel;
    private $programaModel;
    private $evaluacionModel;
    private $cicloModel;
    private $id_dependencia;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Dependencia') {
            $this->redirect('auth/login');
        }

        require_once APP_PATH . '/models/DependenciaModel.php';
        require_once APP_PATH . '/models/ProgramaModel.php';
        require_once APP_PATH . '/models/EvaluacionModel.php';
        require_once APP_PATH . '/models/CicloModel.php';

        $this->dependenciaModel = new DependenciaModel();
        $this->programaModel = new ProgramaModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->cicloModel = new CicloModel();

        $this->id_dependencia = $this->dependenciaModel->getIdDependenciaPorUsuario($_SESSION['id_usuario']);
    }

    public function index() {
        $this->redirect('dependencia/dashboard');
    }

    public function dashboard() {
        $alumnos_activos = $this->dependenciaModel->getAlumnosActivosCount($this->id_dependencia);
        $programas_aprobados = $this->dependenciaModel->getProgramasAprobadosCount($this->id_dependencia);
        $evaluaciones_pendientes = $this->dependenciaModel->getEvaluacionesPendientesCount($this->id_dependencia);

        $this->view('dependencia/dashboard', [
            'alumnos_activos' => $alumnos_activos,
            'programas_aprobados' => $programas_aprobados,
            'evaluaciones_pendientes' => $evaluaciones_pendientes
        ]);
    }

    public function misProgramas() {
        $programas = $this->programaModel->getProgramasPorDependencia($this->id_dependencia);
        $this->view('dependencia/programas', ['programas' => $programas]);
    }

    public function alumnos() {
        $alumnos = $this->evaluacionModel->getAlumnosParaEvaluacion($this->id_dependencia);
        
        $alumno_seleccionado = null;
        if (isset($_GET['alumno_id'])) {
            foreach ($alumnos as $al) {
                if ($al['id_alumno'] == $_GET['alumno_id']) {
                    $alumno_seleccionado = $al;
                    break;
                }
            }
        } else if (count($alumnos) > 0) {
            $alumno_seleccionado = $alumnos[0];
        }

        $this->view('dependencia/evaluaciones', [
            'alumnos' => $alumnos,
            'alumno_seleccionado' => $alumno_seleccionado
        ]);
    }

    public function guardarEvaluacion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_alumno = $_POST['id_alumno'];
            $id_programa = $_POST['id_programa'];
            $nivel_desempeno = $_POST['nivel_desempeno'];
            $comentarios = $_POST['comentarios_supervisor'];
            $fecha = date('Y-m-d');

            $this->evaluacionModel->guardarEvaluacion($id_alumno, $id_programa, $nivel_desempeno, $comentarios, $fecha);
            
            $this->redirect('dependencia/alumnos?success=1');
        }
    }

    public function crearPrograma() {
        $ciclos = $this->cicloModel->obtenerCiclosActuales();
        $this->view('dependencia/crear_programa', ['ciclos' => $ciclos]);
    }

    public function guardarPrograma() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['id_dependencia'] = $this->id_dependencia;
            $_POST['folio_programa'] = 'PRG-' . strtoupper(substr(md5(uniqid()), 0, 6));
            if (!isset($_POST['descripcion'])) $_POST['descripcion'] = '';
            if (!isset($_POST['perfiles_requeridos'])) $_POST['perfiles_requeridos'] = '';
            
            $horarios = [];
            if (isset($_POST['horarios_dias']) && is_array($_POST['horarios_dias'])) {
                foreach ($_POST['horarios_dias'] as $index => $dia) {
                    if (!empty($dia) && !empty($_POST['horarios_inicios'][$index]) && !empty($_POST['horarios_fines'][$index])) {
                        $horarios[] = [
                            'dia' => $dia,
                            'inicio' => $_POST['horarios_inicios'][$index],
                            'fin' => $_POST['horarios_fines'][$index]
                        ];
                    }
                }
            }

            $ciclosSeleccionados = $_POST['ciclos'] ?? [];

            $this->programaModel->crearPrograma($_POST, $horarios, $ciclosSeleccionados);
            $this->redirect('dependencia/misProgramas?success=creado');
        }
    }

    public function editarPrograma() {
        if (isset($_GET['id'])) {
            $programa = $this->programaModel->obtenerProgramaPorId((int)$_GET['id']);
            if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                $ciclos = $this->cicloModel->obtenerTodosLosCiclos();
                $ciclosPrograma = $this->programaModel->obtenerCiclosPorPrograma($programa['id_programa']);
                $horariosPrograma = $this->programaModel->obtenerHorariosPorPrograma($programa['id_programa']);
                
                $this->view('dependencia/editar_programa', [
                    'programa' => $programa,
                    'ciclos' => $ciclos,
                    'ciclosPrograma' => $ciclosPrograma,
                    'horariosPrograma' => $horariosPrograma
                ]);
                return;
            }
        }
        $this->redirect('dependencia/misProgramas');
    }

    public function actualizarPrograma() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_programa'])) {
            $id = (int)$_POST['id_programa'];
            $programa = $this->programaModel->obtenerProgramaPorId($id);
            if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                $horarios = [];
                if (isset($_POST['horarios_dias']) && is_array($_POST['horarios_dias'])) {
                    foreach ($_POST['horarios_dias'] as $index => $dia) {
                        if (!empty($dia) && !empty($_POST['horarios_inicios'][$index]) && !empty($_POST['horarios_fines'][$index])) {
                            $horarios[] = [
                                'dia' => $dia,
                                'inicio' => $_POST['horarios_inicios'][$index],
                                'fin' => $_POST['horarios_fines'][$index]
                            ];
                        }
                    }
                }
                
                $ciclosSeleccionados = $_POST['ciclos'] ?? [];
                
                $this->programaModel->actualizarPrograma($id, $_POST, $horarios, $ciclosSeleccionados);
                $this->redirect('dependencia/misProgramas?success=actualizado');
                return;
            }
        }
        $this->redirect('dependencia/misProgramas');
    }

    public function eliminarPrograma() {
        if (isset($_GET['id'])) {
            if ($this->cicloModel->hayCicloActivo()) {
                $this->redirect('dependencia/misProgramas?error=ciclo_activo');
                return;
            }

            $id = (int)$_GET['id'];
            $programa = $this->programaModel->obtenerProgramaPorId($id);
            
            if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                // Notificar y desvincular alumnos
                $alumnos = $this->programaModel->obtenerAlumnosPorPrograma($id);
                $mensaje = "El programa '{$programa['nombre_programa']}' al que estabas inscrito ha sido cancelado por la dependencia. Por favor, inscríbete a un nuevo programa.";
                $this->programaModel->notificarYDesvincularAlumnos($alumnos, $mensaje);
                
                // Eliminar programa
                $this->programaModel->eliminarPrograma($id);
                $this->redirect('dependencia/misProgramas?success=eliminado');
                return;
            }
        }
        $this->redirect('dependencia/misProgramas');
    }
}
