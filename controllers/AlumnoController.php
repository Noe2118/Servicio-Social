<?php

class AlumnoController extends Controller {

    public function __construct() {
        // Aquí podrías validar la sesión. Ejemplo:
        // si_no_es_alumno_redirigir();
    }

    /**
     * Método por defecto de /alumno
     */
    public function index() {
        // Redireccionar al dashboard
        $this->redirect('alumno/dashboard');
    }

    /**
     * Renderiza la vista principal del alumno
     * Endpoint: GET /alumno/dashboard
     */
    public function dashboard() {
        // Aquí usarías el modelo para obtener datos reales
        $datosParaVista = [
            'titulo' => 'Dashboard del Alumno',
            'nombre' => 'Juan Pérez',
            'estado_servicio' => 'En progreso',
            'horas_acumuladas' => 120
        ];

        // Cargar la vista pasándole los datos
        $this->view('alumno/dashboard', $datosParaVista);
    }

    /**
     * Endpoint para peticiones AJAX de subida de reportes
     * Endpoint: POST /alumno/subir_reporte
     */
    public function subir_reporte() {
        // Verificar que solo se pueda acceder mediante POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Método HTTP no permitido.'], 405);
        }

        // Lógica simulada de validación de archivo y BD
        // $archivo = $_FILES['reporte'] ?? null;
        // $modelo = new AlumnoModel();
        // $modelo->guardarReporte($archivo);
        
        $exito = true; // Simulación

        if ($exito) {
            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Reporte subido correctamente.',
                'fecha_recepcion' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Hubo un problema al subir el reporte.'
            ], 400);
        }
    }
}
