<?php
/**
 * Exportar usuarios a CSV
 * Genera un archivo CSV con todos los usuarios de la base de datos
 */

require_once '../config.php';

// Obtener la conexión a la base de datos
$conn = getConnection();

// Obtener todos los usuarios
$result = $conn->query("SELECT id, dni, nombres, apellidos, correo, created_at FROM usuarios ORDER BY id DESC");

// Verificar si hay usuarios
if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'error' => true,
        'message' => 'No hay usuarios para exportar'
    ]);
    exit();
}

// Configurar headers para descarga de CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="usuarios_' . date('Ymd_His') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Crear el archivo CSV en la salida
$output = fopen('php://output', 'w');

// Escribir el BOM UTF-8 para compatibilidad con Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Escribir la cabecera del CSV
fputcsv($output, ['ID', 'DNI', 'Nombres', 'Apellidos', 'Correo', 'Fecha de Registro']);

// Escribir los datos
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['dni'],
        $row['nombres'],
        $row['apellidos'],
        $row['correo'],
        $row['created_at']
    ]);
}

// Cerrar el archivo
fclose($output);

// Cerrar la conexión
$conn->close();
