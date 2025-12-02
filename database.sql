-- Script de creación de la base de datos y tablas para el proyecto Anakonda
-- Ejecutar este script en phpMyAdmin o MySQL Workbench

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS anakonda DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE anakonda;

-- ================================================
-- TABLA: usuarios_universitarios (registros de estudiantes)
-- ================================================
CREATE TABLE IF NOT EXISTS usuarios_universitarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(8) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    ciclo VARCHAR(20) NOT NULL,
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dni (dni),
    INDEX idx_correo (correo),
    INDEX idx_carrera (carrera),
    INDEX idx_ciclo (ciclo),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================================
-- TABLA: auth_users (usuarios del sistema - login)
-- ================================================
CREATE TABLE IF NOT EXISTS auth_users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt del password',
    email VARCHAR(100) NOT NULL,
    nombre_completo VARCHAR(200) NOT NULL,
    role ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo TINYINT(1) DEFAULT 1 COMMENT '1=activo, 0=inactivo',
    ultimo_acceso TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================================
-- INSERTAR USUARIOS DEL SISTEMA POR DEFECTO
-- ================================================

-- Usuario Admin (username: admin, password: admin123)
INSERT INTO auth_users (username, password, email, nombre_completo, role) VALUES
('admin', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'admin@anakonda.com', 'Administrador del Sistema', 'admin')
ON DUPLICATE KEY UPDATE username = username;

-- Usuario Normal (username: usuario, password: user123)
INSERT INTO auth_users (username, password, email, nombre_completo, role) VALUES
('usuario', '$2y$10$12PmZQPaWJMrLFoqKQhvO.YlOeaK0vAYQX8EvLfz0.rZGZ8jc5qJO', 'usuario@anakonda.com', 'Usuario Normal', 'usuario')
ON DUPLICATE KEY UPDATE username = username;

-- ================================================
-- DATOS DE PRUEBA (OPCIONAL)
-- Comentar esta sección si no deseas datos de prueba
-- ================================================

-- Insertar algunos usuarios universitarios de prueba
-- INSERT INTO usuarios_universitarios (dni, nombres, apellidos, correo, carrera, ciclo, comentarios) VALUES
-- ('12345678', 'Juan', 'Pérez García', 'juan.perez@universidad.edu', 'Ingeniería de Sistemas', 'Ciclo 5', 'Excelente plataforma de registro'),
-- ('87654321', 'María', 'López Martínez', 'maria.lopez@universidad.edu', 'Administración', 'Ciclo 3', 'Me gustaría más opciones de horarios'),
-- ('11223344', 'Carlos', 'González Ruiz', 'carlos.gonzalez@universidad.edu', 'Contabilidad', 'Ciclo 7', NULL),
-- ('55667788', 'Ana', 'Torres Fernández', 'ana.torres@universidad.edu', 'Marketing', 'Ciclo 2', 'Todo funciona bien'),
-- ('99887766', 'Luis', 'Ramírez Sánchez', 'luis.ramirez@universidad.edu', 'Ingeniería Industrial', 'Ciclo 4', 'Gracias por la atención');

-- ================================================
-- VERIFICAR LAS ESTRUCTURAS
-- ================================================

-- Ver estructura de la tabla usuarios_universitarios
DESCRIBE usuarios_universitarios;

-- Ver estructura de la tabla auth_users
DESCRIBE auth_users;

-- Ver todos los usuarios del sistema
SELECT id, username, email, nombre_completo, role, activo, created_at FROM auth_users;

-- Ver todos los registros de estudiantes
SELECT * FROM usuarios_universitarios;

-- ================================================
-- NOTAS IMPORTANTES
-- ================================================
-- 1. Los passwords están hasheados con bcrypt
-- 2. Credenciales por defecto:
--    Admin:   username=admin    password=admin123
--    Usuario: username=usuario  password=user123
-- 3. Cambiar los passwords después del primer login
-- 4. El role 'admin' tiene acceso completo (Dashboard, Usuarios, Registrar)
-- 5. El role 'usuario' solo tiene acceso a Registrar
-- 6. La tabla usuarios_universitarios almacena registros de estudiantes
-- 7. La tabla auth_users almacena usuarios del sistema (login)
