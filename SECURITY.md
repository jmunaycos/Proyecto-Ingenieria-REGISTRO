# 🔒 Guía de Seguridad - Sistema de Registro Universitario

## ⚠️ IMPORTANTE: Antes de Desplegar en Producción

### 1. Cambiar Contraseñas por Defecto
```sql
-- Cambiar la contraseña del admin
UPDATE auth_users 
SET password = '$2y$10$...[nuevo_hash]...' 
WHERE username = 'admin';
```

Genera un nuevo hash con:
```php
echo password_hash('TuNuevaContraseñaSegura123!', PASSWORD_BCRYPT);
```

### 2. Configurar Variables de Entorno
No uses valores hardcodeados en producción. Crea un archivo `.env`:

```env
# Base de datos
DB_HOST=localhost
DB_NAME=registro_universitario
DB_USER=db_user
DB_PASS=contraseña_segura_aqui

# SMTP
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=tu-email@gmail.com
SMTP_PASS=contraseña_app_gmail

# Sistema
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://tudominio.com
```

### 3. Habilitar HTTPS
```apache
# .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 4. Configurar Headers de Seguridad
```php
// config/config.php - Agregar:
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
```

### 5. Deshabilitar Errores en Producción
```php
// config/config.php
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}
```

### 6. Proteger Archivos Sensibles
```apache
# .htaccess en carpeta raíz
<FilesMatch "^\.">
    Require all denied
</FilesMatch>

<FilesMatch "\.(log|md|json|lock)$">
    Require all denied
</FilesMatch>
```

### 7. Configurar Permisos de Archivos
```bash
# Solo lectura para archivos PHP
find . -type f -name "*.php" -exec chmod 644 {} \;

# Solo escritura en logs
chmod -R 755 storage/logs
chown -R www-data:www-data storage/logs

# Sin ejecución en uploads (si existen)
chmod -R 644 uploads/
```

### 8. Backup Automático
```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u usuario -p registro_universitario > backup_$DATE.sql
gzip backup_$DATE.sql
# Subir a S3, Google Drive, etc.
```

### 9. Monitoreo de Logs
```bash
# Configurar cron para revisar intentos sospechosos
0 */6 * * * grep "LOGIN_FAILED" /path/to/storage/logs/security.log | mail -s "Intentos fallidos" admin@universidad.edu.pe
```

### 10. Rate Limiting
```php
// Agregar en AuthController::processLogin()
$ip = $_SERVER['REMOTE_ADDR'];
$attempts_key = "login_attempts_$ip";

if (!isset($_SESSION[$attempts_key])) {
    $_SESSION[$attempts_key] = ['count' => 0, 'time' => time()];
}

// Resetear después de 1 hora
if (time() - $_SESSION[$attempts_key]['time'] > 3600) {
    $_SESSION[$attempts_key] = ['count' => 0, 'time' => time()];
}

// Limitar a 10 intentos por IP por hora
if ($_SESSION[$attempts_key]['count'] >= 10) {
    http_response_code(429);
    die('Demasiados intentos. Intente más tarde.');
}

$_SESSION[$attempts_key]['count']++;
```

---

## 🛡️ Buenas Prácticas de Seguridad

### Contraseñas de Usuarios
✅ **HACER:**
- Requerir mínimo 8 caracteres
- Combinar mayúsculas, minúsculas, números y símbolos
- Usar `password_hash()` con BCrypt
- No almacenar contraseñas en texto plano NUNCA

❌ **NO HACER:**
- Permitir contraseñas comunes (123456, password)
- Usar MD5 o SHA1
- Enviar contraseñas por email
- Mostrar contraseñas en logs

### Sesiones
✅ **HACER:**
- Regenerar ID de sesión tras login
- Usar sesiones seguras (cookie_httponly, cookie_secure)
- Timeout de sesión (30 minutos de inactividad)
- Logout al cerrar navegador

❌ **NO HACER:**
- Almacenar información sensible en sesión sin encriptar
- Compartir IDs de sesión en URLs
- Sesiones indefinidas

### Base de Datos
✅ **HACER:**
- Usar prepared statements SIEMPRE
- Usuario de BD con privilegios mínimos necesarios
- Conexiones cifradas (SSL/TLS)
- Backups regulares automáticos

❌ **NO HACER:**
- Concatenar SQL manualmente
- Usuario root para la aplicación
- Credenciales en código fuente
- Dejar puertos de BD expuestos públicamente

### Validación de Entrada
✅ **HACER:**
- Validar en cliente Y servidor
- Sanitizar todas las entradas
- Validar tipos de datos esperados
- Listas blancas en lugar de listas negras

❌ **NO HACER:**
- Confiar solo en validación JavaScript
- Aceptar cualquier formato de entrada
- Usar `eval()` o `exec()`
- Ejecutar código de usuario

---

## 🚨 Checklist Pre-Producción

- [ ] Cambiar todas las contraseñas por defecto
- [ ] Configurar .env con credenciales reales
- [ ] Habilitar HTTPS con certificado válido
- [ ] Deshabilitar errores de PHP en pantalla
- [ ] Configurar headers de seguridad
- [ ] Proteger archivos sensibles (.env, .git)
- [ ] Configurar permisos correctos (644/755)
- [ ] Implementar backup automático
- [ ] Configurar monitoreo de logs
- [ ] Probar recuperación de desastres
- [ ] Actualizar BASE_URL en config
- [ ] Configurar SMTP con credenciales reales
- [ ] Revisar todos los logs de errores
- [ ] Eliminar usuarios/datos de prueba
- [ ] Documentar procedimientos de emergencia

---

## 📞 Contacto de Seguridad

Si encuentras una vulnerabilidad de seguridad, por favor repórtala de manera responsable a:
- **Email:** seguridad@universidad.edu.pe
- **No publicar** vulnerabilidades en GitHub issues públicos

---

**Última revisión:** 13 de diciembre de 2025
