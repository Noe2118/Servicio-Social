<?php

class Router {
    protected $controller = 'LoginController'; // Controlador por defecto
    protected $method = 'index'; // Método por defecto
    protected $params = [];

    public function __construct() {
        // Inicialización si es necesaria
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            // Eliminar el slash final, sanitizar la URL y separarla en partes
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    public function dispatch() {
        $url = $this->parseUrl();

        // 1. Determinar el Controlador
        if (!empty($url) && isset($url[0])) {
            // Convención: ucfirst(nombre) + 'Controller'
            $requestedController = ucfirst($url[0]) . 'Controller';
            if (file_exists(APP_PATH . '/controllers/' . $requestedController . '.php')) {
                $this->controller = $requestedController;
                unset($url[0]);
            } else {
                $this->sendNotFound("El controlador especificado no existe.");
                return;
            }
        }

        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        
        // Instanciar el controlador dinámicamente
        $this->controller = new $this->controller;

        // 2. Determinar el Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->sendNotFound("El método especificado no existe en el controlador.");
                return;
            }
        }

        // 3. Determinar los Parámetros
        // Lo que sobra en $url son los parámetros pasados por URL (/controlador/metodo/param1/param2)
        $this->params = $url ? array_values($url) : [];

        // Ejecutar el método dentro de la instancia del controlador pasando los parámetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function sendNotFound($message) {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>{$message}</p>";
        exit;
    }
}
