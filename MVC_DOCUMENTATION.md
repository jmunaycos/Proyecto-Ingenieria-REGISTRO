# Conversión a Arquitectura MVC

## Estructura del Proyecto

```
Proyecto-Ingenieria-REGISTRO/
├── config/               # Archivos de configuración
│   ├── config.php       # Configuración general y autoload
│   └── database.php     # Conexión a base de datos (Singleton)
│
├── app/                 # Lógica de la aplicación
│   ├── models/          # Modelos de datos
│   │   ├── Auth.php     # Autenticación y sesiones
│   │   ├── User.php     # Gestión de usuarios auth_users
│   │   ├── Student.php  # Gestión de estudiantes
│   │   └── Career.php   # Gestión de carreras
│   │
│   ├── controllers/     # Controladores
│   │   ├── Controller.php        # Controlador base
│   │   ├── AuthController.php    # Login/Logout
│   │   ├── StudentController.php # CRUD estudiantes
│   │   ├── DashboardController.php # Dashboard
│   │   └── CareerController.php  # API carreras
│   │
│   └── views/           # Vistas (HTML)
│       ├── layouts/     # Plantillas comunes
│       │   ├── header.php
│       │   ├── footer.php
│       │   └── navbar.php
│       ├── auth/
│       │   └── login.php
│       ├── students/
│       │   ├── index.php  # Tabla de estudiantes
│       │   └── create.php # Formulario registro
│       └── dashboard/
│           └── index.php  # Dashboard con gráficos
│
├── public/              # Directorio público (web root)
│   ├── index.php        # Front controller (routing)
│   ├── .htaccess        # Rewrite rules
│   ├── assets/
│   │   ├── css/         # Estilos
│   │   └── js/          # Scripts
│   └── api/             # APIs públicas
│
├── helpers/             # Funciones auxiliares
│   └── functions.php
│
├── storage/             # Almacenamiento
│   └── logs/            # Logs de errores
│
└── database/            # Scripts SQL
```

## Rutas de la Aplicación

```
GET  /login              -> AuthController::showLogin()
POST /login              -> AuthController::processLogin()
GET  /logout             -> AuthController::logout()

GET  /dashboard          -> DashboardController::index()
GET  /dashboard/data     -> DashboardController::getData()

GET  /students           -> StudentController::index()
GET  /registro           -> StudentController::create()
POST /students/store     -> StudentController::store()
GET  /students/show/:id  -> StudentController::show()
POST /students/update/:id -> StudentController::update()
POST /students/delete/:id -> StudentController::delete()
GET  /students/search    -> StudentController::search()
GET  /students/export-csv -> StudentController::exportCsv()

GET  /api/carreras       -> CareerController::getAll()
GET  /api/carreras/:id   -> CareerController::getById()
```

## Patrones de Diseño Implementados

### 1. **MVC (Model-View-Controller)**
- **Modelos**: Acceso a datos y lógica de negocio
- **Vistas**: Presentación (HTML/PHP)
- **Controladores**: Lógica de control y enrutamiento

### 2. **Singleton**
- `Database::getInstance()` - Una sola instancia de conexión

### 3. **Front Controller**
- `public/index.php` - Punto de entrada único

### 4. **Repository Pattern**
- Modelos encapsulan acceso a datos

## Configuración

### Base de Datos
Editar `config/database.php`:
```php
private $host = "localhost";
private $user = "root";
private $pass = "";
private $dbname = "anakond1_anakonda";
```

### URLs
Editar `config/config.php`:
```php
define('BASE_URL', 'http://localhost/Proyecto-Ingenieria-REGISTRO/public');
```

### Email (PHPMailer)
Editar `config/config.php`:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'tu-email@gmail.com');
define('SMTP_PASSWORD', 'tu-app-password');
```

## Características

### Seguridad
- ✅ Prepared statements (previene SQL Injection)
- ✅ Password hashing con bcrypt
- ✅ Sanitización de inputs
- ✅ Validación de sesiones
- ✅ Control de acceso por roles

### Funcionalidades
- ✅ Autenticación con roles (admin/usuario)
- ✅ CRUD completo de estudiantes
- ✅ Búsqueda inteligente (multi-campo + filtros)
- ✅ Dashboard con estadísticas y gráficos
- ✅ Exportación a CSV
- ✅ Envío de emails de bienvenida
- ✅ 17 carreras de Universidad Autónoma del Perú

### Usuarios por Defecto
```
Admin:   admin / admin123
Usuario: usuario / user123
```

## Cómo Probar

1. **Importar base de datos**:
   ```sql
   mysql -u root < database.sql
   mysql -u root anakond1_anakonda < carreras_autonoma.sql
   ```

2. **Configurar Apache**:
   - Habilitar mod_rewrite
   - Document root: `public/` (opcional)

3. **Acceder**:
   ```
   http://localhost/Proyecto-Ingenieria-REGISTRO/public/
   o
   http://localhost/Proyecto-Ingenieria-REGISTRO/
   ```

## Diferencias con Versión Anterior

| Aspecto | Anterior | Actual (MVC) |
|---------|----------|--------------|
| Arquitectura | Procedural | MVC Orientado a Objetos |
| Archivos | login.php, registro.php, index.php | Controladores + Vistas separadas |
| Base de datos | Conexiones múltiples | Singleton pattern |
| Routing | Archivos directos | Front Controller |
| Seguridad | Mixta | Centralizada en modelos |
| Mantenibilidad | Baja | Alta (separación de concerns) |
| Escalabilidad | Limitada | Fácil agregar features |

## Archivos Antiguos

Los archivos originales (`login.php`, `registro.php`, `index.php`, `dashboard.php`) **NO han sido eliminados** por seguridad. Se recomienda:

1. Probar la nueva estructura MVC
2. Verificar que todo funciona correctamente
3. Hacer backup de archivos antiguos
4. Eliminar archivos antiguos cuando esté listo

## Próximos Pasos

1. Probar todas las funcionalidades
2. Ajustar URLs en `config/config.php` según el entorno
3. Configurar credenciales de email
4. Optimizar consultas SQL si es necesario
5. Agregar más validaciones según requerimientos
6. Implementar logs más detallados
7. Considerar agregar tests unitarios

## Soporte

Para dudas o problemas, revisar:
- Logs en `storage/logs/error.log`
- Errores de PHP en terminal/servidor
- Validar que mod_rewrite esté activo
- Verificar permisos de escritura en `storage/logs/`
