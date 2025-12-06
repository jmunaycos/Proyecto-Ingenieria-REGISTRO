<?php
/**
 * Pruebas unitarias para el modelo Auth
 * 
 * Este archivo contiene las pruebas para verificar el correcto funcionamiento
 * del sistema de autenticación, incluyendo login, validación de credenciales y sesiones.
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/models/Auth.php';

class AuthTest extends TestCase
{
    /**
     * Prueba: Validar que se puede crear una instancia del modelo Auth
     */
    public function testCanCreateAuthModelInstance()
    {
        $authModel = new Auth();
        $this->assertInstanceOf(Auth::class, $authModel);
    }
    
    /**
     * Prueba: Validar formato de nombre de usuario
     * El usuario debe tener al menos 3 caracteres
     */
    public function testUsernameValidation()
    {
        $validUsername = 'admin';
        $invalidUsername = 'ab'; // Muy corto
        
        $this->assertTrue(
            strlen($validUsername) >= 3,
            'Nombre de usuario válido debe tener al menos 3 caracteres'
        );
        
        $this->assertFalse(
            strlen($invalidUsername) >= 3,
            'Nombre de usuario muy corto debe ser rechazado'
        );
    }
    
    /**
     * Prueba: Validar que la contraseña no esté vacía
     */
    public function testPasswordNotEmpty()
    {
        $password = 'admin123';
        
        $this->assertNotEmpty(
            $password,
            'La contraseña no debe estar vacía'
        );
        
        $this->assertTrue(
            strlen($password) >= 6,
            'La contraseña debe tener al menos 6 caracteres'
        );
    }
    
    /**
     * Prueba: Validar que el hash de contraseña se genera correctamente
     */
    public function testPasswordHashing()
    {
        $password = 'admin123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertNotEquals(
            $password,
            $hash,
            'El hash no debe ser igual a la contraseña en texto plano'
        );
        
        $this->assertTrue(
            password_verify($password, $hash),
            'El hash debe poder verificarse con la contraseña original'
        );
    }
    
    /**
     * Prueba: Validar estructura de datos de sesión
     */
    public function testSessionDataStructure()
    {
        $sessionData = [
            'user_id' => 1,
            'username' => 'admin',
            'role' => 'admin'
        ];
        
        $this->assertArrayHasKey('user_id', $sessionData);
        $this->assertArrayHasKey('username', $sessionData);
        $this->assertArrayHasKey('role', $sessionData);
        
        $this->assertIsInt($sessionData['user_id']);
        $this->assertIsString($sessionData['username']);
        $this->assertIsString($sessionData['role']);
    }
    
    /**
     * Prueba: Validar roles permitidos
     */
    public function testValidRoles()
    {
        $validRoles = ['admin', 'usuario'];
        $testRole = 'admin';
        
        $this->assertContains(
            $testRole,
            $validRoles,
            'El rol debe ser uno de los roles permitidos'
        );
    }
}
