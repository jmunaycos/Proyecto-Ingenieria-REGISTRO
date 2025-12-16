# 📚 Sistema de Registro Universitario

## 📋 Descripción General

Sistema web desarrollado en PHP para la gestión y registro de estudiantes universitarios. Permite a los administradores gestionar estudiantes, carreras y usuarios del sistema, mientras que los usuarios regulares pueden registrar nuevos estudiantes.

**Versión:** 2.0.0  
**Desarrollado con:** PHP 8.x, MySQL, JavaScript (Vanilla), Chart.js, SweetAlert2  
**Arquitectura:** MVC (Model-View-Controller)

---

## ✨ Características Principales

### 🔐 Sistema de Autenticación
- **Login seguro** con protección CSRF
- **Control de intentos fallidos** (máximo 5 intentos)
- **Bloqueo temporal** de 5 minutos tras intentos fallidos
- **Roles diferenciados:** Administrador y Usuario
- **Regeneración de sesión** para prevenir session fixation
- **Logs de seguridad** detallados (IP, User-Agent, timestamps)

### 👥 Gestión de Usuarios
- **CRUD completo** de usuarios (Crear, Leer, Actualizar, Eliminar)
- **Validación de contraseñas fuertes:**
  - Mínimo 8 caracteres
  - Al menos 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial
- **Protección:** No se puede editar/eliminar el usuario actual
- **Logs de auditoría** para todas las operaciones

### 🎓 Gestión de Estudiantes
- **Registro de estudiantes** con datos completos
- **Búsqueda y filtrado** por DNI, nombre, carrera y ciclo
- **Exportación a CSV** de registros
- **Validación de datos únicos** (DNI y correo)
- **Envío de email de bienvenida** (PHPMailer)

### 📊 Dashboard Administrativo
- **Estadísticas en tiempo real:**
  - Total de estudiantes
  - Total de carreras
  - Total de usuarios
- **Gráficos interactivos** (Chart.js):
  - Estudiantes por carrera (gráfico de barras)
  - Estudiantes por ciclo (gráfico de líneas)
- **Tabla de últimos registros**

### 🎨 Interfaz de Usuario
- **Diseño moderno y responsive**
- **Gradientes personalizados** (morado/azul)
- **Alertas interactivas** (SweetAlert2)
- **Navegación intuitiva** con navbar dinámico
- **Modalés para edición** sin recarga de página

---

## 🏗️ Arquitectura del Sistema

### Patrón MVC

```
Proyecto-Ingenieria-REGISTRO/
│
├── app/
│   ├── controllers/          # Lógica de negocio
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   ├── UserController.php
│   │   └── CareerController.php
│   │
│   ├── models/               # Acceso a datos
│   │   ├── Auth.php
│   │   ├── User.php
│   │   ├── Student.php
│   │   └── Career.php
│   │
│   └── views/                # Presentación
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── students/
│       │   ├── index.php
│       │   └── create.php
│       ├── users/
│       │   ├── index.php
│       │   └── create.php
│       └── layouts/
│           ├── header.php
│           ├── navbar.php
│           └── footer.php
│
├── config/
│   ├── config.php            # Configuración general
│   └── database.php          # Conexión a BD (Singleton)
│
├── helpers/
│   └── functions.php         # Funciones auxiliares
│
├── public/
│   ├── index.php             # Front Controller
│   └── assets/
│       ├── css/
│       └── js/
│           ├── chart.min.js
│           └── sweetalert2.min.js
│
├── storage/
│   └── logs/
│       ├── error.log
│       ├── app.log
│       └── security.log
│
├── PHPMailer/                # Librería de emails
├── vendor/                   # Dependencias Composer
└── tests/                    # Tests unitarios
```

### Front Controller Pattern

Todo el tráfico pasa por `public/index.php` que:
1. Carga configuración
2. Inicia sesión
3. Analiza la ruta (route/action/param)
4. Instancia el controlador correspondiente
5. Ejecuta la acción solicitada

---

## 🔒 Seguridad Implementada

### Nivel de Autenticación
| Característica | Implementado | Descripción |
|----------------|--------------|-------------|
| Password Hashing | ✅ | BCrypt con `password_hash()` |
| Protección CSRF | ✅ | Tokens únicos por sesión |
| Límite de intentos | ✅ | Máximo 5 intentos fallidos |
| Bloqueo temporal | ✅ | 5 minutos tras 5 fallos |
| Session Regeneration | ✅ | Previene session fixation |
| Logs de seguridad | ✅ | Registro de todos los eventos |

