<?php
class DependenciaController extends Controller {
    private $dependenciaModel;
    private $programaModel;
    private $evaluacionModel;
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

        $this->dependenciaModel = new DependenciaModel();
        $this->programaModel = new ProgramaModel();
        $this->evaluacionModel = new EvaluacionModel();

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
}
