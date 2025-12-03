<?php
/**
 * Configuración general del sistema
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1); // Habilitado para desarrollo
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');

// Zona horaria
date_default_timezone_set('America/Lima');

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Constantes del sistema
define('BASE_PATH', dirname(__DIR__));
define('APP_NAME', 'Sistema de Registro Universitario');
define('APP_VERSION', '2.0.0');

// URLs (ajustar según el entorno)
define('BASE_URL', 'http://localhost/Proyecto-Ingenieria-REGISTRO/public');
define('ASSETS_URL', BASE_URL . '/assets');
define('API_URL', BASE_URL . '/api');

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_ENCRYPTION', 'ssl');
define('SMTP_USERNAME', 'lfernandez@isil.pe');
define('SMTP_PASSWORD', 'emytqsmfmcfdqkfe');
define('SMTP_FROM_EMAIL', 'lfernandez@isil.pe');
define('SMTP_FROM_NAME', 'Sistema REGISTRO');

// Roles del sistema
define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'usuario');

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/helpers/',
        BASE_PATH . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
