<?php
/**
 * Clase Database - Patrón Singleton para conexión a BD
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "anakond1_anakonda";
    
    private function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->conn->connect_error) {
            error_log("Error de conexión a BD: " . $this->conn->connect_error);
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
