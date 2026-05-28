<?php
class DependenciaController extends Controller {
    private $dependenciaModel;
    private $programaModel;
    private $evaluacionModel;
    private $cicloModel;
    private $asignacionModel;
    private $documentoModel;
    private $bimestreModel;
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
        require_once APP_PATH . '/models/AsignacionModel.php';
        require_once APP_PATH . '/models/DocumentoModel.php';
        require_once APP_PATH . '/models/BimestreModel.php';

        $this->dependenciaModel = new DependenciaModel();
        $this->programaModel = new ProgramaModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->cicloModel = new CicloModel();
        $this->asignacionModel = new AsignacionModel();
        $this->documentoModel = new DocumentoModel();
        $this->bimestreModel = new BimestreModel();

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
        $alumnos = $this->asignacionModel->obtenerAlumnosActivosPorDependencia($this->id_dependencia);
        $this->view('dependencia/alumnos', [
            'alumnos' => $alumnos
        ]);
    }

    public function reportes_bimestrales() {
        $programas = $this->programaModel->getProgramasPorDependencia($this->id_dependencia);
        
        $id_programa = isset($_GET['id_programa']) ? (int) $_GET['id_programa'] : 0;
        $id_alumno = isset($_GET['id_alumno']) ? (int) $_GET['id_alumno'] : 0;
        
        if ($id_programa == 0 && count($programas) > 0) {
            $id_programa = $programas[0]['id_programa'];
        }

        $alumnos = [];
        $alumno_seleccionado = null;
        $estadoBimestres = [];

        if ($id_programa > 0) {
            // Get all students active in this program
            // Wait, we need a query to get active students by program.
            $todosAlumnos = $this->asignacionModel->obtenerAlumnosActivosPorDependencia($this->id_dependencia);
            foreach ($todosAlumnos as $al) {
                if ($al['id_programa'] == $id_programa) {
                    $alumnos[] = $al;
                }
            }

            if ($id_alumno > 0) {
                foreach ($alumnos as $al) {
                    if ($al['id_alumno'] == $id_alumno) {
                        $alumno_seleccionado = $al;
                        break;
                    }
                }
            } elseif (count($alumnos) > 0) {
                $alumno_seleccionado = $alumnos[0];
            }
        }

        if ($alumno_seleccionado) {
            $idAlumnoSel = $alumno_seleccionado['id_alumno'];
            $bimestresConfig = $this->bimestreModel->obtenerBimestresPorPrograma($id_programa);
            $todosDocumentos = $this->documentoModel->obtenerPorAlumno($idAlumnoSel);

            $estadoBimestres = [
                1 => ['habilitado' => false, 'evaluacion_subida' => false, 'bloqueado' => false, 'documento' => null],
                2 => ['habilitado' => false, 'evaluacion_subida' => false, 'bloqueado' => true, 'documento' => null],
                3 => ['habilitado' => false, 'evaluacion_subida' => false, 'bloqueado' => true, 'documento' => null]
            ];

            foreach ($bimestresConfig as $num => $config) {
                $estadoBimestres[$num]['habilitado'] = (bool)$config['habilitado'];
                $estadoBimestres[$num]['config'] = $config;
            }

            foreach ($todosDocumentos as $doc) {
                for ($i = 1; $i <= 3; $i++) {
                    // Check if Dependencia uploaded the eval
                    if (str_contains($doc['tipo_documento'], "EVALUACIÓN CUALITATIVA DEL PRESTADOR DE SERVICIO SOCIAL $i")) {
                        $estadoBimestres[$i]['evaluacion_subida'] = true;
                        $estadoBimestres[$i]['documento'] = $doc;
                    }
                }
            }

            // Bloqueo estricto
            $estadoBimestres[1]['bloqueado'] = !$estadoBimestres[1]['habilitado'];
            if ($estadoBimestres[1]['evaluacion_subida'] && $estadoBimestres[2]['habilitado']) {
                $estadoBimestres[2]['bloqueado'] = false;
            }
            if ($estadoBimestres[2]['evaluacion_subida'] && $estadoBimestres[3]['habilitado']) {
                $estadoBimestres[3]['bloqueado'] = false;
            }
        }

        $this->view('dependencia/reportes_bimestrales', [
            'programas' => $programas,
            'id_programa_seleccionado' => $id_programa,
            'alumnos' => $alumnos,
            'alumno_seleccionado' => $alumno_seleccionado,
            'estadoBimestres' => $estadoBimestres,
            'paginaActiva' => 'reportes_bimestrales'
        ]);
    }

    public function perfil_alumno(int $idAlumno = 0) {
        if ($idAlumno <= 0) {
            $this->redirect('dependencia/alumnos');
            return;
        }

        // Verify if the student is active in one of the dependency's programs
        $alumnosActivos = $this->asignacionModel->obtenerAlumnosActivosPorDependencia($this->id_dependencia);
        $alumno_seleccionado = null;
        foreach ($alumnosActivos as $al) {
            if ($al['id_alumno'] == $idAlumno) {
                $alumno_seleccionado = $al;
                break;
            }
        }

        if (!$alumno_seleccionado) {
            $_SESSION['flash_error'] = 'El alumno no está asignado a tu dependencia o no está activo.';
            $this->redirect('dependencia/alumnos');
            return;
        }

        // Get documents
        require_once APP_PATH . '/models/AlumnoModel.php';
        $alumnoModel = new AlumnoModel();
        $alumnoDetalle = $alumnoModel->obtenerPorId($idAlumno);
        $documentos = $this->documentoModel->obtenerPorAlumno($idAlumno);

        $this->view('dependencia/perfil_alumno', [
            'alumno' => $alumnoDetalle,
            'asignacion' => $alumno_seleccionado,
            'documentos' => $documentos
        ]);
    }

    public function dar_baja_alumno() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idAlumno = (int) ($_POST['id_alumno'] ?? 0);
            $idPrograma = (int) ($_POST['id_programa'] ?? 0);
            $motivo = trim($_POST['motivo'] ?? '');
            
            if ($idAlumno > 0 && $idPrograma > 0 && !empty($motivo)) {
                // Verify ownership of the program
                $programa = $this->programaModel->obtenerProgramaPorId($idPrograma);
                if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                    if ($this->asignacionModel->actualizarEstadoAsignacion($idAlumno, $idPrograma, 'Baja', $motivo)) {
                        require_once APP_PATH . '/models/Database.php';
                        $db = Database::getInstance()->getConnection();
                        $db->prepare("UPDATE alumnos SET estado_servicio = 'Interrumpido' WHERE id_alumno = ?")->execute([$idAlumno]);

                        $_SESSION['flash_success'] = 'Alumno dado de baja correctamente.';
                    } else {
                        $_SESSION['flash_error'] = 'No se pudo dar de baja al alumno.';
                    }
                } else {
                    $_SESSION['flash_error'] = 'No tienes permiso para modificar este programa.';
                }
            } else {
                $_SESSION['flash_error'] = 'El motivo de baja es obligatorio.';
            }
        }
        $this->redirect('dependencia/alumnos');
    }

    public function subir_evaluacion_bimestral() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['evaluacion_pdf'])) {
            $this->redirect('dependencia/reportes_bimestrales');
            return;
        }

        $idAlumno = (int) $_POST['id_alumno'];
        $idPrograma = (int) $_POST['id_programa'];
        $numBimestre = (int) $_POST['numero_bimestre'];
        
        $uploadDir = APP_PATH . '/public/uploads/documentos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $tipoDoc = "EVALUACIÓN CUALITATIVA DEL PRESTADOR DE SERVICIO SOCIAL $numBimestre";
        
        if ($_FILES['evaluacion_pdf']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['evaluacion_pdf']['tmp_name'];
            $originalName = $_FILES['evaluacion_pdf']['name'];
            
            $safeName = preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $originalName);
            $newName = time() . '_' . $idAlumno . '_' . $safeName;
            $destPath = $uploadDir . $newName;

            if (move_uploaded_file($tmpName, $destPath)) {
                $rutaDB = '/public/uploads/documentos/' . $newName;
                $this->documentoModel->subirDocumento($idAlumno, $tipoDoc, $originalName, $rutaDB);
                $_SESSION['flash_success'] = "Evaluación del Bimestre $numBimestre subida correctamente.";
            } else {
                $_SESSION['flash_error'] = "Error al mover el archivo al servidor.";
            }
        } else {
            $_SESSION['flash_error'] = "Error al subir el archivo PDF.";
        }

        $this->redirect("dependencia/reportes_bimestrales?id_programa=$idPrograma&id_alumno=$idAlumno");
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

    public function configurar_bimestres() {
        if (isset($_GET['id'])) {
            $idPrograma = (int)$_GET['id'];
            $programa = $this->programaModel->obtenerProgramaPorId($idPrograma);
            
            // Validate ownership
            if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                $bimestres = $this->bimestreModel->obtenerBimestresPorPrograma($idPrograma);
                $this->view('dependencia/configurar_bimestres', [
                    'programa' => $programa,
                    'bimestres' => $bimestres
                ]);
                return;
            }
        }
        $this->redirect('dependencia/misProgramas');
    }

    public function guardar_configuracion_bimestres() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_programa'])) {
            $idPrograma = (int)$_POST['id_programa'];
            $programa = $this->programaModel->obtenerProgramaPorId($idPrograma);
            
            // Validate ownership
            if ($programa && $programa['id_dependencia'] == $this->id_dependencia) {
                // Process each of the 3 bimestres
                for ($i = 1; $i <= 3; $i++) {
                    $fechaInicio = !empty($_POST["fecha_inicio_$i"]) ? $_POST["fecha_inicio_$i"] : null;
                    $fechaFin = !empty($_POST["fecha_fin_$i"]) ? $_POST["fecha_fin_$i"] : null;
                    $habilitado = isset($_POST["habilitado_$i"]) ? true : false;

                    $this->bimestreModel->guardarBimestre($idPrograma, $i, $fechaInicio, $fechaFin, $habilitado);
                }
                
                $_SESSION['flash_success'] = 'Configuración de bimestres guardada correctamente.';
                $this->redirect("dependencia/configurar_bimestres?id={$idPrograma}");
                return;
            }
        }
        $this->redirect('dependencia/misProgramas');
    }
}
