-- ================================================
-- TABLA DE CARRERAS
-- Universidad Autónoma del Perú
-- ================================================

USE anakonda;

-- Crear tabla de carreras
CREATE TABLE IF NOT EXISTS carreras (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    facultad VARCHAR(100) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_facultad (facultad),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar carreras de la Universidad Autónoma del Perú
INSERT INTO carreras (nombre, facultad) VALUES
-- FACULTAD DE INGENIERÍA Y ARQUITECTURA
('Ingeniería de Sistemas e Informática', 'Ingeniería y Arquitectura'),
('Ingeniería Industrial', 'Ingeniería y Arquitectura'),
('Ingeniería Civil', 'Ingeniería y Arquitectura'),
('Ingeniería Ambiental', 'Ingeniería y Arquitectura'),
('Ingeniería Mecatrónica', 'Ingeniería y Arquitectura'),
('Arquitectura', 'Ingeniería y Arquitectura'),

-- FACULTAD DE CIENCIAS EMPRESARIALES
('Administración y Gestión de Empresas', 'Ciencias Empresariales'),
('Contabilidad y Finanzas', 'Ciencias Empresariales'),
('Marketing y Negocios Internacionales', 'Ciencias Empresariales'),
('Administración y Negocios Digitales', 'Ciencias Empresariales'),

-- FACULTAD DE CIENCIAS HUMANAS
('Psicología', 'Ciencias Humanas'),
('Derecho', 'Ciencias Humanas'),
('Ciencias de la Comunicación', 'Ciencias Humanas'),
('Educación', 'Ciencias Humanas'),

-- FACULTAD DE CIENCIAS DE LA SALUD
('Enfermería', 'Ciencias de la Salud'),
('Estomatología', 'Ciencias de la Salud'),
('Obstetricia', 'Ciencias de la Salud');

-- Verificar las carreras insertadas
SELECT 
    f.facultad,
    GROUP_CONCAT(c.nombre ORDER BY c.nombre SEPARATOR '\n') as carreras,
    COUNT(*) as total
FROM carreras c
JOIN (SELECT DISTINCT facultad FROM carreras) f ON c.facultad = f.facultad
WHERE c.activo = 1
GROUP BY f.facultad
ORDER BY f.facultad;

-- Ver todas las carreras
SELECT id, nombre, facultad FROM carreras WHERE activo = 1 ORDER BY facultad, nombre;

-- ================================================
-- NOTAS
-- ================================================
-- 1. Total de carreras: 17
-- 2. Agrupadas por 4 facultades
-- 3. Campo 'activo' permite desactivar carreras sin eliminarlas
-- 4. Las carreras se pueden gestionar desde la base de datos
