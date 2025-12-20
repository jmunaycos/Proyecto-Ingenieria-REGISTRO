<?php
/**
 * Pruebas para UserController
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/controllers/Controller.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/models/User.php';

class UserControllerTest extends TestCase
{
    private $controller;
    
    protected function setUp(): void
    {
        $this->controller = new UserController();
    }
    
    protected function tearDown(): void
    {
        $this->controller = null;
    }
    
    /**
     * @test
     * Verifica que UserController se puede instanciar
     */
    public function testUserControllerInstantiation()
    {
        $this->assertInstanceOf(UserController::class, $this->controller);
    }
    
    /**
     * @test
     * Verifica que UserController extiende Controller
     */
    public function testUserControllerExtendsController()
    {
        $this->assertInstanceOf(Controller::class, $this->controller);
    }
    
    /**
     * @test
     * Verifica que tiene método index
     */
    public function testIndexMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'index'));
    }
    
    /**
     * @test
     * Verifica que tiene método create
     */
    public function testCreateMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'create'));
    }
    
    /**
     * @test
     * Verifica que tiene método store
     */
    public function testStoreMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'store'));
    }
    
    /**
     * @test
     * Verifica que tiene método edit
     */
    public function testEditMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'edit'));
    }
    
    /**
     * @test
     * Verifica que tiene método update
     */
    public function testUpdateMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'update'));
    }
    
    /**
     * @test
     * Verifica que tiene método delete
     */
    public function testDeleteMethodExists()
    {
        $this->assertTrue(method_exists($this->controller, 'delete'));
    }
    
    /**
     * @test
     * Verifica estructura completa del controlador
     */
    public function testControllerHasAllCRUDMethods()
    {
        $reflection = new ReflectionClass($this->controller);
        
        $expectedMethods = ['index', 'create', 'store', 'edit', 'update', 'delete'];
        
        foreach ($expectedMethods as $method) {
            $this->assertTrue($reflection->hasMethod($method), 
                "El controlador debe tener el método {$method}");
        }
    }
    
    /**
     * @test
     * Verifica que los métodos CRUD son públicos
     */
    public function testCRUDMethodsArePublic()
    {
        $reflection = new ReflectionClass($this->controller);
        $methods = ['index', 'create', 'store', 'edit', 'update', 'delete'];
        
        foreach ($methods as $methodName) {
            if ($reflection->hasMethod($methodName)) {
                $method = $reflection->getMethod($methodName);
                $this->assertTrue($method->isPublic(), 
                    "El método {$methodName} debe ser público");
            }
        }
    }
}
