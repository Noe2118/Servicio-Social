<?php

class Controller {
    
    /**
     * Cargar una vista y pasarle datos
     * @param string $viewName Ruta de la vista (ej. 'alumno/dashboard')
     * @param array $data Arreglo asociativo con los datos para la vista
     */
    protected function view($viewName, $data = []) {
        $viewPath = APP_PATH . '/views/' . $viewName . '.php';
        
        if (file_exists($viewPath)) {
            // Extraer las variables para que estén disponibles en el scope de la vista
            extract($data);
            require_once $viewPath;
        } else {
            die("Error crítico: La vista '{$viewName}' no existe en '{$viewPath}'.");
        }
    }

    /**
     * Enviar una respuesta en formato JSON (útil para endpoints AJAX)
     * @param mixed $data Datos a serializar en JSON
     * @param int $statusCode Código de estado HTTP (ej. 200, 400, 404)
     */
    protected function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirigir a otra URL dentro del sistema
     * @param string $url Ruta relativa (ej. 'alumno/dashboard')
     */
    protected function redirect($url) {
        header('Location: /' . $url);
        exit;
    }
}
