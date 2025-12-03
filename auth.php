<?php
/**
 * Sistema de Autenticación y Manejo de Sesiones
 * Funciones para login, logout, verificación de sesión y roles
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Conectar a la base de datos
 */
function getDBConnection() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "anakond1_anakonda";
    
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

/**
 * Intentar login con username y password
 * @param string $username
 * @param string $password
 * @return array ['success' => bool, 'message' => string, 'user' => array]
 */
function login($username, $password) {
    $conn = getDBConnection();
    
    // Preparar consulta
    $stmt = $conn->prepare("SELECT id, username, password, email, nombre_completo, role, activo FROM auth_users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        return [
            'success' => false,
            'message' => 'Usuario no encontrado'
        ];
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar si el usuario está activo
    if ($user['activo'] != 1) {
        $conn->close();
        return [
            'success' => false,
            'message' => 'Usuario inactivo. Contacte al administrador'
        ];
    }
    
    // Verificar password
    if (!password_verify($password, $user['password'])) {
        $conn->close();
        return [
            'success' => false,
            'message' => 'Contraseña incorrecta'
        ];
    }
    
    // Actualizar último acceso
    $stmt = $conn->prepare("UPDATE auth_users SET ultimo_acceso = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // Guardar datos en sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['nombre_completo'] = $user['nombre_completo'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    
    return [
        'success' => true,
        'message' => 'Login exitoso',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'nombre_completo' => $user['nombre_completo'],
            'role' => $user['role']
        ]
    ];
}

/**
 * Cerrar sesión
 */
function logout() {
    session_unset();
    session_destroy();
    return true;
}

/**
 * Verificar si el usuario está autenticado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Obtener los datos del usuario actual
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'nombre_completo' => $_SESSION['nombre_completo'] ?? null,
        'role' => $_SESSION['role'] ?? null
    ];
}

/**
 * Verificar si el usuario tiene un rol específico
 * @param string $role 'admin' o 'usuario'
 * @return bool
 */
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Verificar si el usuario es admin
 * @return bool
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Requerir autenticación
 * Redirige al login si no está autenticado
 * @param string $redirectUrl URL a donde redirigir si no está autenticado
 */
function requireAuth($redirectUrl = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 * Requerir rol admin
 * Redirige si no es admin
 * @param string $redirectUrl URL a donde redirigir si no es admin
 */
function requireAdmin($redirectUrl = 'dashboard.php') {
    requireAuth();
    
    if (!isAdmin()) {
        header("Location: $redirectUrl?error=" . urlencode("Acceso denegado. Se requieren permisos de administrador."));
        exit();
    }
}

/**
 * Registrar nuevo usuario del sistema
 * @param array $data Datos del usuario
 * @return array ['success' => bool, 'message' => string]
 */
function registerAuthUser($data) {
    $conn = getDBConnection();
    
    // Validar campos requeridos
    $required = ['username', 'password', 'email', 'nombre_completo'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $conn->close();
            return [
                'success' => false,
                'message' => "El campo $field es obligatorio"
            ];
        }
    }
    
    // Verificar si el username ya existe
    $stmt = $conn->prepare("SELECT id FROM auth_users WHERE username = ?");
    $stmt->bind_param("s", $data['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return [
            'success' => false,
            'message' => 'El nombre de usuario ya existe'
        ];
    }
    $stmt->close();
    
    // Hash del password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insertar usuario
    $role = $data['role'] ?? 'usuario';
    $stmt = $conn->prepare("INSERT INTO auth_users (username, password, email, nombre_completo, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['username'], $passwordHash, $data['email'], $data['nombre_completo'], $role);
    
    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return [
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'id' => $newId
        ];
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return [
            'success' => false,
            'message' => 'Error al registrar usuario: ' . $error
        ];
    }
}
