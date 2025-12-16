<?php
/**
 * Pruebas unitarias para el modelo Career
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/Career.php';

class CareerTest extends TestCase
{
    private $career;
    
    protected function setUp(): void
    {
        $this->career = new Career();
    }
    
    protected function tearDown(): void
    {
        $this->career = null;
    }
    
    /**
     * @test
     * Verifica que se puede crear una instancia del modelo Career
     */
    public function testCareerInstantiation()
    {
        $this->assertInstanceOf(Career::class, $this->career);
    }
    
    /**
     * @test
     * Verifica que getAll devuelve un array
     */
    public function testGetAllReturnsArray()
    {
        $result = $this->career->getAll();
        $this->assertIsArray($result);
    }
    
    /**
     * @test
     * Verifica la estructura de datos de carreras
     */
    public function testCareerDataStructure()
    {
        $careers = $this->career->getAll();
        
        if (!empty($careers)) {
            $firstCareer = $careers[0];
            
            $expectedFields = ['id', 'nombre_carrera', 'facultad'];
            
            foreach ($expectedFields as $field) {
                $this->assertArrayHasKey($field, $firstCareer,
                    "La carrera debe tener el campo: {$field}");
            }
        } else {
            $this->markTestSkipped('No hay carreras en la base de datos');
        }
    }
    
    /**
     * @test
     * Verifica que getById funciona correctamente
     */
    public function testGetByIdReturnsCorrectType()
    {
        $result = $this->career->getById(1);
        $this->assertTrue(is_array($result) || is_null($result));
    }
    
    /**
     * @test
     * Verifica que getById con ID inválido devuelve null
     */
    public function testGetByIdWithInvalidId()
    {
        $result = $this->career->getById(999999);
        $this->assertNull($result);
    }
    
    /**
     * @test
     * Verifica que getAllGroupedByFacultad agrupa correctamente
     */
    public function testGetAllGroupedByFacultad()
    {
        $result = $this->career->getAllGroupedByFacultad();
        
        $this->assertIsArray($result);
        
        // Verificar que cada grupo tiene un array de carreras
        foreach ($result as $facultad => $carreras) {
            $this->assertIsString($facultad);
            $this->assertIsArray($carreras);
            
            if (!empty($carreras)) {
                foreach ($carreras as $carrera) {
                    $this->assertArrayHasKey('nombre_carrera', $carrera);
                    $this->assertEquals($facultad, $carrera['facultad']);
                }
            }
        }
    }
    
    /**
     * @test
     * Verifica que getByFacultad funciona correctamente
     */
    public function testGetByFacultad()
    {
        $careers = $this->career->getAll();
        
        if (!empty($careers)) {
            $facultad = $careers[0]['facultad'];
            $result = $this->career->getByFacultad($facultad);
            
            $this->assertIsArray($result);
            
            foreach ($result as $carrera) {
                $this->assertEquals($facultad, $carrera['facultad']);
            }
        } else {
            $this->markTestSkipped('No hay carreras para probar getByFacultad');
        }
    }
    
    /**
     * @test
     * Verifica que facultad no está vacía
     */
    public function testFacultadIsNotEmpty()
    {
        $careers = $this->career->getAll();
        
        if (!empty($careers)) {
            foreach ($careers as $carrera) {
                $this->assertNotEmpty($carrera['facultad'],
                    "La facultad no debe estar vacía");
            }
        } else {
            $this->markTestSkipped('No hay carreras para validar facultades');
        }
    }
    
    /**
     * @test
     * Verifica que nombre_carrera no está vacío
     */
    public function testCareerNameIsNotEmpty()
    {
        $careers = $this->career->getAll();
        
        if (!empty($careers)) {
            foreach ($careers as $carrera) {
                $this->assertNotEmpty($carrera['nombre_carrera'],
                    "El nombre de la carrera no debe estar vacío");
            }
        } else {
            $this->markTestSkipped('No hay carreras para validar nombres');
        }
    }
    
    /**
     * @test
     * Verifica que las carreras tienen IDs únicos
     */
    public function testCareerIdsAreUnique()
    {
        $careers = $this->career->getAll();
        
        if (!empty($careers)) {
            $ids = array_column($careers, 'id');
            $uniqueIds = array_unique($ids);
            
            $this->assertCount(count($ids), $uniqueIds,
                "Todos los IDs de carreras deben ser únicos");
        } else {
            $this->markTestSkipped('No hay carreras para validar IDs');
        }
    }
    
    /**
     * @test
     * Verifica métodos CRUD existen
     */
    public function testCRUDMethodsExist()
    {
        $this->assertTrue(method_exists($this->career, 'getAll'));
        $this->assertTrue(method_exists($this->career, 'getById'));
        $this->assertTrue(method_exists($this->career, 'getAllGroupedByFacultad'));
        $this->assertTrue(method_exists($this->career, 'getByFacultad'));
    }
}
