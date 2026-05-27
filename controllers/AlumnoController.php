<?php

class AlumnoController extends Controller {
    private AlumnoModel $alumnoModel;
    private ProgramaModel $programaModel;
    private AsignacionModel $asignacionModel;
    private DocumentoModel $documentoModel;
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
        $this->documentoModel = new DocumentoModel();

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

        // Estado de documentos iniciales
        $estadoDocumentos = [];
        if ($alumno) {
            $estadoDocumentos = $this->documentoModel->obtenerEstadoDocumentosIniciales($alumno['id_alumno']);
        }

        $this->view('alumno/dashboard', [
            'titulo' => 'Mi Progreso',
            'alumno' => $alumno,
            'asignacion' => $asignacion,
            'horasCompletadas' => $horasCompletadas,
            'totalHorasMeta' => $totalHorasMeta,
            'porcentajeHoras' => $porcentajeHoras,
            'estadoDocumentos' => $estadoDocumentos,
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
     * Mi Expediente — Vista de documentos
     * Endpoint: GET /alumno/expediente
     */
    public function expediente(): void {
        if (!$this->alumno) {
            $this->redirect('auth/login');
            return;
        }

        $asignacion = $this->asignacionModel->obtenerAsignacionActiva($this->alumno['id_alumno']);

        $documentos = $this->documentoModel->obtenerPorAlumno($this->alumno['id_alumno']);
        $estadoDocumentos = $this->documentoModel->obtenerEstadoDocumentosIniciales($this->alumno['id_alumno']);

        // Calcular porcentaje de expediente inicial
        $docsAprobados = 0;
        $totalDocsRequeridos = count($estadoDocumentos);
        foreach ($estadoDocumentos as $estado) {
            if ($estado === 'Aprobado') {
                $docsAprobados++;
            }
        }
        $progresoExpediente = $totalDocsRequeridos > 0 ? round(($docsAprobados / $totalDocsRequeridos) * 100) : 0;

        $this->view('alumno/expediente', [
            'titulo' => 'Mi Expediente y Seguimiento',
            'alumno' => $this->alumno,
            'asignacion' => $asignacion,
            'documentos' => $documentos,
            'estadoDocumentos' => $estadoDocumentos,
            'progresoExpediente' => $progresoExpediente,
            'paginaActiva' => 'expediente'
        ]);
    }

    /**
     * Endpoint para subida de documentos
     * Endpoint: POST /alumno/subir_documento
     */
    public function subir_documento(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('alumno/expediente');
            return;
        }

        if (!$this->alumno) {
            die("No se encontró perfil de alumno.");
        }

        $asignacion = $this->asignacionModel->obtenerAsignacionActiva($this->alumno['id_alumno']);
        if (!$asignacion) {
            $_SESSION['flash_error'] = 'No puedes subir documentos hasta que solicites inscripción a un programa.';
            $this->redirect('alumno/expediente');
            return;
        }

        $tipoDocumento = $_POST['tipo_documento'] ?? '';
        
        if (empty($tipoDocumento) || !isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Debe seleccionar un tipo de documento y un archivo válido.';
            $this->redirect('alumno/expediente');
            return;
        }

        $archivo = $_FILES['archivo'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if ($extension !== 'pdf') {
            $_SESSION['flash_error'] = 'Solo se permiten archivos PDF.';
            $this->redirect('alumno/expediente');
            return;
        }

        if ($archivo['size'] > 10 * 1024 * 1024) { // 10MB
            $_SESSION['flash_error'] = 'El archivo supera el tamaño máximo de 10MB.';
            $this->redirect('alumno/expediente');
            return;
        }

        // Crear directorio si no existe
        $uploadDir = APP_PATH . '/uploads/documentos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generar nombre seguro
        $safeName = time() . '_' . rand(1000, 9999) . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $archivo['name']);
        $rutaDestino = $uploadDir . $safeName;
        $rutaRelativa = '/uploads/documentos/' . $safeName;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Guardar en BD
            $this->documentoModel->subirDocumento(
                $this->alumno['id_alumno'],
                $tipoDocumento,
                $archivo['name'],
                $rutaRelativa
            );
            $_SESSION['flash_success'] = 'Documento subido correctamente.';
        } else {
            $_SESSION['flash_error'] = 'Ocurrió un error al guardar el archivo en el servidor.';
        }

        $this->redirect('alumno/expediente');
    }

    /**
     * Endpoint para eliminar documento (solo si es Nuevo o Rechazado)
     * Endpoint: POST /alumno/eliminar_documento
     */
    public function eliminar_documento(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->alumno) {
            $this->redirect('alumno/expediente');
            return;
        }

        $idDocumento = (int) ($_POST['id_documento'] ?? 0);
        if ($idDocumento > 0) {
            // Verificar si el documento existe y obtener la ruta antes de eliminarlo
            $doc = $this->documentoModel->obtenerPorId($idDocumento, $this->alumno['id_alumno']);
            
            if ($doc && in_array($doc['estado_validacion'], ['Nuevo', 'Rechazado'])) {
                // Eliminar de la base de datos
                if ($this->documentoModel->eliminarDocumento($idDocumento, $this->alumno['id_alumno'])) {
                    // Eliminar del servidor físico
                    $rutaFisica = APP_PATH . $doc['ruta_servidor'];
                    if (file_exists($rutaFisica)) {
                        unlink($rutaFisica);
                    }
                    $_SESSION['flash_success'] = 'Documento eliminado exitosamente.';
                } else {
                    $_SESSION['flash_error'] = 'No se pudo eliminar el documento de la base de datos.';
                }
            } else {
                $_SESSION['flash_error'] = 'El documento no existe o no puede ser eliminado en su estado actual.';
            }
        }

        $this->redirect('alumno/expediente');
    }
}
