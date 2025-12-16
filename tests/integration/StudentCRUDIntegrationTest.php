<?php
/**
 * Pruebas de integración para el flujo CRUD de estudiantes
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/Student.php';
require_once __DIR__ . '/../../app/models/Career.php';

class StudentCRUDIntegrationTest extends TestCase
{
    private $studentModel;
    private $careerModel;
    
    protected function setUp(): void
    {
        $this->studentModel = new Student();
        $this->careerModel = new Career();
    }
    
    protected function tearDown(): void
    {
        $this->studentModel = null;
        $this->careerModel = null;
    }
    
    /**
     * @test
     * Prueba el flujo completo de consulta de estudiantes
     */
    public function testCompleteStudentReadFlow()
    {
        // 1. Obtener todos los estudiantes
        $students = $this->studentModel->getAll();
        $this->assertIsArray($students);
        
        if (!empty($students)) {
            // 2. Obtener un estudiante específico por ID
            $firstStudent = $students[0];
            $student = $this->studentModel->getById($firstStudent['id']);
            
            $this->assertNotNull($student);
            $this->assertEquals($firstStudent['id'], $student['id']);
            
            // 3. Buscar por DNI
            $studentByDNI = $this->studentModel->getByDNI($firstStudent['dni']);
            $this->assertNotNull($studentByDNI);
            $this->assertEquals($firstStudent['dni'], $studentByDNI['dni']);
            
            // 4. Buscar por email
            $studentByEmail = $this->studentModel->getByEmail($firstStudent['correo']);
            $this->assertNotNull($studentByEmail);
            $this->assertEquals($firstStudent['correo'], $studentByEmail['correo']);
        } else {
            $this->markTestSkipped('No hay estudiantes en la base de datos');
        }
    }
    
    /**
     * @test
     * Prueba la integración entre Student y Career
     */
    public function testStudentCareerIntegration()
    {
        $students = $this->studentModel->getAll();
        $careers = $this->careerModel->getAll();
        
        $this->assertIsArray($students);
        $this->assertIsArray($careers);
        
        if (!empty($students) && !empty($careers)) {
            // Verificar que cada estudiante tiene una carrera válida
            foreach ($students as $student) {
                $this->assertArrayHasKey('carrera', $student);
                $this->assertNotEmpty($student['carrera']);
            }
        } else {
            $this->markTestSkipped('No hay datos suficientes para la prueba');
        }
    }
    
    /**
     * @test
     * Prueba la búsqueda de estudiantes
     */
    public function testStudentSearchFunctionality()
    {
        $students = $this->studentModel->getAll();
        
        if (!empty($students)) {
            // Buscar por nombre
            $searchTerm = substr($students[0]['nombres'], 0, 3);
            $results = $this->studentModel->search($searchTerm);
            
            $this->assertIsArray($results);
        } else {
            $this->markTestSkipped('No hay estudiantes para buscar');
        }
    }
    
    /**
     * @test
     * Prueba obtener estadísticas de estudiantes
     */
    public function testStudentStatistics()
    {
        $stats = $this->studentModel->getStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertGreaterThanOrEqual(0, $stats['total']);
    }
    
    /**
     * @test
     * Prueba validación de datos de estudiante
     */
    public function testStudentDataValidation()
    {
        $students = $this->studentModel->getAll();
        
        if (!empty($students)) {
            foreach ($students as $student) {
                // Validar DNI (8 dígitos)
                if (!empty($student['dni'])) {
                    $this->assertMatchesRegularExpression('/^\d{8}$/', $student['dni'],
                        'El DNI debe tener 8 dígitos');
                }
                
                // Validar email
                if (!empty($student['correo'])) {
                    $this->assertMatchesRegularExpression(
                        '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        $student['correo'],
                        'El email debe tener un formato válido'
                    );
                }
                
                // Validar que nombres y apellidos no estén vacíos
                $this->assertNotEmpty($student['nombres'], 'Los nombres no deben estar vacíos');
                $this->assertNotEmpty($student['apellidos'], 'Los apellidos no deben estar vacíos');
            }
        } else {
            $this->markTestSkipped('No hay estudiantes para validar');
        }
    }
    
    /**
     * @test
     * Prueba la consistencia de datos entre consultas
     */
    public function testDataConsistencyBetweenQueries()
    {
        $students = $this->studentModel->getAll();
        
        if (!empty($students)) {
            $studentId = $students[0]['id'];
            
            // Obtener el mismo estudiante múltiples veces
            $student1 = $this->studentModel->getById($studentId);
            $student2 = $this->studentModel->getById($studentId);
            
            // Verificar que los datos son consistentes
            $this->assertEquals($student1, $student2);
        } else {
            $this->markTestSkipped('No hay estudiantes en la base de datos');
        }
    }
}
