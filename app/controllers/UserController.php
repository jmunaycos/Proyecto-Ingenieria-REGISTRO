<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Auth.php';

/**
 * Controlador para gestión de usuarios (solo admin)
 */
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Muestra el formulario de registro de usuario
    public function create() {
        Auth::requireAdmin();
        $this->view('users/create');
    }

    // Procesa el registro de usuario
    public function store() {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?route=usuarios');
            return;
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'usuario';
        $csrf_token = $_POST['csrf_token'] ?? '';
        $errors = [];
        
        if (!verifyCsrfToken($csrf_token)) {
            $errors[] = 'Token CSRF inválido. Recargue la página e intente de nuevo.';
        }
        if (empty($username) || empty($password)) {
            $errors[] = 'Usuario y contraseña son requeridos';
        }
        
        // Validar fortaleza de contraseña
        $passwordValidation = validatePassword($password);
        if (!$passwordValidation['valid']) {
            $errors = array_merge($errors, $passwordValidation['errors']);
        }
        
        if (!in_array($role, ['admin', 'usuario'])) {
            $errors[] = 'Rol inválido';
        }
        if ($this->userModel->existsUsername($username)) {
            $errors[] = 'El nombre de usuario ya existe';
        }
        if ($errors) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('index.php?route=usuarios&action=create');
            return;
        }
        
        $result = $this->userModel->create([
            'username' => $username,
            'password' => $password,
            'role' => $role
        ]);
        
        if ($result) {
            logSecurityEvent('USER_CREATED', Auth::username(), "Creó usuario: $username con rol: $role");
            $_SESSION['success'] = 'Usuario creado correctamente';
        } else {
            $_SESSION['error'] = 'Error al crear usuario';
        }
        
        $this->redirect('index.php?route=usuarios');
    }

    // Lista usuarios
    public function index() {
        Auth::requireAdmin();
        $usuarios = $this->userModel->getAll();
        $this->view('users/index', ['usuarios' => $usuarios]);
    }
    
    /**
     * Muestra un usuario específico (para edición)
     */
    public function show($id) {
        Auth::requireAdmin();
        
        $user = $this->userModel->getById($id);
        
        if ($user) {
            $this->json(['success' => true, 'data' => $user]);
        } else {
            $this->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }
    }
    
    /**
     * Actualiza un usuario
     */
    public function update($id) {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        // Validar que el usuario existe
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            return;
        }
        
        // No permitir editar el propio usuario
        if ($id == Auth::id()) {
            $this->json(['success' => false, 'message' => 'No puede editar su propio usuario desde aquí']);
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es requerido';
        }
        
        if (empty($role) || !in_array($role, ['admin', 'usuario'])) {
            $errors[] = 'Rol inválido';
        }
        
        // Validar username único
        if ($this->userModel->existsUsername($username, $id)) {
            $errors[] = 'El nombre de usuario ya existe';
        }
        
        // Si se proporciona contraseña, validarla
        if (!empty($password)) {
            $passwordValidation = validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => implode(', ', $errors)]);
            return;
        }
        
        $data = [
            'username' => $username,
            'role' => $role
        ];
        
        if (!empty($password)) {
            $data['password'] = $password;
        }
        
        if ($this->userModel->update($id, $data)) {
            logSecurityEvent('USER_UPDATED', Auth::username(), "Actualizó usuario: $username");
            $this->json(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al actualizar usuario'], 500);
        }
    }
    
    /**
     * Elimina un usuario
     */
    public function delete($id) {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        // Validar que el usuario existe
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            return;
        }
        
        // No permitir eliminar el propio usuario
        if ($id == Auth::id()) {
            $this->json(['success' => false, 'message' => 'No puede eliminar su propio usuario']);
            return;
        }
        
        if ($this->userModel->delete($id)) {
            logSecurityEvent('USER_DELETED', Auth::username(), "Eliminó usuario: {$user['username']}");
            $this->json(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al eliminar usuario'], 500);
        }
    }
}
