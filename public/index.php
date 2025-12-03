<?php
/**
 * Front Controller - Punto de entrada único de la aplicación
 */

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Iniciar sesión
Auth::initSession();

// Determinar la ruta: puede venir de URL reescrita o de parámetro GET
if (isset($_GET['route'])) {
    // Modo sin .htaccess: index.php?route=dashboard o index.php?route=students/store
    $routeParts = explode('/', $_GET['route']);
    $route = $routeParts[0];
    $action = $routeParts[1] ?? ($_GET['action'] ?? 'index');
    $param = $routeParts[2] ?? ($_GET['id'] ?? null);
} else {
    // Modo con .htaccess: URL reescrita
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Remover el path base del proyecto si existe
    $basePath = '/Proyecto-Ingenieria-REGISTRO/public';
    if (strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
    
    // Remover index.php si existe
    $requestUri = str_replace('/index.php', '', $requestUri);
    
    // Limpiar la URI
    $uri = trim($requestUri, '/');
    
    // Si está vacía, es la raíz (login)
    if (empty($uri)) {
        $route = 'login';
        $action = 'index';
        $param = null;
    } else {
        // Separar la URI en partes
        $parts = explode('/', $uri);
        $route = $parts[0] ?? 'login';
        $action = $parts[1] ?? 'index';
        $param = $parts[2] ?? null;
    }
}

// Enrutamiento
try {
    switch ($route) {
        case '':
        case 'login':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processLogin();
            } else {
                $controller->showLogin();
            }
            break;
            
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
            
        case 'dashboard':
            $controller = new DashboardController();
            if ($action === 'data') {
                $controller->getData();
            } else {
                $controller->index();
            }
            break;
            
        case 'students':
            $controller = new StudentController();
            
            switch ($action) {
                case 'index':
                case '':
                    $controller->index();
                    break;
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                    $controller->show($param);
                    break;
                case 'update':
                    $controller->update($param);
                    break;
                case 'delete':
                    $controller->delete($param);
                    break;
                case 'search':
                    $controller->search();
                    break;
                case 'export-csv':
                    $controller->exportCsv();
                    break;
                default:
                    throw new Exception('Acción no encontrada');
            }
            break;
            
        case 'registro':
            $controller = new StudentController();
            $controller->create();
            break;
            
        case 'api':
            // API Routes
            if ($action === 'carreras') {
                $controller = new CareerController();
                if ($param) {
                    $controller->getById($param);
                } else {
                    $controller->getAll();
                }
            } else {
                throw new Exception('API endpoint no encontrado');
            }
            break;
            
        default:
            throw new Exception('Ruta no encontrada');
    }
    
} catch (Exception $e) {
    // Log del error
    error_log("Error en routing: " . $e->getMessage());
    
    // Mostrar error 404
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='" . BASE_URL . "/login'>Volver al inicio</a>";
}
