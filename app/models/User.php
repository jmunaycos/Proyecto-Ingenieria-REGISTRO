<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Modelo User - Maneja operaciones sobre la tabla auth_users
 */
class User {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Obtiene todos los usuarios
     * @return array
     */
    public function getAll() {
        $query = "SELECT id, username, role, created_at FROM auth_users ORDER BY id DESC";
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Obtiene un usuario por ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, role, created_at FROM auth_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Obtiene un usuario por username
     * @param string $username
     * @return array|null
     */
    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM auth_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Crea un nuevo usuario
     * @param array $data
     * @return bool|int
     */
    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt = $this->conn->prepare(
            "INSERT INTO auth_users (username, password, role) VALUES (?, ?, ?)"
        );
        
        $stmt->bind_param("sss", $data['username'], $hashedPassword, $data['role']);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Actualiza un usuario
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare(
                "UPDATE auth_users SET username = ?, password = ?, role = ? WHERE id = ?"
            );
            $stmt->bind_param("sssi", $data['username'], $hashedPassword, $data['role'], $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE auth_users SET username = ?, role = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $data['username'], $data['role'], $id);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Elimina un usuario
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM auth_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Verifica las credenciales de un usuario
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function authenticate($username, $password) {
        $user = $this->getByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Verifica si existe un username
     * @param string $username
     * @param int|null $excludeId
     * @return bool
     */
    public function existsUsername($username, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM auth_users WHERE username = ? AND id != ?");
            $stmt->bind_param("si", $username, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM auth_users WHERE username = ?");
            $stmt->bind_param("s", $username);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    /**
     * Cuenta el total de usuarios
     * @return int
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM auth_users");
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
}
