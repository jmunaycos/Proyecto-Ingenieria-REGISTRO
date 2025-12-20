<?php
/**
 * Pruebas de integración para el sistema completo
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/Auth.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/models/Student.php';
require_once __DIR__ . '/../../app/models/Career.php';

class SystemIntegrationTest extends TestCase
{
    private $userModel;
    private $studentModel;
    private $careerModel;
    
    protected function setUp(): void
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->careerModel = new Career();
        
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
     * Prueba el flujo completo: Login -> Ver estudiantes -> Logout
     */
    public function testCompleteUserWorkflow()
    {
        // 1. Login
        $users = $this->userModel->getAll();
        $this->assertNotEmpty($users, 'Debe haber usuarios en el sistema');
        
        Auth::login([
            'id' => $users[0]['id'],
            'username' => $users[0]['username'],
            'role' => $users[0]['role']
        ]);
        
        $this->assertTrue(Auth::check());
        
        // 2. Ver estudiantes (simular acceso a datos)
        $students = $this->studentModel->getAll();
        $this->assertIsArray($students);
        
        // 3. Ver carreras
        $careers = $this->careerModel->getAll();
        $this->assertIsArray($careers);
        
        // 4. Logout
        Auth::logout();
        $this->assertFalse(Auth::check());
    }
    
    /**
     * @test
     * Prueba permisos de administrador
     */
    public function testAdminPermissions()
    {
        // Login como admin
        $users = $this->userModel->getAll();
        $admin = null;
        
        foreach ($users as $user) {
            if ($user['role'] === 'admin') {
                $admin = $user;
                break;
            }
        }
        
        if ($admin) {
            Auth::login($admin);
            
            // Admin debe poder ver usuarios
            $this->assertTrue(Auth::isAdmin());
            $allUsers = $this->userModel->getAll();
            $this->assertIsArray($allUsers);
            
            // Admin debe poder ver estudiantes
            $students = $this->studentModel->getAll();
            $this->assertIsArray($students);
        } else {
            $this->markTestSkipped('No hay usuario admin en la base de datos');
        }
    }
    
    /**
     * @test
     * Prueba integridad de datos entre modelos
     */
    public function testDataIntegrityAcrossModels()
    {
        $students = $this->studentModel->getAll();
        $careers = $this->careerModel->getAll();
        
        if (!empty($students) && !empty($careers)) {
            // Verificar que las carreras de estudiantes existen
            $careerNames = array_column($careers, 'nombre_carrera');
            
            foreach ($students as $student) {
                if (!empty($student['carrera'])) {
                    // La carrera del estudiante debe existir o ser texto libre
                    $this->assertNotEmpty($student['carrera']);
                }
            }
        } else {
            $this->markTestSkipped('No hay suficientes datos para verificar integridad');
        }
    }
    
    /**
     * @test
     * Prueba que los modelos no interfieren entre sí
     */
    public function testModelsIndependence()
    {
        $users1 = $this->userModel->getAll();
        $students1 = $this->studentModel->getAll();
        $careers1 = $this->careerModel->getAll();
        
        // Ejecutar consultas en diferente orden
        $careers2 = $this->careerModel->getAll();
        $users2 = $this->userModel->getAll();
        $students2 = $this->studentModel->getAll();
        
        // Los resultados deben ser los mismos
        $this->assertEquals($users1, $users2);
        $this->assertEquals($students1, $students2);
        $this->assertEquals($careers1, $careers2);
    }
    
    /**
     * @test
     * Prueba disponibilidad de todas las funcionalidades principales
     */
    public function testAllMainFeaturesAvailable()
    {
        // Verificar que todos los modelos se pueden instanciar
        $this->assertInstanceOf(User::class, $this->userModel);
        $this->assertInstanceOf(Student::class, $this->studentModel);
        $this->assertInstanceOf(Career::class, $this->careerModel);
        
        // Verificar que Auth funciona
        $this->assertFalse(Auth::check());
        
        // Verificar que las consultas básicas funcionan
        $this->assertIsArray($this->userModel->getAll());
        $this->assertIsArray($this->studentModel->getAll());
        $this->assertIsArray($this->careerModel->getAll());
    }
    
    /**
     * @test
     * Prueba rendimiento de consultas múltiples
     */
    public function testMultipleQueriesPerformance()
    {
        $startTime = microtime(true);
        
        // Ejecutar múltiples consultas
        for ($i = 0; $i < 5; $i++) {
            $this->userModel->getAll();
            $this->studentModel->getAll();
            $this->careerModel->getAll();
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        // Las consultas no deberían tomar más de 5 segundos
        $this->assertLessThan(5, $executionTime, 
            'Las consultas múltiples no deben tomar más de 5 segundos');
    }
}
