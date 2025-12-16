<?php
/**
 * Bootstrap para PHPUnit Tests
 * Este archivo se ejecuta antes de todas las pruebas
 */

// Definir el directorio raíz del proyecto
define('ROOT_PATH', dirname(__DIR__));

// Incluir el autoloader de Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Incluir archivos de configuración necesarios
require_once ROOT_PATH . '/config/config.php';

// Configurar zona horaria
date_default_timezone_set('America/Lima');

// Deshabilitar la salida de errores durante las pruebas
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Función helper para incluir modelos y controladores
function loadClass($className) {
    $paths = [
        ROOT_PATH . '/app/models/' . $className . '.php',
        ROOT_PATH . '/app/controllers/' . $className . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    }
    
    return false;
}

// Función para limpiar la base de datos de pruebas (si existe)
function cleanTestDatabase() {
    // Implementar si se usa base de datos de pruebas
}

// Función para crear datos de prueba
function seedTestData() {
    // Implementar si se necesitan datos de prueba
}

echo "Bootstrap de pruebas cargado correctamente.\n";
