<?php
/**
 * Procesar el login
 */

require_once 'auth.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php?error=" . urlencode("Método no permitido"));
    exit();
}

// Obtener credenciales
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validar campos
if (empty($username) || empty($password)) {
    header("Location: login.php?error=" . urlencode("Por favor complete todos los campos"));
    exit();
}

// Intentar login
$result = login($username, $password);

if ($result['success']) {
    // Login exitoso, redirigir al dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Login fallido, volver al formulario con error
    header("Location: login.php?error=" . urlencode($result['message']));
    exit();
}
