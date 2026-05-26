<?php
// Habilitar reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constante de la raíz de la aplicación
define('APP_PATH', __DIR__);

// Definir la URL base del proyecto (subcarpeta en htdocs)
define('BASE_URL', '/SistemaServicioSocial');

// Autoloader simple para cargar clases automáticamente
spl_autoload_register(function ($class) {
    $paths = ['core', 'controllers', 'models'];
    foreach ($paths as $path) {
        $file = APP_PATH . '/' . $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Inicializar y despachar el enrutador
$router = new Router();
$router->dispatch();
