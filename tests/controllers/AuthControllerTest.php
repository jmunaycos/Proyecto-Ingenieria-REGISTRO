<?php
/**
 * Pruebas para AuthController
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/controllers/Controller.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/models/Auth.php';

class AuthControllerTest extends TestCase
{
    private $controller;
    
    protected function setUp(): void
    {
        // Limpiar sesión
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
        }
        
        $this->controller = new AuthController();
    }
    
    protected function tearDown(): void
    {
        $this->controller = null;
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
        }
    }
    
    /**
     * @test
     * Verifica que AuthController se puede instanciar
     */
    public function testAuthControllerInstantiation()
    {
        $this->assertInstanceOf(AuthController::class, $this->controller);
    }
    
    /**
     * @test
     * Verifica que AuthController extiende Controller
     */
    public function testAuthControllerExtendsController()
    {
        $this->assertInstanceOf(Controller::class, $this->controller);
    }
    
    /**
     * @test
     * Verifica que showLogin es un método público
     */
    public function testShowLoginMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'showLogin'));
    }
    
    /**
     * @test
     * Verifica que processLogin es un método público
     */
    public function testProcessLoginMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'processLogin'));
    }
    
    /**
     * @test
     * Verifica que logout es un método público
     */
    public function testLogoutMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'logout'));
    }
    
    /**
     * @test
     * Verifica que logout limpia la sesión
     */
    public function testLogoutClearsSession()
    {
        // Simular usuario logueado
        Auth::login([
            'id' => 1,
            'username' => 'testuser',
            'role' => 'user'
        ]);
        
        $this->assertTrue(Auth::check());
        
        // No podemos ejecutar logout directamente porque hace redirect
        // pero podemos verificar que Auth::logout funciona
        Auth::logout();
        $this->assertFalse(Auth::check());
    }
    
    /**
     * @test
     * Verifica estructura del controlador
     */
    public function testControllerStructure()
    {
        $reflection = new ReflectionClass($this->controller);
        
        // Verificar que tiene los métodos necesarios
        $this->assertTrue($reflection->hasMethod('showLogin'));
        $this->assertTrue($reflection->hasMethod('processLogin'));
        $this->assertTrue($reflection->hasMethod('logout'));
    }
}
