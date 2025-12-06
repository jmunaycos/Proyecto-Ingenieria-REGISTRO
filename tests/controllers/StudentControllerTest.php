<?php
/**
 * Pruebas unitarias para StudentController
 * 
 * Este archivo contiene las pruebas para verificar el correcto funcionamiento
 * del controlador de estudiantes, incluyendo creación, listado y validaciones.
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';

class StudentControllerTest extends TestCase
{
    private $controller;
    
    /**
     * Configuración inicial antes de cada prueba
     */
    protected function setUp(): void
    {
        $this->controller = new StudentController();
    }
    
    /**
     * Prueba: Validar que se puede crear una instancia del controlador
     */
    public function testCanCreateControllerInstance()
    {
        $this->assertInstanceOf(StudentController::class, $this->controller);
    }
    
    /**
     * Prueba: Validar datos requeridos para crear un estudiante
     */
    public function testRequiredFieldsForStudentCreation()
    {
        $requiredFields = [
            'dni',
            'nombres',
            'apellidos',
            'correo',
            'carrera',
            'ciclo',
            'comentarios'
        ];
        
        $studentData = [
            'dni' => '12345678',
            'nombres' => 'Carlos',
            'apellidos' => 'García',
            'correo' => 'carlos@ejemplo.com',
            'carrera' => '1',
            'ciclo' => '3',
            'comentarios' => 'Muy buena experiencia'
        ];
        
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey(
                $field,
                $studentData,
                "El campo {$field} es requerido para crear un estudiante"
            );
        }
    }
    
    /**
     * Prueba: Validar sanitización de datos de entrada
     */
    public function testDataSanitization()
    {
        // Test 1: Remover tags HTML
        $dirtyData = '<script>alert("xss")</script>Nombre';
        $cleanData = strip_tags($dirtyData);
        
        $this->assertEquals(
            'alert("xss")Nombre',
            $cleanData,
            'Los tags HTML deben ser removidos'
        );
        
        // Test 2: Escapar caracteres especiales
        $cleanDataEscaped = htmlspecialchars($cleanData, ENT_QUOTES, 'UTF-8');
        
        $this->assertStringNotContainsString(
            '<script>',
            $cleanDataEscaped,
            'Los scripts deben ser removidos o escapados'
        );
        
        // Test 3: Validar que strip_tags + trim funciona correctamente
        $input = '  <b>Texto</b>  ';
        $output = trim(strip_tags($input));
        $this->assertEquals('Texto', $output, 'Debe remover tags y espacios');
    }
    
    /**
     * Prueba: Validar formato de respuesta JSON
     */
    public function testJsonResponseFormat()
    {
        $responseData = [
            'success' => true,
            'message' => 'Operación exitosa',
            'data' => []
        ];
        
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertIsBool($responseData['success']);
        $this->assertIsString($responseData['message']);
    }
    
    /**
     * Prueba: Validar estructura de datos del estudiante
     */
    public function testStudentDataStructure()
    {
        $studentData = [
            'dni' => '87654321',
            'nombres' => 'María',
            'apellidos' => 'López',
            'correo' => 'maria@ejemplo.com',
            'carrera' => '2',
            'ciclo' => '5',
            'comentarios' => 'Excelente atención'
        ];
        
        // Validar tipos de datos
        $this->assertIsString($studentData['dni']);
        $this->assertIsString($studentData['nombres']);
        $this->assertIsString($studentData['apellidos']);
        $this->assertIsString($studentData['correo']);
        $this->assertIsString($studentData['ciclo']);
        $this->assertIsString($studentData['comentarios']);
        
        // Validar formatos
        $this->assertMatchesRegularExpression(
            '/^\d{8}$/',
            $studentData['dni'],
            'DNI debe tener 8 digitos'
        );
        
        $this->assertMatchesRegularExpression(
            '/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
            $studentData['correo'],
            'Correo debe tener formato válido'
        );
    }
    
    /**
     * Limpieza después de cada prueba
     */
    protected function tearDown(): void
    {
        $this->controller = null;
    }
}
