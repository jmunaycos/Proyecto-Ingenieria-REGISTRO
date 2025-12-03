<?php
require_once __DIR__ . '/Controller.php';

/**
 * Controlador API para carreras
 */
class CareerController extends Controller {
    private $careerModel;
    
    public function __construct() {
        $this->careerModel = new Career();
    }
    
    /**
     * Obtiene todas las carreras en formato JSON
     */
    public function getAll() {
        $carreras = $this->careerModel->getAll();
        $this->json(['success' => true, 'data' => $carreras]);
    }
    
    /**
     * Obtiene carreras agrupadas por facultad
     */
    public function getAllGrouped() {
        $carreras = $this->careerModel->getAllGroupedByFacultad();
        $this->json(['success' => true, 'data' => $carreras]);
    }
    
    /**
     * Obtiene una carrera específica
     */
    public function getById($id) {
        $carrera = $this->careerModel->getById($id);
        
        if ($carrera) {
            $this->json(['success' => true, 'data' => $carrera]);
        } else {
            $this->json(['success' => false, 'message' => 'Carrera no encontrada'], 404);
        }
    }
    
    /**
     * Obtiene carreras de una facultad
     */
    public function getByFacultad($facultad) {
        $carreras = $this->careerModel->getByFacultad($facultad);
        $this->json(['success' => true, 'data' => $carreras]);
    }
}
