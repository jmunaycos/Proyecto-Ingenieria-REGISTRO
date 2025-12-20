<?php
require_once __DIR__ . '/Controller.php';

/**
 * Controlador de autenticación
 */
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Muestra el formulario de login
     */
    public function showLogin() {
        Auth::guest();
        $this->view('auth/login');
    }
    
    /**
     * Procesa el login
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php');
            return;
        }
        // Limitar intentos de login por sesión
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        if (!isset($_SESSION['login_blocked_until'])) {
            $_SESSION['login_blocked_until'] = 0;
        }
        if ($_SESSION['login_blocked_until'] > time()) {
            $_SESSION['error'] = 'Demasiados intentos fallidos. Intente nuevamente en ' . (($_SESSION['login_blocked_until'] - time())) . ' segundos.';
            $this->redirect('index.php');
            return;
        }
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        // Validar campos y CSRF
        if (!verifyCsrfToken($csrf_token)) {
            $_SESSION['error'] = 'Token CSRF inválido. Recargue la página e intente de nuevo.';
            $this->redirect('index.php');
            return;
        }
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Usuario y contraseña son requeridos';
            $this->redirect('index.php');
            return;
        }
        
        // Autenticar usuario
        $user = $this->userModel->authenticate($username, $password);
        if ($user) {
            // Login exitoso
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_blocked_until'] = 0;
            
            // Registrar evento de seguridad
            logSecurityEvent('LOGIN_SUCCESS', $username, 'Inicio de sesión exitoso');
            
            Auth::login($user);
            Auth::regenerate();
            
            // Redirigir según rol
            if ($user['role'] === ROLE_ADMIN) {
                $this->redirect('index.php?route=dashboard');
            } else {
                $this->redirect('index.php?route=registro');
            }
        } else {
            Auth::initSession();
            $_SESSION['login_attempts']++;
            
            // Registrar intento fallido
            logSecurityEvent('LOGIN_FAILED', $username, "Intento #{$_SESSION['login_attempts']}");
            
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['login_blocked_until'] = time() + 300; // 5 minutos
                $_SESSION['error'] = 'Demasiados intentos fallidos. Espere 5 minutos antes de volver a intentar.';
                logSecurityEvent('LOGIN_BLOCKED', $username, 'Cuenta bloqueada por 5 minutos');
            } else {
                $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            }
            $this->redirect('index.php');
        }
    }
    
    /**
     * Cierra sesión
     */
    public function logout() {
        $username = Auth::username() ?? 'UNKNOWN';
        logSecurityEvent('LOGOUT', $username, 'Cierre de sesión');
        Auth::logout();
        $this->redirect('index.php');
    }
}
