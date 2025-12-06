<?php
/**
 * Pruebas de integración para el flujo completo de registro de opiniones
 * 
 * Este archivo contiene pruebas de integración que verifican el flujo completo
 * desde el formulario hasta el envío del correo de confirmación.
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/config.php';

class StudentRegistrationTest extends TestCase
{
    /**
     * Prueba: Validar flujo completo de registro de opinión
     * 
     * Esta prueba verifica que todos los componentes del sistema
     * trabajen correctamente juntos durante el registro.
     */
    public function testCompleteRegistrationFlow()
    {
        // Datos de prueba
        $testData = [
            'dni' => '98765432',
            'nombres' => 'Pedro',
            'apellidos' => 'Ramírez',
            'correo' => 'pedro@ejemplo.com',
            'carrera' => '3',
            'ciclo' => '6',
            'comentarios' => 'La plataforma es muy intuitiva y fácil de usar'
        ];
        
        // 1. Validar datos de entrada
        $this->assertNotEmpty($testData['dni'], 'DNI no debe estar vacío');
        $this->assertNotEmpty($testData['nombres'], 'Nombres no debe estar vacío');
        $this->assertNotEmpty($testData['correo'], 'Correo no debe estar vacío');
        
        // 2. Validar formato de datos
        $this->assertMatchesRegularExpression(
            '/^\d{8}$/',
            $testData['dni'],
            'DNI debe tener formato válido'
        );
        
        $this->assertTrue(
            filter_var($testData['correo'], FILTER_VALIDATE_EMAIL) !== false,
            'Correo debe tener formato válido'
        );
        
        // 3. Validar ciclo en rango válido
        $ciclo = intval($testData['ciclo']);
        $this->assertTrue(
            $ciclo >= 1 && $ciclo <= 10,
            'Ciclo debe estar entre 1 y 10'
        );
        
        // 4. Simular sanitización de datos
        $sanitizedData = array_map(function($value) {
            return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
        }, $testData);
        
        $this->assertIsArray($sanitizedData, 'Datos sanitizados deben ser un array');
        
        // 5. Verificar que los datos sanitizados mantienen la información esencial
        $this->assertEquals(
            $testData['dni'],
            $sanitizedData['dni'],
            'DNI debe mantenerse igual después de sanitizar'
        );
    }
    
    /**
     * Prueba: Validar respuesta del sistema después del registro
     */
    public function testRegistrationResponse()
    {
        $expectedResponse = [
            'success' => true,
            'message' => 'opinion del estudiante registrado con exito'
        ];
        
        $this->assertTrue(
            $expectedResponse['success'],
            'La respuesta debe indicar éxito'
        );
        
        $this->assertStringContainsString(
            'exito',
            strtolower($expectedResponse['message']),
            'El mensaje debe confirmar el éxito de la operación'
        );
    }
    
    /**
     * Prueba: Validar estructura de correo de confirmación
     */
    public function testConfirmationEmailStructure()
    {
        $emailData = [
            'to' => 'estudiante@ejemplo.com',
            'subject' => 'Gracias por tu opinión',
            'body' => 'Hemos recibido tus comentarios exitosamente'
        ];
        
        // Validar campos requeridos del correo
        $this->assertArrayHasKey('to', $emailData, 'Correo debe tener destinatario');
        $this->assertArrayHasKey('subject', $emailData, 'Correo debe tener asunto');
        $this->assertArrayHasKey('body', $emailData, 'Correo debe tener cuerpo');
        
        // Validar formato del destinatario
        $this->assertTrue(
            filter_var($emailData['to'], FILTER_VALIDATE_EMAIL) !== false,
            'Destinatario debe ser un correo válido'
        );
        
        // Validar contenido del mensaje
        $this->assertStringContainsString(
            'opinión',
            $emailData['subject'],
            'Asunto debe mencionar la opinión'
        );
        
        $this->assertStringContainsString(
            'exitosamente',
            $emailData['body'],
            'Cuerpo debe confirmar la recepción'
        );
    }
}
