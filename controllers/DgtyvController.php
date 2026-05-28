<?php

class DgtyvController extends Controller {
    private ProgramaModel $programaModel;
    private DocumentoModel $documentoModel;
    private AlumnoModel $alumnoModel;
    private CicloModel $cicloModel;
    private AsignacionModel $asignacionModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'DGTyV') {
            $this->redirect('auth/login');
            exit;
        }

        $this->programaModel = new ProgramaModel();
        $this->documentoModel = new DocumentoModel();
        $this->alumnoModel = new AlumnoModel();

        require_once APP_PATH . '/models/CicloModel.php';
        $this->cicloModel = new CicloModel();

        require_once APP_PATH . '/models/AsignacionModel.php';
        $this->asignacionModel = new AsignacionModel();
    }

    public function index(): void {
        $this->redirect('dgtyv/dashboard');
    }

    /**
     * Dashboard DGTyV — Resumen Operativo
     */
    public function dashboard(): void {
        $programasPendientes = $this->programaModel->contarPendientes();
        $documentosPendientes = $this->documentoModel->contarNuevos();
        $alumnosLiberacion = $this->alumnoModel->contarParaLiberacion();
        
        $documentos = $this->documentoModel->obtenerDocumentosConAlumno();
        $alertasUrgentes = array_slice($documentos, 0, 3);

        // --- Lógica de Actividad Reciente ---
        $docsRecientes = $this->documentoModel->obtenerDocumentosRecientes(5);
        $progsRecientes = $this->programaModel->obtenerProgramasRecientes(5);
        $actividades = [];
        
        foreach($docsRecientes as $doc) {
            $time = strtotime($doc['fecha_subida']);
            if ($doc['estado_validacion'] === 'Nuevo') {
                $titulo = "Nuevo documento recibido";
                $desc = $doc['tipo_documento'] . " de " . $doc['nombre_completo'];
                $color = 'var(--itch-accent)';
            } else if ($doc['estado_validacion'] === 'Corregido') {
                $titulo = "Documento corregido";
                $desc = $doc['tipo_documento'] . " de " . $doc['nombre_completo'];
                $color = 'var(--itch-warning)';
            } else {
                $titulo = "Documento " . strtolower($doc['estado_validacion']);
                $desc = $doc['tipo_documento'] . " de " . $doc['nombre_completo'];
                $color = $doc['estado_validacion'] === 'Aprobado' ? 'var(--itch-success)' : 'var(--itch-danger)';
            }
            $actividades[] = [
                'time' => $time,
                'titulo' => $titulo,
                'descripcion' => $desc,
                'color' => $color,
                'fecha_relativa' => $this->tiempoRelativo($time)
            ];
        }

        foreach($progsRecientes as $prog) {
            $time = strtotime($prog['fecha_envio']);
            if ($prog['estado_aprobacion'] === 'En Revisión por DGTyV') {
                $titulo = "Nuevo programa recibido";
                $desc = "\"" . $prog['nombre_programa'] . "\" enviado por " . $prog['nombre_organizacion'];
                $color = 'var(--itch-accent)';
            } else {
                $titulo = "Programa " . strtolower($prog['estado_aprobacion']);
                $desc = "\"" . $prog['nombre_programa'] . "\" fue " . strtolower($prog['estado_aprobacion']);
                $color = $prog['estado_aprobacion'] === 'Aprobado' ? 'var(--itch-success)' : 'var(--itch-danger)';
            }
            $actividades[] = [
                'time' => $time,
                'titulo' => $titulo,
                'descripcion' => $desc,
                'color' => $color,
                'fecha_relativa' => $this->tiempoRelativo($time)
            ];
        }

        usort($actividades, function($a, $b) { return $b['time'] <=> $a['time']; });
        $actividades = array_slice($actividades, 0, 5);

        $this->view('dgtyv/dashboard', [
            'titulo' => 'Resumen Operativo',
            'programasPendientes' => $programasPendientes,
            'documentosPendientes' => $documentosPendientes,
            'alumnosLiberacion' => $alumnosLiberacion,
            'alertasUrgentes' => $alertasUrgentes,
            'actividades' => $actividades,
            'paginaActiva' => 'dashboard'
        ]);
    }

    private function tiempoRelativo($time) {
        $diff = time() - $time;
        if ($diff < 60) return "Hace un momento";
        if ($diff < 3600) return "Hace " . floor($diff/60) . " minutos";
        if ($diff < 86400) return "Hace " . floor($diff/3600) . " horas";
        if ($diff < 172800) return "Hace 1 día";
        return "Hace " . floor($diff/86400) . " días";
    }

    /**
     * Bandeja de Programas por Aprobar
     */
    public function programas(): void {
        $programas = $this->programaModel->obtenerPendientesConDependencia();
        
        $programaSeleccionado = null;
        $idSeleccionado = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        
        if ($idSeleccionado > 0) {
            $programaSeleccionado = $this->programaModel->obtenerProgramaPorId($idSeleccionado);
        } elseif (!empty($programas)) {
            $programaSeleccionado = $this->programaModel->obtenerProgramaPorId($programas[0]['id_programa']);
        }

        $this->view('dgtyv/programas', [
            'titulo' => 'Bandeja de Entrada',
            'programas' => $programas,
            'programaSeleccionado' => $programaSeleccionado,
            'paginaActiva' => 'programas'
        ]);
    }

    /**
     * POST: Aprobar programa
     */
    public function aprobar_programa(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/programas');
            return;
        }
        $id = (int) ($_POST['id_programa'] ?? 0);
        if ($id > 0) {
            $this->programaModel->actualizarEstado($id, 'Aprobado');
            $_SESSION['flash_success'] = 'Programa aprobado exitosamente.';
        }
        $this->redirect('dgtyv/programas');
    }

    /**
     * POST: Rechazar programa
     */
    public function rechazar_programa(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/programas');
            return;
        }
        $id = (int) ($_POST['id_programa'] ?? 0);
        if ($id > 0) {
            $this->programaModel->actualizarEstado($id, 'Rechazado');
            $_SESSION['flash_error'] = 'Programa rechazado.';
        }
        $this->redirect('dgtyv/programas');
    }

    /**
     * Revisión de Expedientes
     */
    public function expedientes(): void {
        $documentos = $this->documentoModel->obtenerDocumentosConAlumno();
        
        $documentoSeleccionado = null;
        $idSeleccionado = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($idSeleccionado > 0) {
            $documentoSeleccionado = $this->documentoModel->obtenerDocumentoPorIdAdmin($idSeleccionado);
        } elseif (!empty($documentos)) {
            $documentoSeleccionado = $this->documentoModel->obtenerDocumentoPorIdAdmin($documentos[0]['id_documento']);
        }

        $this->view('dgtyv/expedientes', [
            'titulo' => 'Revisión de Expedientes',
            'documentos' => $documentos,
            'documentoSeleccionado' => $documentoSeleccionado,
            'paginaActiva' => 'expedientes'
        ]);
    }

    /**
     * POST: Aprobar documento
     */
    public function aprobar_documento(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/expedientes');
            return;
        }
        $id = (int) ($_POST['id_documento'] ?? 0);
        $comentarios = $_POST['comentarios'] ?? null;
        if ($id > 0) {
            $this->documentoModel->actualizarEstadoConComentarios($id, 'Aprobado', $comentarios);
            $_SESSION['flash_success'] = 'Documento aprobado exitosamente.';
        }
        $this->redirect('dgtyv/expedientes');
    }

    /**
     * POST: Rechazar documento
     */
    public function rechazar_documento(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/expedientes');
            return;
        }
        $id = (int) ($_POST['id_documento'] ?? 0);
        $comentarios = $_POST['comentarios'] ?? null;
        if ($id > 0) {
            $this->documentoModel->actualizarEstadoConComentarios($id, 'Rechazado', $comentarios);
            $_SESSION['flash_error'] = 'Documento rechazado.';
        }
        $this->redirect('dgtyv/expedientes');
    }

    /**
     * POST: Aceptar alumno en el programa
     */
    public function aceptar_alumno(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idAlumno = (int) ($_POST['id_alumno'] ?? 0);
            $idPrograma = (int) ($_POST['id_programa'] ?? 0);
            if ($idAlumno > 0 && $idPrograma > 0) {
                if ($this->asignacionModel->actualizarEstadoAsignacion($idAlumno, $idPrograma, 'Activo')) {
                    // Update the student's status to En curso
                    $db = Database::getInstance()->getConnection();
                    $db->prepare("UPDATE alumnos SET estado_servicio = 'En curso' WHERE id_alumno = ?")->execute([$idAlumno]);
                    
                    $_SESSION['flash_success'] = 'Alumno aceptado en el programa exitosamente.';
                } else {
                    $_SESSION['flash_error'] = 'No se pudo aceptar al alumno.';
                }
            }
        }
        $this->redirect('dgtyv/expedientes');
    }

    /**
     * POST: Dar de baja a un alumno (Director)
     */
    public function dar_baja_alumno(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idAlumno = (int) ($_POST['id_alumno'] ?? 0);
            $idPrograma = (int) ($_POST['id_programa'] ?? 0);
            $motivo = trim($_POST['motivo'] ?? '');
            if ($idAlumno > 0 && $idPrograma > 0 && !empty($motivo)) {
                if ($this->asignacionModel->actualizarEstadoAsignacion($idAlumno, $idPrograma, 'Baja', $motivo)) {
                    $db = Database::getInstance()->getConnection();
                    $db->prepare("UPDATE alumnos SET estado_servicio = 'Interrumpido' WHERE id_alumno = ?")->execute([$idAlumno]);

                    $_SESSION['flash_success'] = 'Alumno dado de baja correctamente.';
                } else {
                    $_SESSION['flash_error'] = 'No se pudo dar de baja al alumno.';
                }
            } else {
                $_SESSION['flash_error'] = 'El motivo de baja es obligatorio.';
            }
        }
        $this->redirect('dgtyv/expedientes');
    }

    /**
     * Módulo de Liberación
     */
    public function liberacion(): void {
        $alumnos = $this->alumnoModel->obtenerAlumnosParaLiberacion();

        $this->view('dgtyv/liberacion', [
            'titulo' => 'Módulo de Liberación',
            'alumnos' => $alumnos,
            'paginaActiva' => 'liberacion'
        ]);
    }

    /**
     * POST: Emitir constancia de liberación
     */
    public function emitir_liberacion(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/liberacion');
            return;
        }
        $ids = $_POST['alumnos'] ?? [];
        $count = 0;
        foreach ($ids as $idAlumno) {
            if ($this->alumnoModel->marcarLiberado((int) $idAlumno)) {
                $count++;
            }
        }
        if ($count > 0) {
            $_SESSION['flash_success'] = "Se emitieron {$count} constancia(s) de liberación.";
        } else {
            $_SESSION['flash_error'] = 'No se seleccionaron alumnos o ocurrió un error.';
        }
        $this->redirect('dgtyv/liberacion');
    }

    public function catalogo_programas(): void {
        $programas = $this->programaModel->obtenerTodosLosProgramas();
        
        foreach ($programas as &$prog) {
            $horarios = $this->programaModel->obtenerHorariosPorPrograma($prog['id_programa']);
            $strs = [];
            foreach ($horarios as $h) {
                $inicio = date('H:i', strtotime($h['hora_inicio']));
                $fin = date('H:i', strtotime($h['hora_fin']));
                $strs[] = $h['dia_semana'] . ' (' . $inicio . ' - ' . $fin . ')';
            }
            $prog['horarios_formateados'] = empty($strs) ? 'No definido' : implode(', ', $strs);
        }
        unset($prog);

        $this->view('dgtyv/catalogo_programas', [
            'titulo' => 'Catálogo de Programas',
            'programas' => $programas,
            'paginaActiva' => 'catalogo_programas'
        ]);
    }

    /**
     * Módulo para crear un programa nuevo (Admin DGTyV)
     */
    public function nuevo_programa(): void {
        $dependencias = $this->programaModel->obtenerTodasLasDependencias();
        $this->view('dgtyv/nuevo_programa', [
            'titulo' => 'Nuevo Programa',
            'dependencias' => $dependencias,
            'paginaActiva' => ''
        ]);
    }

    /**
     * POST: Guardar nuevo programa
     */
    public function guardar_programa(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dgtyv/nuevo_programa');
            return;
        }

        $datos = [
            'id_dependencia' => $_POST['id_dependencia'] ?? '',
            'folio_programa' => $_POST['folio_programa'] ?? '',
            'nombre_programa' => $_POST['nombre_programa'] ?? '',
            'modalidad' => $_POST['modalidad'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'perfiles_requeridos' => $_POST['perfiles_requeridos'] ?? '',
            'cupos_totales' => $_POST['cupos_totales'] ?? '',
            'dia_semana' => $_POST['dia_semana'] ?? '',
            'hora_inicio' => $_POST['hora_inicio'] ?? '',
            'hora_fin' => $_POST['hora_fin'] ?? '',
            'ubicacion' => $_POST['ubicacion'] ?? '',
            'responsable_nombre' => $_POST['responsable_nombre'] ?? '',
            'responsable_contacto' => $_POST['responsable_contacto'] ?? ''
        ];

        $horarios = [];
        if (!empty($_POST['dia_semana']) && !empty($_POST['hora_inicio']) && !empty($_POST['hora_fin'])) {
            $horarios[] = [
                'dia' => $_POST['dia_semana'],
                'inicio' => $_POST['hora_inicio'],
                'fin' => $_POST['hora_fin']
            ];
        }

        // Se usa la nueva firma (aunque sea DGTyV, por ahora pasamos vacíos o los horarios si se mandan)
        if ($this->programaModel->crearPrograma($datos, $horarios)) {
            $_SESSION['flash_success'] = 'Programa creado y aprobado exitosamente.';
            $this->redirect('dgtyv/programas');
        } else {
            $_SESSION['flash_error'] = 'Error al crear el programa.';
            $this->redirect('dgtyv/nuevo_programa');
        }
    }

    /**
     * Gestión de Ciclos
     */
    public function ciclos(): void {
        $ciclos = $this->cicloModel->obtenerTodosLosCiclos();
        $this->view('dgtyv/ciclos', [
            'titulo' => 'Gestión de Ciclos',
            'ciclos' => $ciclos,
            'paginaActiva' => 'ciclos'
        ]);
    }

    public function guardar_ciclo(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_ciclo']);
            $inicio = trim($_POST['fecha_inicio']);
            $fin = trim($_POST['fecha_fin']);

            if (!empty($nombre) && !empty($inicio) && !empty($fin)) {
                $this->cicloModel->crearCiclo($nombre, $inicio, $fin);
                $_SESSION['flash_success'] = 'Ciclo registrado con éxito.';
            } else {
                $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
            }
        }
        $this->redirect('dgtyv/ciclos');
    }

    public function eliminar_ciclo(): void {
        if (isset($_GET['id'])) {
            $this->cicloModel->eliminarCiclo((int)$_GET['id']);
            $_SESSION['flash_success'] = 'Ciclo eliminado.';
        }
        $this->redirect('dgtyv/ciclos');
    }
}
