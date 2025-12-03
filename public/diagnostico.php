<?php
// Diagnóstico simple
echo "=== DIAGNÓSTICO MVC ===<br><br>";

echo "1. REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "2. SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "3. PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br><br>";

// Probar carga de config
echo "4. Cargando config...<br>";
try {
    require_once __DIR__ . '/../config/config.php';
    echo "   ✅ Config cargado<br>";
    echo "   BASE_PATH: " . BASE_PATH . "<br>";
    echo "   BASE_URL: " . BASE_URL . "<br><br>";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "<br><br>";
    die();
}

// Probar carga de Database
echo "5. Cargando Database...<br>";
try {
    require_once __DIR__ . '/../config/database.php';
    echo "   ✅ Database clase cargada<br>";
    
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    if ($conn->ping()) {
        echo "   ✅ Conexión exitosa<br><br>";
    } else {
        echo "   ❌ No hay conexión<br><br>";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "<br><br>";
}

// Probar carga de modelos
echo "6. Cargando modelos...<br>";
$modelos = ['Auth', 'User', 'Student', 'Career'];
foreach ($modelos as $modelo) {
    try {
        $file = __DIR__ . "/../app/models/{$modelo}.php";
        if (file_exists($file)) {
            require_once $file;
            echo "   ✅ {$modelo}.php cargado<br>";
        } else {
            echo "   ❌ {$modelo}.php no encontrado<br>";
        }
    } catch (Exception $e) {
        echo "   ❌ Error en {$modelo}: " . $e->getMessage() . "<br>";
    }
}
echo "<br>";

// Probar carga de controladores
echo "7. Cargando controladores...<br>";
$controladores = ['Controller', 'AuthController', 'StudentController', 'DashboardController', 'CareerController'];
foreach ($controladores as $controller) {
    try {
        $file = __DIR__ . "/../app/controllers/{$controller}.php";
        if (file_exists($file)) {
            require_once $file;
            echo "   ✅ {$controller}.php cargado<br>";
        } else {
            echo "   ❌ {$controller}.php no encontrado<br>";
        }
    } catch (Exception $e) {
        echo "   ❌ Error en {$controller}: " . $e->getMessage() . "<br>";
    }
}
echo "<br>";

// Probar routing básico
echo "8. Simulando routing...<br>";
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/Proyecto-Ingenieria-REGISTRO/public';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
$uri = trim($requestUri, '/');
$parts = $uri ? explode('/', $uri) : [];
$route = $parts[0] ?? 'login';

echo "   URI limpia: '{$uri}'<br>";
echo "   Route detectada: '{$route}'<br>";
echo "   Parts: " . print_r($parts, true) . "<br><br>";

echo "9. Enlaces de prueba:<br>";
echo "   <a href='" . BASE_URL . "/'>Inicio (login)</a><br>";
echo "   <a href='" . BASE_URL . "/login'>Login explícito</a><br>";
echo "   <a href='" . BASE_URL . "/dashboard'>Dashboard</a><br>";
echo "   <a href='" . BASE_URL . "/students'>Estudiantes</a><br>";
echo "   <a href='" . BASE_URL . "/registro'>Registro</a><br>";

echo "<br>=== FIN DIAGNÓSTICO ===";
