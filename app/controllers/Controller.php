<?php
require_once __DIR__ . '/../../config/config.php';

/**
 * Controlador base con funcionalidades comunes
 */
class Controller {
    
    /**
     * Carga una vista
     * @param string $view
     * @param array $data
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Redirige a una URL
     * @param string $url
     */
    protected function redirect($url) {
        // Si la URL ya incluye 'index.php' o es una URL completa, usarla directamente
        if (strpos($url, 'index.php') !== false || strpos($url, 'http') === 0) {
            header('Location: ' . $url);
        } else {
            header('Location: ' . BASE_URL . $url);
        }
        exit;
    }
    
    /**
     * Devuelve respuesta JSON
     * @param mixed $data
     * @param int $statusCode
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Valida datos requeridos
     * @param array $data
     * @param array $required
     * @return array Errores de validación
     */
    protected function validate($data, $required) {
        $errors = [];
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[$field] = "El campo {$field} es requerido";
            }
        }
        
        return $errors;
    }
    
    /**
     * Sanitiza datos de entrada
     * @param string $data
     * @return string
     */
    protected function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitiza un array de datos
     * @param array $data
     * @return array
     */
    protected function sanitizeArray($data) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } else {
                $sanitized[$key] = $this->sanitize($value);
            }
        }
        
        return $sanitized;
    }
}
