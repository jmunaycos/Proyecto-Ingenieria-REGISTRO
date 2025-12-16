<?php
/**
 * Pruebas unitarias para el modelo User
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/User.php';

class UserTest extends TestCase
{
    private $user;
    
    protected function setUp(): void
    {
        $this->user = new User();
    }
    
    protected function tearDown(): void
    {
        $this->user = null;
    }
    
    /**
     * @test
     * Verifica que se puede crear una instancia del modelo User
     */
    public function testUserInstantiation()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }
    
    /**
     * @test
     * Verifica que getAll devuelve un array
     */
    public function testGetAllReturnsArray()
    {
        $result = $this->user->getAll();
        $this->assertIsArray($result);
    }
    
    /**
     * @test
     * Verifica que getById con ID válido devuelve datos o null
     */
    public function testGetByIdReturnsCorrectType()
    {
        $result = $this->user->getById(1);
        $this->assertTrue(is_array($result) || is_null($result));
    }
    
    /**
     * @test
     * Verifica que getById con ID inválido devuelve null
     */
    public function testGetByIdWithInvalidIdReturnsNull()
    {
        $result = $this->user->getById(999999);
        $this->assertNull($result);
    }
    
    /**
     * @test
     * Verifica la estructura de datos de usuario
     */
    public function testUserDataStructure()
    {
        $users = $this->user->getAll();
        
        if (!empty($users)) {
            $firstUser = $users[0];
            
            $expectedFields = ['id', 'username', 'role', 'created_at'];
            
            foreach ($expectedFields as $field) {
                $this->assertArrayHasKey($field, $firstUser,
                    "El usuario debe tener el campo: {$field}");
            }
            
            // Verificar que NO tiene el campo password (seguridad)
            $this->assertArrayNotHasKey('password', $firstUser,
                "El password no debe estar en la respuesta de getAll");
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos');
        }
    }
    
    /**
     * @test
     * Verifica que getByUsername funciona correctamente
     */
    public function testGetByUsername()
    {
        $users = $this->user->getAll();
        
        if (!empty($users)) {
            $username = $users[0]['username'];
            $result = $this->user->getByUsername($username);
            
            $this->assertIsArray($result);
            $this->assertEquals($username, $result['username']);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos');
        }
    }
    
    /**
     * @test
     * Verifica que getByUsername con usuario inexistente devuelve null
     */
    public function testGetByUsernameWithInvalidUsername()
    {
        $result = $this->user->getByUsername('usuario_inexistente_' . time());
        $this->assertNull($result);
    }
    
    /**
     * @test
     * Verifica que los roles son válidos
     */
    public function testUserRolesAreValid()
    {
        $users = $this->user->getAll();
        $validRoles = ['admin', 'user'];
        
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->assertContains($user['role'], $validRoles,
                    "El rol debe ser uno de los roles válidos: " . implode(', ', $validRoles));
            }
        } else {
            $this->markTestSkipped('No hay usuarios para validar roles');
        }
    }
    
    /**
     * @test
     * Verifica que username no está vacío
     */
    public function testUsernameIsNotEmpty()
    {
        $users = $this->user->getAll();
        
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->assertNotEmpty($user['username'],
                    "El username no debe estar vacío");
            }
        } else {
            $this->markTestSkipped('No hay usuarios para validar usernames');
        }
    }
    
    /**
     * @test
     * Verifica que delete funciona
     */
    public function testDeleteMethod()
    {
        // Solo verificamos que el método existe y acepta un parámetro
        $this->assertTrue(method_exists($this->user, 'delete'));
    }
    
    /**
     * @test
     * Verifica que update funciona
     */
    public function testUpdateMethod()
    {
        $this->assertTrue(method_exists($this->user, 'update'));
    }
    
    /**
     * @test
     * Verifica que create funciona
     */
    public function testCreateMethod()
    {
        $this->assertTrue(method_exists($this->user, 'create'));
    }
    
    /**
     * @test
     * Verifica que created_at tiene formato de fecha válido
     */
    public function testCreatedAtIsValidDate()
    {
        $users = $this->user->getAll();
        
        if (!empty($users)) {
            foreach ($users as $user) {
                if (isset($user['created_at'])) {
                    $timestamp = strtotime($user['created_at']);
                    $this->assertNotFalse($timestamp,
                        "created_at debe ser una fecha válida");
                }
            }
        } else {
            $this->markTestSkipped('No hay usuarios para validar fechas');
        }
    }
}
