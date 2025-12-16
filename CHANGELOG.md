# CHANGELOG - Sistema de Registro Universitario

## [2.0.0] - 2025-12-13

### ✨ Nuevas Características
- Sistema completo de gestión de usuarios (CRUD)
- Validación de contraseñas fuertes con requisitos estrictos
- Dashboard con gráficos interactivos (Chart.js)
- Logs de seguridad detallados
- Interfaz moderna con diseño responsive
- Exportación de estudiantes a CSV

### 🔒 Seguridad
- Protección CSRF implementada
- Límite de intentos de login (5 intentos máximo)
- Bloqueo temporal de 5 minutos tras intentos fallidos
- Regeneración de sesión para prevenir session fixation
- Logs de auditoría para todas las operaciones críticas
- Hash de contraseñas con BCrypt
- Prepared statements en todas las consultas SQL

### 🎨 Interfaz
- Diseño moderno con gradientes personalizados
- SweetAlert2 para alertas interactivas
- Modales para edición inline
- Navbar dinámico según rol de usuario
- Badges de roles con colores distintivos

### 📊 Dashboard
- Estadísticas en tiempo real
- Gráfico de barras: Estudiantes por carrera
- Gráfico de líneas: Estudiantes por ciclo
- Tabla de últimos 10 registros

### 🐛 Correcciones
- Solucionado problema de visualización de texto en tablas
- Corregido error al editar estudiantes
- Mejorado manejo de errores en formularios
- Optimizado rendimiento de consultas

### 📝 Documentación
- README.md completo con arquitectura del sistema
- Guía de instalación detallada
- Documentación de APIs
- Diagramas de base de datos

### 🗑️ Eliminado
- Vistas obsoletas de auth (register.php, users.php)
- Carpeta coverage innecesaria
- Archivos duplicados

---

## [1.0.0] - 2025-12-01

### Características Iniciales
- Sistema básico de login
- Registro de estudiantes
- Gestión básica de usuarios
- Base de datos inicial
