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
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validar campos
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Usuario y contraseña son requeridos';
            $this->redirect('index.php');
            return;
        }
        
        // Autenticar usuario
        $user = $this->userModel->authenticate($username, $password);
        
        if ($user) {
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
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            $this->redirect('index.php');
        }
    }
    
    /**
     * Cierra sesión
     */
    public function logout() {
        Auth::logout();
        $this->redirect('index.php');
    }
}
