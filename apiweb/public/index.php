<?php
/**
 * API REST para gestión de usuarios
 * Soporta operaciones CRUD: GET, POST, PUT, DELETE
 */

require_once '../config.php';

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtener el recurso solicitado
$resource = isset($_GET['resource']) ? $_GET['resource'] : '';

// Verificar que el recurso sea 'usuario'
if ($resource !== 'usuario') {
    sendResponse([
        'error' => true,
        'message' => 'Recurso no válido'
    ], 400);
}

// Obtener la conexión a la base de datos
$conn = getConnection();

// Enrutar según el método HTTP
switch ($method) {
    case 'GET':
        handleGet($conn);
        break;
    
    case 'POST':
        handlePost($conn);
        break;
    
    case 'PUT':
        handlePut($conn);
        break;
    
    case 'DELETE':
        handleDelete($conn);
        break;
    
    default:
        sendResponse([
            'error' => true,
            'message' => 'Método no permitido'
        ], 405);
}

/**
 * Maneja peticiones GET
 * Obtiene un usuario por ID o todos los usuarios
 */
function handleGet($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if ($id) {
        // Obtener un usuario específico
        $stmt = $conn->prepare("SELECT id, dni, nombres, apellidos, correo, carrera, ciclo, comentarios, created_at FROM usuarios_universitarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            sendResponse($usuario);
        } else {
            sendResponse([
                'error' => true,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        
        $stmt->close();
    } else {
        // Obtener todos los usuarios
        $result = $conn->query("SELECT id, dni, nombres, apellidos, correo, carrera, ciclo, comentarios, created_at FROM usuarios_universitarios ORDER BY id DESC");
        
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        
        sendResponse($usuarios);
    }
    
    $conn->close();
}

/**
 * Maneja peticiones POST
 * Crea un nuevo usuario
 */
function handlePost($conn) {
    // Obtener datos del cuerpo de la petición
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Si no viene como JSON, intentar con POST tradicional
    if (empty($input)) {
        $input = $_POST;
    }
    
    // Validar campos requeridos
    if (empty($input['dni']) || empty($input['nombres']) || 
        empty($input['apellidos']) || empty($input['correo']) ||
        empty($input['carrera']) || empty($input['ciclo'])) {
        sendResponse([
            'error' => true,
            'message' => 'Todos los campos obligatorios deben ser completados (DNI, nombres, apellidos, correo, carrera, ciclo)'
        ], 400);
    }
    
    $dni = $input['dni'];
    $nombres = $input['nombres'];
    $apellidos = $input['apellidos'];
    $correo = $input['correo'];
    $carrera = $input['carrera'];
    $ciclo = $input['ciclo'];
    $comentarios = isset($input['comentarios']) ? $input['comentarios'] : null;
    
    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        sendResponse([
            'error' => true,
            'message' => 'Formato de correo no válido'
        ], 400);
    }
    
    // Validar que el DNI tenga 8 dígitos
    if (!preg_match('/^\d{8}$/', $dni)) {
        sendResponse([
            'error' => true,
            'message' => 'El DNI debe tener 8 dígitos'
        ], 400);
    }
    
    // Verificar si el DNI ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios_universitarios WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        sendResponse([
            'error' => true,
            'message' => 'El DNI ya está registrado'
        ], 409);
    }
    $stmt->close();
    
    // Insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios_universitarios (dni, nombres, apellidos, correo, carrera, ciclo, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $dni, $nombres, $apellidos, $correo, $carrera, $ciclo, $comentarios);
    
    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        sendResponse([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'id' => $newId
        ], 201);
    } else {
        sendResponse([
            'error' => true,
            'message' => 'Error al registrar el usuario: ' . $stmt->error
        ], 500);
    }
    
    $stmt->close();
    $conn->close();
}

/**
 * Maneja peticiones PUT
 * Actualiza un usuario existente
 */
function handlePut($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if (!$id) {
        sendResponse([
            'error' => true,
            'message' => 'ID de usuario requerido'
        ], 400);
    }
    
    // Obtener datos del cuerpo de la petición
    parse_str(file_get_contents('php://input'), $input);
    
    // Si no viene como form-encoded, intentar con JSON
    if (empty($input)) {
        $input = json_decode(file_get_contents('php://input'), true);
    }
    
    // Validar campos requeridos
    if (empty($input['dni']) || empty($input['nombres']) || 
        empty($input['apellidos']) || empty($input['correo']) ||
        empty($input['carrera']) || empty($input['ciclo'])) {
        sendResponse([
            'error' => true,
            'message' => 'Todos los campos obligatorios deben ser completados'
        ], 400);
    }
    
    $dni = $input['dni'];
    $nombres = $input['nombres'];
    $apellidos = $input['apellidos'];
    $correo = $input['correo'];
    $carrera = $input['carrera'];
    $ciclo = $input['ciclo'];
    $comentarios = isset($input['comentarios']) ? $input['comentarios'] : null;
    
    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        sendResponse([
            'error' => true,
            'message' => 'Formato de correo no válido'
        ], 400);
    }
    
    // Validar que el DNI tenga 8 dígitos
    if (!preg_match('/^\d{8}$/', $dni)) {
        sendResponse([
            'error' => true,
            'message' => 'El DNI debe tener 8 dígitos'
        ], 400);
    }
    
    // Verificar que el usuario existe
    $stmt = $conn->prepare("SELECT id FROM usuarios_universitarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendResponse([
            'error' => true,
            'message' => 'Usuario no encontrado'
        ], 404);
    }
    $stmt->close();
    
    // Verificar si el DNI ya existe en otro usuario
    $stmt = $conn->prepare("SELECT id FROM usuarios_universitarios WHERE dni = ? AND id != ?");
    $stmt->bind_param("si", $dni, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        sendResponse([
            'error' => true,
            'message' => 'El DNI ya está registrado en otro usuario'
        ], 409);
    }
    $stmt->close();
    
    // Actualizar el usuario
    $stmt = $conn->prepare("UPDATE usuarios_universitarios SET dni = ?, nombres = ?, apellidos = ?, correo = ?, carrera = ?, ciclo = ?, comentarios = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $dni, $nombres, $apellidos, $correo, $carrera, $ciclo, $comentarios, $id);
    
    if ($stmt->execute()) {
        sendResponse([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente'
        ]);
    } else {
        sendResponse([
            'error' => true,
            'message' => 'Error al actualizar el usuario: ' . $stmt->error
        ], 500);
    }
    
    $stmt->close();
    $conn->close();
}

/**
 * Maneja peticiones DELETE
 * Elimina un usuario
 */
function handleDelete($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if (!$id) {
        sendResponse([
            'error' => true,
            'message' => 'ID de usuario requerido'
        ], 400);
    }
    
    // Verificar que el usuario existe
    $stmt = $conn->prepare("SELECT id FROM usuarios_universitarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendResponse([
            'error' => true,
            'message' => 'Usuario no encontrado'
        ], 404);
    }
    $stmt->close();
    
    // Eliminar el usuario
    $stmt = $conn->prepare("DELETE FROM usuarios_universitarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        sendResponse([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    } else {
        sendResponse([
            'error' => true,
            'message' => 'Error al eliminar el usuario: ' . $stmt->error
        ], 500);
    }
    
    $stmt->close();
    $conn->close();
}
