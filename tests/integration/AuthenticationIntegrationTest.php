<?php
/**
 * Pruebas de integración para el flujo de autenticación
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/Auth.php';
require_once __DIR__ . '/../../app/models/User.php';

class AuthenticationIntegrationTest extends TestCase
{
    private $userModel;
    
    protected function setUp(): void
    {
        $this->userModel = new User();
        
        // Limpiar sesión
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
        }
    }
    
    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
        }
    }
    
    /**
     * @test
     * Prueba el flujo completo de login exitoso
     */
    public function testCompleteLoginFlow()
    {
        // 1. Verificar que no hay sesión activa
        $this->assertFalse(Auth::check(), 'No debe haber sesión activa al inicio');
        
        // 2. Obtener un usuario de la base de datos
        $users = $this->userModel->getAll();
        $this->assertNotEmpty($users, 'Debe haber al menos un usuario en la base de datos');
        
        $testUser = $users[0];
        
        // 3. Simular login
        Auth::login([
            'id' => $testUser['id'],
            'username' => $testUser['username'],
            'role' => $testUser['role']
        ]);
        
        // 4. Verificar que la sesión se estableció correctamente
        $this->assertTrue(Auth::check(), 'La sesión debe estar activa después del login');
        
        // 5. Verificar datos del usuario en sesión
        $currentUser = Auth::user();
        $this->assertNotNull($currentUser);
        $this->assertEquals($testUser['id'], $currentUser['id']);
        $this->assertEquals($testUser['username'], $currentUser['username']);
        $this->assertEquals($testUser['role'], $currentUser['role']);
    }
    
    /**
     * @test
     * Prueba el flujo completo de logout
     */
    public function testCompleteLogoutFlow()
    {
        // 1. Establecer sesión
        $userData = [
            'id' => 1,
            'username' => 'testuser',
            'role' => 'user'
        ];
        
        Auth::login($userData);
        $this->assertTrue(Auth::check());
        
        // 2. Realizar logout
        Auth::logout();
        
        // 3. Verificar que la sesión fue destruida
        $this->assertFalse(Auth::check());
        $this->assertNull(Auth::user());
    }
    
    /**
     * @test
     * Prueba la verificación de roles después del login
     */
    public function testRoleVerificationAfterLogin()
    {
        // Login como admin
        Auth::login([
            'id' => 1,
            'username' => 'admin',
            'role' => 'admin'
        ]);
        
        $this->assertTrue(Auth::isAdmin());
        $this->assertFalse(Auth::isUser());
        $this->assertTrue(Auth::hasRole('admin'));
        
        Auth::logout();
        
        // Login como user
        Auth::login([
            'id' => 2,
            'username' => 'user',
            'role' => 'user'
        ]);
        
        $this->assertFalse(Auth::isAdmin());
        $this->assertTrue(Auth::isUser());
        $this->assertTrue(Auth::hasRole('user'));
    }
    
    /**
     * @test
     * Prueba la integración entre Auth y User model
     */
    public function testAuthAndUserModelIntegration()
    {
        // Obtener usuario por username
        $users = $this->userModel->getAll();
        
        if (!empty($users)) {
            $username = $users[0]['username'];
            $user = $this->userModel->getByUsername($username);
            
            $this->assertNotNull($user);
            
            // Usar datos del modelo para login
            Auth::login($user);
            
            $this->assertTrue(Auth::check());
            $this->assertEquals($user['username'], Auth::user()['username']);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos');
        }
    }
    
    /**
     * @test
     * Prueba sesión persistente
     */
    public function testSessionPersistence()
    {
        Auth::login([
            'id' => 1,
            'username' => 'testuser',
            'role' => 'admin'
        ]);
        
        // Verificar múltiples veces que la sesión persiste
        for ($i = 0; $i < 3; $i++) {
            $this->assertTrue(Auth::check());
            $user = Auth::user();
            $this->assertEquals('testuser', $user['username']);
        }
    }
}
