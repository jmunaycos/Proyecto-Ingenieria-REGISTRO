-- ================================================
-- SCRIPT DE MIGRACIÓN
-- De tabla 'usuarios' a 'usuarios_universitarios'
-- ================================================
-- IMPORTANTE: Ejecutar este script SOLO si ya tienes datos en la tabla 'usuarios'
-- Si es una instalación nueva, solo ejecuta database.sql

USE anakond1_anakonda;

-- Paso 1: Crear la nueva tabla usuarios_universitarios
CREATE TABLE IF NOT EXISTS usuarios_universitarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(8) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    carrera VARCHAR(100) NOT NULL DEFAULT 'Sin especificar',
    ciclo VARCHAR(20) NOT NULL DEFAULT 'Sin especificar',
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dni (dni),
    INDEX idx_correo (correo),
    INDEX idx_carrera (carrera),
    INDEX idx_ciclo (ciclo),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Paso 2: Migrar datos de 'usuarios' a 'usuarios_universitarios'
-- (Solo si la tabla usuarios existe y tiene datos)
INSERT INTO usuarios_universitarios (id, dni, nombres, apellidos, correo, carrera, ciclo, comentarios, created_at, updated_at)
SELECT 
    id, 
    dni, 
    nombres, 
    apellidos, 
    correo,
    'Sin especificar' as carrera,
    'Sin especificar' as ciclo,
    NULL as comentarios,
    created_at,
    updated_at
FROM usuarios
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios_universitarios WHERE usuarios_universitarios.dni = usuarios.dni
);

-- Paso 3: Verificar la migración
SELECT COUNT(*) as total_usuarios_antiguos FROM usuarios;
SELECT COUNT(*) as total_usuarios_universitarios FROM usuarios_universitarios;

-- Paso 4 (OPCIONAL): Respaldar y eliminar tabla antigua
-- DESCOMENTAR SOLO SI ESTÁS SEGURO DE QUE LA MIGRACIÓN FUE EXITOSA
-- RENAME TABLE usuarios TO usuarios_backup_old;

-- O eliminar completamente (¡PRECAUCIÓN!)
-- DROP TABLE IF EXISTS usuarios;

-- ================================================
-- VERIFICACIÓN
-- ================================================
SELECT 'Migración completada. Verifica los datos:' as mensaje;
SELECT * FROM usuarios_universitarios LIMIT 10;

-- ================================================
-- NOTAS
-- ================================================
-- 1. Los datos migrados tendrán carrera y ciclo como 'Sin especificar'
-- 2. Puedes actualizarlos manualmente después
-- 3. La tabla antigua se renombra a 'usuarios_backup_old' por seguridad
-- 4. Una vez verificado, puedes eliminar la tabla de respaldo
