<?php
/**
 * API para obtener carreras universitarias
 * Endpoint: GET /get_carreras.php
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "anakonda";

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => true, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Configurar charset UTF-8
$conn->set_charset("utf8mb4");

// Consultar carreras activas
$sql = "SELECT id, nombre, facultad FROM carreras WHERE activo = 1 ORDER BY facultad, nombre";
$result = $conn->query($sql);

$carreras = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $carreras[] = $row;
    }
}

$conn->close();

// Retornar JSON
echo json_encode([
    'success' => true,
    'carreras' => $carreras,
    'total' => count($carreras)
], JSON_UNESCAPED_UNICODE);
?>