### Validaciones de Entrada
- **Sanitización:** `htmlspecialchars()` en todas las salidas
- **Prepared Statements:** 100% de consultas SQL
- **Validación de tipos:** Verificación de roles, emails, DNI
- **Contraseñas fuertes:** Requisitos estrictos con validación

### Control de Acceso
- **Middleware de autenticación:** `Auth::requireAuth()`
- **Verificación de roles:** `Auth::requireAdmin()`
- **Redirección segura:** Solo a rutas permitidas
- **Protección de recursos:** Usuarios no pueden acceder a rutas admin

---

## 🌐 APIs Disponibles

### API de Carreras
**Base URL:** `/api/carreras`

#### GET /api/carreras
Obtiene todas las carreras disponibles.

**Respuesta:**
```json
[
  {
    "id": 1,
    "nombre_carrera": "Ingeniería de Sistemas",
    "facultad": "Ingeniería",
    "activo": 1
  }
]
```

#### GET /api/carreras/{id}
Obtiene una carrera específica.

**Respuesta:**
```json
{
  "id": 1,
  "nombre_carrera": "Ingeniería de Sistemas",
  "facultad": "Ingeniería",
  "activo": 1
}
```

### API de Estudiantes (Internas)

#### POST /students/store
Crea un nuevo estudiante.

**Body:**
```json
{
  "dni": "12345678",
  "nombres": "Juan",
  "apellidos": "Pérez",
  "correo": "juan@example.com",
  "carrera": "Ingeniería de Sistemas",
  "ciclo": "5",
  "comentarios": "Estudiante regular"
}
```

#### PUT /students/update/{id}
Actualiza un estudiante existente.

#### DELETE /students/delete/{id}
Elimina un estudiante.

#### GET /students/show/{id}
Obtiene los datos de un estudiante.

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "dni": "12345678",
    "nombres": "Juan",
    "apellidos": "Pérez",
    "correo": "juan@example.com",
    "carrera": "Ingeniería de Sistemas",
    "ciclo": "5"
  }
}
```

### API de Usuarios (Internas - Solo Admin)

#### GET /usuarios/show/{id}
Obtiene datos de un usuario.

#### POST /usuarios/update/{id}
Actualiza un usuario.

#### POST /usuarios/delete/{id}
Elimina un usuario.

---

## 💾 Estructura de la Base de Datos

### Tabla: `auth_users`
```sql
CREATE TABLE auth_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'usuario') DEFAULT 'usuario',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: `usuarios_universitarios`
```sql
CREATE TABLE usuarios_universitarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(8) UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    ciclo VARCHAR(10) NOT NULL,
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: `carreras`
```sql
CREATE TABLE carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    facultad VARCHAR(100) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Diagrama de Relaciones

```
┌─────────────────┐
│   auth_users    │
├─────────────────┤
│ id (PK)         │
│ username        │
│ password        │
│ role            │
│ created_at      │
└─────────────────┘

┌──────────────────────────┐
│ usuarios_universitarios  │
├──────────────────────────┤
│ id (PK)                  │
│ dni (UNIQUE)             │
│ nombres                  │
│ apellidos                │
│ correo (UNIQUE)          │
│ carrera                  │──┐
│ ciclo                    │  │
│ comentarios              │  │
│ created_at               │  │
└──────────────────────────┘  │
                              │ (relación implícita)
┌─────────────────┐           │
│    carreras     │           │
├─────────────────┤           │
│ id (PK)         │           │
│ nombre          │◄──────────┘
│ facultad        │
│ activo          │
│ created_at      │
└─────────────────┘
```

---

## 🚀 Instalación

### Requisitos
- PHP >= 8.0
- MySQL >= 5.7
- Apache/Nginx con mod_rewrite
- Composer (opcional)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd Proyecto-Ingenieria-REGISTRO
```

2. **Configurar la base de datos**
```sql
CREATE DATABASE registro_universitario;
USE registro_universitario;

-- Importar el esquema (ver estructura arriba)
-- Crear usuario admin por defecto
INSERT INTO auth_users (username, password, role) 
VALUES ('admin', '$2y$10$...hash...', 'admin');
```

3. **Configurar `config/config.php`**
```php
define('BASE_URL', 'http://localhost/Proyecto-Ingenieria-REGISTRO/public');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'tu-email@gmail.com');
define('SMTP_PASSWORD', 'tu-contraseña-app');
```

4. **Configurar `config/database.php`**
```php
private $host = "localhost";
private $username = "root";
private $password = "";
private $database = "registro_universitario";
```

5. **Configurar permisos**
```bash
chmod -R 755 storage/logs
```

6. **Acceder al sistema**
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/
```

