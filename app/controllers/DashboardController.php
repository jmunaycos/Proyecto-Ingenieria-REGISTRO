<?php
require_once __DIR__ . '/Controller.php';

/**
 * Controlador del dashboard
 */
class DashboardController extends Controller {
    private $studentModel;
    private $userModel;
    private $careerModel;
    
    public function __construct() {
        $this->studentModel = new Student();
        $this->userModel = new User();
        $this->careerModel = new Career();
    }
    
    /**
     * Muestra el dashboard con estadísticas
     */
    public function index() {
        Auth::requireAdmin();
        
        // Obtener estadísticas
        $totalStudents = $this->studentModel->count();
        $totalUsers = $this->userModel->count();
        $totalCarreras = $this->careerModel->count();
        
        $studentsByCarrera = $this->studentModel->countByCarrera();
        $studentsByCiclo = $this->studentModel->countByCiclo();
        $recentStudents = array_slice($this->studentModel->getAll(), 0, 10);
        
        $this->view('dashboard/index', [
            'totalStudents' => $totalStudents,
            'totalUsers' => $totalUsers,
            'totalCarreras' => $totalCarreras,
            'studentsByCarrera' => $studentsByCarrera,
            'studentsByCiclo' => $studentsByCiclo,
            'recentStudents' => $recentStudents
        ]);
    }
    
    /**
     * API para obtener datos del dashboard en JSON
     */
    public function getData() {
        Auth::requireAdmin();
        
        $type = $_GET['type'] ?? 'all';
        
        $data = [];
        
        switch ($type) {
            case 'carreras':
                $data = $this->studentModel->countByCarrera();
                break;
            case 'ciclos':
                $data = $this->studentModel->countByCiclo();
                break;
            case 'stats':
                $data = [
                    'totalStudents' => $this->studentModel->count(),
                    'totalUsers' => $this->userModel->count(),
                    'totalCarreras' => $this->careerModel->count()
                ];
                break;
            default:
                $data = [
                    'totalStudents' => $this->studentModel->count(),
                    'totalUsers' => $this->userModel->count(),
                    'totalCarreras' => $this->careerModel->count(),
                    'byCarrera' => $this->studentModel->countByCarrera(),
                    'byCiclo' => $this->studentModel->countByCiclo()
                ];
        }
        
        $this->json(['success' => true, 'data' => $data]);
    }
}
