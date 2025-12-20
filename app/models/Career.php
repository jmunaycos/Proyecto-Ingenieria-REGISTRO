<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Modelo Career - Maneja operaciones sobre la tabla carreras
 */
class Career {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Obtiene todas las carreras
     * @return array
     */
    public function getAll() {
        $query = "SELECT id, nombre as nombre_carrera, facultad, activo, created_at FROM carreras ORDER BY facultad, nombre";
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Obtiene una carrera por ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM carreras WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Obtiene carreras agrupadas por facultad
     * @return array
     */
    public function getAllGroupedByFacultad() {
        $carreras = $this->getAll();
        $grouped = [];
        
        foreach ($carreras as $carrera) {
            $facultad = $carrera['facultad'];
            if (!isset($grouped[$facultad])) {
                $grouped[$facultad] = [];
            }
            // Agregar tanto con 'nombre' como con 'nombre_carrera' para compatibilidad
            $carrera['nombre'] = $carrera['nombre_carrera'];
            $grouped[$facultad][] = $carrera;
        }
        
        return $grouped;
    }
    
    /**
     * Obtiene carreras de una facultad específica
     * @param string $facultad
     * @return array
     */
    public function getByFacultad($facultad) {
        $stmt = $this->conn->prepare("SELECT id, nombre as nombre_carrera, facultad, activo, created_at FROM carreras WHERE facultad = ? ORDER BY nombre");
        $stmt->bind_param("s", $facultad);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Obtiene todas las facultades únicas
     * @return array
     */
    public function getFacultades() {
        $query = "SELECT DISTINCT facultad FROM carreras ORDER BY facultad";
        $result = $this->conn->query($query);
        
        if ($result) {
            $facultades = [];
            while ($row = $result->fetch_assoc()) {
                $facultades[] = $row['facultad'];
            }
            return $facultades;
        }
        
        return [];
    }
    
    /**
     * Cuenta el total de carreras
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM carreras");
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
}