**Credenciales por defecto:**
- Usuario: `admin`
- Contraseña: `admin123` (cambiar inmediatamente)

---

## 📝 Logs del Sistema

### `storage/logs/security.log`
Registra eventos de seguridad:
```
[2025-12-13 20:15:30] [LOGIN_SUCCESS] IP: 192.168.1.100 | User: admin | Details: Inicio de sesión exitoso | UserAgent: Mozilla/5.0...
[2025-12-13 20:20:45] [USER_CREATED] IP: 192.168.1.100 | User: admin | Details: Creó usuario: juan con rol: usuario | UserAgent: Mozilla/5.0...
[2025-12-13 20:25:10] [LOGIN_FAILED] IP: 192.168.1.101 | User: hacker | Details: Intento #3 | UserAgent: curl/7.68.0
```

### `storage/logs/error.log`
Errores de PHP y excepciones del sistema.

### `storage/logs/app.log`
Logs generales de la aplicación.

---

## 🔧 Mejoras Futuras Sugeridas

### 🎯 Alta Prioridad
1. **Implementar HTTPS obligatorio**
   - Certificado SSL/TLS
   - Redirección automática HTTP → HTTPS

2. **Autenticación de dos factores (2FA)**
   - Google Authenticator
   - Códigos por SMS/Email

3. **Recuperación de contraseña**
   - Token temporal por email
   - Validación de identidad

4. **Paginación en listados**
   - Optimizar consultas grandes
   - Mejorar rendimiento

5. **Validación de email real**
   - Verificación de correo al registrar
   - Token de activación

### 🚀 Media Prioridad
6. **API REST completa**
   - Autenticación con JWT
   - Documentación con Swagger
   - Rate limiting

7. **Dashboard avanzado**
   - Más métricas y KPIs
   - Gráficos de tendencias
   - Reportes personalizables

8. **Sistema de notificaciones**
   - Notificaciones push
   - Alertas por email
   - Centro de notificaciones

9. **Importación masiva**
   - Upload de CSV/Excel
   - Validación de datos
   - Vista previa antes de importar

10. **Gestión de carreras**
    - CRUD completo
    - Asignación de coordinadores
    - Gestión de plan de estudios

### 💡 Baja Prioridad
11. **Modo oscuro**
    - Toggle en UI
    - Persistencia de preferencia

12. **Multi-idioma (i18n)**
    - Español/Inglés
    - Detección automática

13. **Exportación a PDF**
    - Reportes individuales
    - Certificados de registro

14. **Sistema de comentarios/notas**
    - Historial de cambios
    - Observaciones por estudiante

15. **Integración con sistemas externos**
    - SUNEDU
    - Sistemas académicos
    - Plataformas de pago

### 🔐 Seguridad Adicional
16. **Web Application Firewall (WAF)**
17. **Detección de anomalías**
18. **Backup automático**
19. **Encriptación de datos sensibles**
20. **Auditoría completa de cambios**

---

## 🧪 Testing

### Ejecutar Tests
```bash
vendor/bin/phpunit tests/
```

### Cobertura Actual
- Controllers: ~60%
- Models: ~70%
- Integration: ~50%

---

## 👨‍💻 Guía de Desarrollo

### Agregar un nuevo módulo
1. Crear controlador en `app/controllers/`
2. Crear modelo en `app/models/`
3. Crear vistas en `app/views/modulo/`
4. Agregar rutas en `public/index.php`
5. Actualizar navbar si es necesario

### Convenciones de Código
- **PSR-12** para estilo de código PHP
- **CamelCase** para nombres de clases
- **snake_case** para nombres de BD
- **Comentarios** en español
- **DocBlocks** para todas las funciones

---

## 📄 Licencia

Este proyecto es de uso educativo.

---

## 👥 Contribuidores

- **Desarrollador Principal:** [Tu Nombre]
- **Universidad:** Universidad Autónoma del Perú
- **Año:** 2025

---

## 📞 Soporte

Para preguntas o reportar problemas:
- Email: soporte@universidad.edu.pe
- Issues: GitHub Issues

---

**Última actualización:** 13 de diciembre de 2025
