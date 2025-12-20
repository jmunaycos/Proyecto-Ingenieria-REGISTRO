<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Modelo Student - Maneja operaciones CRUD sobre la tabla usuarios_universitarios
 */
class Student {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Obtiene todos los estudiantes
     * @return array
     */
    public function getAll() {
        $query = "SELECT u.*, u.carrera as nombre_carrera 
                  FROM usuarios_universitarios u
                  ORDER BY u.id DESC";
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Obtiene un estudiante por ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT u.*, u.carrera as nombre_carrera 
             FROM usuarios_universitarios u 
             WHERE u.id = ?"
        );
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Crea un nuevo estudiante
     * @param array $data
     * @return bool|int
     */
    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuarios_universitarios 
            (dni, nombres, apellidos, correo, carrera, ciclo, comentarios) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            "sssssss",
            $data['dni'],
            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $data['carrera'],
            $data['ciclo'],
            $data['comentarios']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Actualiza un estudiante
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE usuarios_universitarios SET 
                 dni = ?, nombres = ?, apellidos = ?, correo = ?, 
                 carrera = ?, ciclo = ?, comentarios = ? 
             WHERE id = ?"
        );
        
        $stmt->bind_param(
            "sssssssi",
            $data['dni'],
            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $data['carrera'],
            $data['ciclo'],
            $data['comentarios'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Elimina un estudiante
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios_universitarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Busca estudiantes por múltiples criterios
     * @param string $searchText
     * @param int|null $carreraId
     * @param int|null $ciclo
     * @return array
     */
    public function search($searchText = '', $carreraId = null, $ciclo = null) {
        $query = "SELECT u.*, u.carrera as nombre_carrera 
                  FROM usuarios_universitarios u 
                  WHERE 1=1";
        
        $params = [];
        $types = '';
        
        if (!empty($searchText)) {
            $query .= " AND (u.dni LIKE ? OR u.nombres LIKE ? OR u.apellidos LIKE ? OR u.correo LIKE ?)";
            $searchParam = "%{$searchText}%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
            $types .= 'ssss';
        }
        
        if ($carreraId !== null && $carreraId !== '') {
            $query .= " AND u.carrera_id = ?";
            $params[] = $carreraId;
            $types .= 'i';
        }
        
        if ($ciclo !== null && $ciclo !== '') {
            $query .= " AND u.ciclo = ?";
            $params[] = $ciclo;
            $types .= 'i';
        }
        
        $query .= " ORDER BY u.id DESC";
        
        if (!empty($params)) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($query);
        }
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Cuenta el total de estudiantes
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM usuarios_universitarios");
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    
    /**
     * Cuenta estudiantes por carrera
     * @return array
     */
    public function countByCarrera() {
        $query = "SELECT u.carrera as nombre_carrera, COUNT(*) as total 
                  FROM usuarios_universitarios u
                  GROUP BY u.carrera
                  ORDER BY total DESC";
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Cuenta estudiantes por ciclo
     * @return array
     */
    public function countByCiclo() {
        $query = "SELECT ciclo, COUNT(*) as total 
                  FROM usuarios_universitarios 
                  GROUP BY ciclo 
                  ORDER BY ciclo";
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Verifica si existe un DNI
     * @param string $dni
     * @param int|null $excludeId
     * @return bool
     */
    public function existsDni($dni, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios_universitarios WHERE dni = ? AND id != ?");
            $stmt->bind_param("si", $dni, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios_universitarios WHERE dni = ?");
            $stmt->bind_param("s", $dni);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    /**
     * Verifica si existe un correo
     * @param string $correo
     * @param int|null $excludeId
     * @return bool
     */
    public function existsEmail($correo, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios_universitarios WHERE correo = ? AND id != ?");
            $stmt->bind_param("si", $correo, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios_universitarios WHERE correo = ?");
            $stmt->bind_param("s", $correo);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    /**
     * Obtiene un estudiante por DNI
     * @param string $dni
     * @return array|null
     */
    public function getByDNI($dni) {
        $stmt = $this->conn->prepare(
            "SELECT u.*, u.carrera as nombre_carrera 
             FROM usuarios_universitarios u 
             WHERE u.dni = ?"
        );
        
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Obtiene un estudiante por Email
     * @param string $email
     * @return array|null
     */
    public function getByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT u.*, u.carrera as nombre_carrera 
             FROM usuarios_universitarios u 
             WHERE u.correo = ?"
        );
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Obtiene estudiantes por carrera
     * @param string $carrera
     * @return array
     */
    public function getByCareer($carrera) {
        $stmt = $this->conn->prepare(
            "SELECT u.*, u.carrera as nombre_carrera 
             FROM usuarios_universitarios u 
             WHERE u.carrera = ?
             ORDER BY u.id DESC"
        );
        
        $stmt->bind_param("s", $carrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Obtiene estadísticas generales de estudiantes
     * @return array
     */
    public function getStats() {
        $stats = [
            'total' => $this->count(),
            'por_carrera' => $this->countByCarrera(),
            'por_ciclo' => $this->countByCiclo()
        ];
        
        // Agregar estadísticas adicionales
        $query = "SELECT 
                    COUNT(DISTINCT dni) as total_dni_unicos,
                    COUNT(DISTINCT correo) as total_correos_unicos,
                    COUNT(DISTINCT carrera) as total_carreras
                  FROM usuarios_universitarios";
        
        $result = $this->conn->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['dni_unicos'] = (int)$row['total_dni_unicos'];
            $stats['correos_unicos'] = (int)$row['total_correos_unicos'];
            $stats['carreras_activas'] = (int)$row['total_carreras'];
        }
        
        return $stats;
    }
}
