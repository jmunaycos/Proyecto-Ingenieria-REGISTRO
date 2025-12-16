<?php
require_once __DIR__ . '/../../config/config.php';

/**
 * Modelo Auth - Maneja la autenticación y sesiones
 */
class Auth {
    
    /**
     * Inicia sesión si no está activa
     */
    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        } elseif (session_status() === PHP_SESSION_NONE && headers_sent()) {
            // En modo testing, usar sesión global simulada
            if (defined('TEST_MODE') && TEST_MODE === true) {
                return; // La sesión ya está inicializada en el bootstrap
            }
        }
    }
    
    /**
     * Inicia sesión de usuario
     * @param array $userData
     */
    public static function login($userData) {
        self::initSession();
        $_SESSION['user_id'] = $userData['id'] ?? null;
        $_SESSION['username'] = $userData['username'] ?? '';
        $_SESSION['role'] = $userData['role'] ?? ROLE_USER;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }
    
    /**
     * Cierra sesión de usuario
     */
    public static function logout() {
        self::initSession();
        session_unset();
        session_destroy();
    }
    
    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function check() {
        self::initSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Obtiene el usuario actual
     * @return array|null
     */
    public static function user() {
        self::initSession();
        
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        
        return null;
    }
    
    /**
     * Verifica si el usuario tiene un rol específico
     * @param string $rol
     * @return bool
     */
    public static function hasRole($role) {
        self::initSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
    
    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public static function isAdmin() {
        return self::hasRole(ROLE_ADMIN);
    }
    
    /**
     * Verifica si el usuario es un usuario normal (no admin)
     * @return bool
     */
    public static function isUser() {
        // Acepta tanto 'user' como 'usuario' para compatibilidad con pruebas
        return self::hasRole('usuario') || self::hasRole('user') || self::hasRole(ROLE_USER);
    }
    
    /**
     * Requiere autenticación - redirige si no está autenticado
     * @param string $redirectTo
     */
    public static function requireAuth($redirectTo = '/login') {
        if (!self::check()) {
            header('Location: ' . BASE_URL . $redirectTo);
            exit;
        }
    }
    
    /**
     * Requiere rol específico - redirige si no lo tiene
     * @param string $rol
     * @param string $redirectTo
     */
    public static function requireRole($role, $redirectTo = '/dashboard') {
        self::requireAuth();
        
        if (!self::hasRole($role)) {
            header('Location: ' . BASE_URL . $redirectTo);
            exit;
        }
    }
    
    /**
     * Requiere rol de administrador
     * @param string $redirectTo
     */
    public static function requireAdmin($redirectTo = '/dashboard') {
        self::requireRole(ROLE_ADMIN, $redirectTo);
    }
    
    /**
     * Verifica si el usuario ya está autenticado - redirige si lo está
     * @param string $redirectTo
     */
    public static function guest($redirectTo = '/dashboard') {
        if (self::check()) {
            header('Location: ' . BASE_URL . $redirectTo);
            exit;
        }
    }
    
    /**
     * Obtiene el ID del usuario actual
     * @return int|null
     */
    public static function id() {
        self::initSession();
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtiene el username del usuario actual
     * @return string|null
     */
    public static function username() {
        self::initSession();
        return $_SESSION['username'] ?? null;
    }
    
    /**
     * Obtiene el rol del usuario actual
     * @return string|null
     */
    public static function role() {
        self::initSession();
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Regenera el ID de sesión para prevenir session fixation
     */
    public static function regenerate() {
        self::initSession();
        session_regenerate_id(true);
    }
}
