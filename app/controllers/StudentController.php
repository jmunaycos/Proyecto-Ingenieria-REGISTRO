<?php
require_once __DIR__ . '/Controller.php';

/**
 * Controlador de estudiantes
 */
class StudentController extends Controller {
    private $studentModel;
    private $careerModel;
    
    public function __construct() {
        $this->studentModel = new Student();
        $this->careerModel = new Career();
    }
    
    /**
     * Muestra la lista de estudiantes (index.php)
     */
    public function index() {
        Auth::requireAdmin();
        
        $students = $this->studentModel->getAll();
        $carreras = $this->careerModel->getAll();
        
        $this->view('students/index', [
            'students' => $students,
            'carreras' => $carreras
        ]);
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function create() {
        Auth::requireAuth();
        
        $carrerasGrouped = $this->careerModel->getAllGroupedByFacultad();
        
        $this->view('students/create', [
            'carrerasGrouped' => $carrerasGrouped
        ]);
    }
    
    /**
     * Procesa el registro de un nuevo estudiante
     */
    public function store() {
        Auth::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($csrf_token)) {
            $this->json(['success' => false, 'message' => 'Token CSRF inválido. Recargue la página e intente de nuevo.']);
            return;
        }
        // Validar campos requeridos
        $errors = $this->validate($_POST, ['dni', 'nombres', 'apellidos', 'correo', 'carrera', 'ciclo']);
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => 'Todos los campos son requeridos', 'errors' => $errors]);
            return;
        }
        
        // Sanitizar datos
        $data = $this->sanitizeArray($_POST);
        
        // Normalizar ciclo: extraer solo el número
        $data['ciclo'] = preg_replace('/[^0-9]/', '', $data['ciclo']);
        
        // Validar DNI único
        if ($this->studentModel->existsDni($data['dni'])) {
            $this->json(['success' => false, 'message' => 'El DNI ya está registrado']);
            return;
        }
        
        // Validar correo único
        if ($this->studentModel->existsEmail($data['correo'])) {
            $this->json(['success' => false, 'message' => 'El correo ya está registrado']);
            return;
        }
        
        // Validar formato de correo
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Formato de correo inválido']);
            return;
        }
        
        // Validar DNI (8 dígitos)
        if (!preg_match('/^\d{8}$/', $data['dni'])) {
            $this->json(['success' => false, 'message' => 'El DNI debe tener 8 dígitos']);
            return;
        }
        
        // Crear estudiante
        $studentId = $this->studentModel->create($data);
        
        if ($studentId) {
            // Obtener nombre de la carrera para el correo
            $carrera = $this->careerModel->getById($data['carrera']);
            $data['nombre_carrera'] = $carrera ? $carrera['nombre'] : 'No especificada';
            
            // Enviar correo de confirmación
            $this->sendWelcomeEmail($data);
            
            $this->json([
                'success' => true,
                'message' => 'opinion del estudiante registrado con exito',
                'id' => $studentId
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Error al registrar estudiante'], 500);
        }
    }
    
    /**
     * Muestra un estudiante específico
     */
    public function show($id) {
        Auth::requireAdmin();
        
        $student = $this->studentModel->getById($id);
        
        if ($student) {
            $this->json(['success' => true, 'data' => $student]);
        } else {
            $this->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }
    }
    
    /**
     * Actualiza un estudiante
     */
    public function update($id) {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        // Validar que el estudiante existe
        $student = $this->studentModel->getById($id);
        if (!$student) {
            $this->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
            return;
        }
        
        // Validar campos requeridos
        $errors = $this->validate($_POST, ['dni', 'nombres', 'apellidos', 'correo', 'carrera', 'ciclo']);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => 'Todos los campos son requeridos', 'errors' => $errors]);
            return;
        }
        
        // Sanitizar datos
        $data = $this->sanitizeArray($_POST);
        
        // Asegurar que comentarios existe
        if (!isset($data['comentarios'])) {
            $data['comentarios'] = '';
        }
        
        // Normalizar ciclo: extraer solo el número
        $data['ciclo'] = preg_replace('/[^0-9]/', '', $data['ciclo']);
        
        // Validar DNI único (excluyendo el actual)
        if ($this->studentModel->existsDni($data['dni'], $id)) {
            $this->json(['success' => false, 'message' => 'El DNI ya está registrado por otro estudiante']);
            return;
        }
        
        // Validar correo único (excluyendo el actual)
        if ($this->studentModel->existsEmail($data['correo'], $id)) {
            $this->json(['success' => false, 'message' => 'El correo ya está registrado por otro estudiante']);
            return;
        }
        
        // Actualizar estudiante
        if ($this->studentModel->update($id, $data)) {
            $this->json(['success' => true, 'message' => 'Estudiante actualizado exitosamente']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al actualizar estudiante'], 500);
        }
    }
    
    /**
     * Elimina un estudiante
     */
    public function delete($id) {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        // Validar que el estudiante existe
        $student = $this->studentModel->getById($id);
        if (!$student) {
            $this->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
            return;
        }
        
        if ($this->studentModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'Estudiante eliminado exitosamente']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al eliminar estudiante'], 500);
        }
    }
    
    /**
     * Busca estudiantes
     */
    public function search() {
        Auth::requireAdmin();
        
        $searchText = $_GET['search'] ?? '';
        $carrera = $_GET['carrera'] ?? null;
        $ciclo = $_GET['ciclo'] ?? null;
        
        $filters = [
            'search' => $searchText,
            'carrera' => $carrera,
            'ciclo' => $ciclo
        ];
        
        $students = $this->studentModel->search($filters);
        
        $this->json(['success' => true, 'data' => $students]);
    }
    
    /**
     * Exporta estudiantes a CSV
     */
    public function exportCsv() {
        Auth::requireAdmin();
        
        $students = $this->studentModel->getAll();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="estudiantes_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
        
        // Encabezados con punto y coma
        $headers = ['ID', 'DNI', 'Nombres', 'Apellidos', 'Correo', 'Carrera', 'Ciclo', 'Comentarios', 'Fecha Registro'];
        fwrite($output, implode(';', $headers) . "\n");
        
        // Datos
        foreach ($students as $student) {
            // Normalizar ciclo
            $ciclo = preg_replace('/[^0-9]/', '', $student['ciclo']);
            
            $row = [
                $student['id'],
                $student['dni'],
                $student['nombres'],
                $student['apellidos'],
                $student['correo'],
                $student['nombre_carrera'] ?? $student['carrera'] ?? 'N/A',
                'Ciclo ' . $ciclo,
                str_replace(["\r", "\n", ";"], [' ', ' ', ','], $student['comentarios'] ?? ''),
                $student['created_at'] ?? date('Y-m-d H:i:s')
            ];
            
            fwrite($output, implode(';', $row) . "\n");
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Envía correo de bienvenida al estudiante
     */
    private function sendWelcomeEmail($studentData) {
        try {
            require_once BASE_PATH . '/PHPMailer/src/PHPMailer.php';
            require_once BASE_PATH . '/PHPMailer/src/SMTP.php';
            require_once BASE_PATH . '/PHPMailer/src/Exception.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';
            
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($studentData['correo'], $studentData['nombres'] . ' ' . $studentData['apellidos']);
            
            $mail->isHTML(true);
            $mail->Subject = 'Gracias por tu opinión';
            $mail->Body = $this->getEmailTemplate($studentData);
            
            $mail->send();
        } catch (Exception $e) {
            error_log("Error enviando correo: " . $e->getMessage());
        }
    }
    
    /**
     * Plantilla HTML del correo
     */
    private function getEmailTemplate($data) {
        $nombreCarrera = $data['nombre_carrera'] ?? 'No especificada';
        
        return "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #6a1b9a;'>¡Gracias por tu opinión!</h2>
                <p>Estimado(a) <strong>{$data['nombres']} {$data['apellidos']}</strong>,</p>
                <p>Hemos recibido tus comentarios exitosamente. Agradecemos sinceramente que te hayas tomado el tiempo para compartir tu opinión con nosotros.</p>
                <p>Tu perspectiva es muy valiosa y será considerada para mejorar la experiencia de nuestros estudiantes.</p>
                <p>Atentamente,</p>
                <p><strong>Universidad Autónoma del Perú</strong></p>
                <p style='color: #666; font-size: 12px;'>Este es un correo automático, por favor no responder.</p>
            </div>
        </body>
        </html>
        ";
    }
}
