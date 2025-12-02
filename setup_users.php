<?php
/**
 * Script para crear usuarios del sistema con passwords correctos
 * Ejecutar este archivo una sola vez: http://localhost/Proyecto-Ingenieria-REGISTRO/setup_users.php
 */

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "anakonda";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Eliminar usuarios existentes para recrearlos
$conn->query("DELETE FROM auth_users");

// Crear hash para admin123
$passwordAdmin = password_hash('admin123', PASSWORD_DEFAULT);

// Crear hash para user123
$passwordUser = password_hash('user123', PASSWORD_DEFAULT);

// Insertar usuario Admin
$stmt = $conn->prepare("INSERT INTO auth_users (username, password, email, nombre_completo, role) VALUES (?, ?, ?, ?, ?)");
$username = 'admin';
$email = 'admin@anakonda.com';
$nombre = 'Administrador del Sistema';
$role = 'admin';
$stmt->bind_param("sssss", $username, $passwordAdmin, $email, $nombre, $role);
$stmt->execute();
$stmt->close();

// Insertar usuario Normal
$stmt = $conn->prepare("INSERT INTO auth_users (username, password, email, nombre_completo, role) VALUES (?, ?, ?, ?, ?)");
$username = 'usuario';
$email = 'usuario@anakonda.com';
$nombre = 'Usuario Normal';
$role = 'usuario';
$stmt->bind_param("sssss", $username, $passwordUser, $email, $nombre, $role);
$stmt->execute();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Completada</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #27ae60;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 28px;
        }
        p {
            color: #7f8c8d;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        .credentials h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .credential-item strong {
            color: #2c3e50;
        }
        code {
            background: #ecf0f1;
            padding: 2px 8px;
            border-radius: 4px;
            color: #e74c3c;
            font-family: 'Courier New', monospace;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 20px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <div class="success-icon">✅</div>
        <h1>¡Configuración Completada!</h1>
        <p>Los usuarios del sistema han sido creados exitosamente con las contraseñas correctas.</p>
        
        <div class="credentials">
            <h3>🔑 Credenciales de Acceso</h3>
            <div class="credential-item">
                <strong>👑 Administrador:</strong><br>
                Usuario: <code>admin</code><br>
                Contraseña: <code>admin123</code>
            </div>
            <div class="credential-item">
                <strong>👤 Usuario Normal:</strong><br>
                Usuario: <code>usuario</code><br>
                Contraseña: <code>user123</code>
            </div>
        </div>

        <div class="warning">
            ⚠️ <strong>Importante:</strong> Por seguridad, elimina este archivo (<code>setup_users.php</code>) después de usarlo.
        </div>

        <a href="login.php" class="btn">Ir al Login</a>
    </div>
</body>
</html>
