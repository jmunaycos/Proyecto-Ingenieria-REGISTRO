<?php
/**
 * Front Controller - Punto de entrada único de la aplicación
 */

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Iniciar sesión
Auth::initSession();

// Determinar la ruta: puede venir de URL reescrita o de parámetro GET
$route = 'login';
$action = 'index';
$param = null;

if (isset($_GET['route']) && !empty($_GET['route'])) {
    // Modo con .htaccess: index.php?route=dashboard/index o index.php?route=students/store
    $routeParts = explode('/', trim($_GET['route'], '/'));
    $route = !empty($routeParts[0]) ? $routeParts[0] : 'login';
    $action = isset($routeParts[1]) && !empty($routeParts[1]) ? $routeParts[1] : 'index';
    $param = isset($routeParts[2]) ? $routeParts[2] : null;
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
            
        case 'usuarios':
            $controller = new UserController();
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
                default:
                    throw new Exception('Acción de usuario no encontrada');
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
