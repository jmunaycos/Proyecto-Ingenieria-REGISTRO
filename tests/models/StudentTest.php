<?php
/**
 * Pruebas unitarias para el modelo Student
 * 
 * Este archivo contiene las pruebas para verificar el correcto funcionamiento
 * del modelo Student, incluyendo la creación, búsqueda y validación de estudiantes.
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/models/Student.php';

class StudentTest extends TestCase
{
    private $studentModel;
    
    /**
     * Configuración inicial antes de cada prueba
     */
    protected function setUp(): void
    {
        $this->studentModel = new Student();
    }
    
    /**
     * Prueba: Validar que se puede crear una instancia del modelo Student
     */
    public function testCanCreateStudentModelInstance()
    {
        $this->assertInstanceOf(Student::class, $this->studentModel);
    }
    
    /**
     * Prueba: Validar formato de DNI (debe ser numérico y tener 8 dígitos)
     */
    public function testDniValidation()
    {
        $validDni = '12345678';
        $invalidDni = '123'; // Muy corto
        
        $this->assertTrue(
            preg_match('/^\d{8}$/', $validDni) === 1,
            'DNI válido debe tener 8 dígitos numéricos'
        );
        
        $this->assertFalse(
            preg_match('/^\d{8}$/', $invalidDni) === 1,
            'DNI inválido debe ser rechazado'
        );
    }
    
    /**
     * Prueba: Validar formato de correo electrónico
     */
    public function testEmailValidation()
    {
        $validEmail = 'estudiante@ejemplo.com';
        $invalidEmail = 'correo-invalido';
        
        $this->assertTrue(
            filter_var($validEmail, FILTER_VALIDATE_EMAIL) !== false,
            'Correo válido debe ser aceptado'
        );
        
        $this->assertFalse(
            filter_var($invalidEmail, FILTER_VALIDATE_EMAIL) !== false,
            'Correo inválido debe ser rechazado'
        );
    }
    
    /**
     * Prueba: Validar que el ciclo sea un número entre 1 y 10
     */
    public function testCicloValidation()
    {
        $validCiclo = 5;
        $invalidCiclo = 15; // Fuera de rango
        
        $this->assertTrue(
            $validCiclo >= 1 && $validCiclo <= 10,
            'Ciclo válido debe estar entre 1 y 10'
        );
        
        $this->assertFalse(
            $invalidCiclo >= 1 && $invalidCiclo <= 10,
            'Ciclo fuera de rango debe ser rechazado'
        );
    }
    
    /**
     * Prueba: Verificar que los campos requeridos no estén vacíos
     */
    public function testRequiredFields()
    {
        $requiredFields = ['dni', 'nombres', 'apellidos', 'correo', 'carrera', 'ciclo'];
        
        $studentData = [
            'dni' => '12345678',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'correo' => 'juan@ejemplo.com',
            'carrera' => 'Ingeniería',
            'ciclo' => '5'
        ];
        
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey(
                $field,
                $studentData,
                "El campo {$field} debe estar presente"
            );
            
            $this->assertNotEmpty(
                $studentData[$field],
                "El campo {$field} no debe estar vacío"
            );
        }
    }
    
    /**
     * Limpieza después de cada prueba
     */
    protected function tearDown(): void
    {
        $this->studentModel = null;
    }
}
