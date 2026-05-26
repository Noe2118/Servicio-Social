<?php

class AlumnoController extends Controller {
    private AlumnoModel $alumnoModel;
    private ProgramaModel $programaModel;
    private AsignacionModel $asignacionModel;
    private ?array $alumno = null;

    public function __construct() {
        // Verificar sesión activa y rol de Alumno
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Alumno') {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/auth/login');
            exit;
        }

        $this->alumnoModel = new AlumnoModel();
        $this->programaModel = new ProgramaModel();
        $this->asignacionModel = new AsignacionModel();

        // Obtener datos del alumno logueado
        $this->alumno = $this->alumnoModel->obtenerPorUsuarioId($_SESSION['id_usuario']);
    }

    /**
     * Método por defecto de /alumno
     */
    public function index(): void {
        $this->redirect('alumno/dashboard');
    }

    /**
     * Dashboard principal del alumno — Mi Progreso
     * Endpoint: GET /alumno/dashboard
     */
    public function dashboard(): void {
        $alumno = $this->alumno;
        $asignacion = null;

        if ($alumno) {
            $asignacion = $this->asignacionModel->obtenerAsignacionActiva($alumno['id_alumno']);
        }

        // Calcular porcentaje de horas (meta: 500 horas)
        $totalHorasMeta = 500;
        $horasCompletadas = $alumno['horas_completadas'] ?? 0;
        $porcentajeHoras = $totalHorasMeta > 0 
            ? round(($horasCompletadas / $totalHorasMeta) * 100) 
            : 0;

        $this->view('alumno/dashboard', [
            'titulo' => 'Mi Progreso',
            'alumno' => $alumno,
            'asignacion' => $asignacion,
            'horasCompletadas' => $horasCompletadas,
            'totalHorasMeta' => $totalHorasMeta,
            'porcentajeHoras' => $porcentajeHoras,
            'paginaActiva' => 'dashboard'
        ]);
    }

    /**
     * Catálogo de programas disponibles
     * Endpoint: GET /alumno/catalogo
     */
    public function catalogo(): void {
        // Leer filtros desde query string
        $modalidad = $_GET['modalidad'] ?? null;
        $idDependencia = isset($_GET['dependencia']) ? (int) $_GET['dependencia'] : null;

        $programas = $this->programaModel->obtenerProgramasDisponibles($modalidad, $idDependencia);
        $dependencias = $this->programaModel->obtenerDependenciasConProgramas();

        $this->view('alumno/catalogo', [
            'titulo' => 'Catálogo de Programas',
            'programas' => $programas,
            'dependencias' => $dependencias,
            'filtroModalidad' => $modalidad,
            'filtroDependencia' => $idDependencia,
            'alumno' => $this->alumno,
            'paginaActiva' => 'catalogo'
        ]);
    }

    /**
     * Detalle de un programa individual
     * Endpoint: GET /alumno/detalle_programa/{id}
     */
    public function detalle_programa(int $idPrograma = 0): void {
        if ($idPrograma <= 0) {
            $this->redirect('alumno/catalogo');
            return;
        }

        $programa = $this->programaModel->obtenerPorId($idPrograma);

        if (!$programa) {
            $this->redirect('alumno/catalogo');
            return;
        }

        // Verificar si el alumno ya solicitó este programa
        $yaSolicitado = false;
        if ($this->alumno) {
            $yaSolicitado = $this->asignacionModel->yaHaSolicitadoPrograma(
                $this->alumno['id_alumno'],
                $idPrograma
            );
        }

        $this->view('alumno/detalle_programa', [
            'titulo' => $programa['nombre_programa'],
            'programa' => $programa,
            'yaSolicitado' => $yaSolicitado,
            'alumno' => $this->alumno,
            'paginaActiva' => 'catalogo'
        ]);
    }

    /**
     * Procesar solicitud de inscripción a un programa.
     * Endpoint: POST /alumno/solicitar_inscripcion
     */
    public function solicitar_inscripcion(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método HTTP no permitido.'], 405);
            return;
        }

        if (!$this->alumno) {
            $this->jsonResponse(['error' => 'No se encontró perfil de alumno.'], 403);
            return;
        }

        $idPrograma = isset($_POST['id_programa']) ? (int) $_POST['id_programa'] : 0;

        if ($idPrograma <= 0) {
            $this->jsonResponse(['error' => 'Programa no válido.'], 400);
            return;
        }

        $idAlumno = (int) $this->alumno['id_alumno'];

        try {
            // 1. Verificar que el alumno no tenga ya una asignación activa/pendiente
            if ($this->asignacionModel->tieneAsignacionActiva($idAlumno)) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Ya tienes una solicitud activa o pendiente. No puedes inscribirte a otro programa.'
                ], 409);
                return;
            }

            // 2. Verificar que el programa aún tenga cupos
            $programa = $this->programaModel->obtenerPorId($idPrograma);
            if (!$programa || $programa['cupos_disponibles'] <= 0) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Este programa ya no tiene cupos disponibles.'
                ], 409);
                return;
            }

            // 3. Crear la asignación e incrementar cupos ocupados
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();

            $this->asignacionModel->crearSolicitud($idAlumno, $idPrograma);
            $this->programaModel->incrementarCuposOcupados($idPrograma);

            $db->commit();

            $this->jsonResponse([
                'status' => 'success',
                'message' => '¡Solicitud enviada exitosamente! Tu inscripción está pendiente de aprobación.'
            ]);
        } catch (\PDOException $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error en solicitud de inscripción: " . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar tu solicitud. Intenta de nuevo.'
            ], 500);
        }
    }

    /**
     * Mi Expediente — Vista estática (maqueta para siguiente fase)
     * Endpoint: GET /alumno/expediente
     */
    public function expediente(): void {
        $this->view('alumno/expediente', [
            'titulo' => 'Mi Expediente y Seguimiento',
            'alumno' => $this->alumno,
            'paginaActiva' => 'expediente'
        ]);
    }

    /**
     * Endpoint para peticiones AJAX de subida de reportes (placeholder)
     * Endpoint: POST /alumno/subir_reporte
     */
    public function subir_reporte(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método HTTP no permitido.'], 405);
            return;
        }

        // Lógica de subida a implementar en fase posterior
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Reporte subido correctamente.',
            'fecha_recepcion' => date('Y-m-d H:i:s')
        ]);
    }
}
