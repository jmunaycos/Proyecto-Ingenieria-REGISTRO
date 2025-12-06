# Guía de Pruebas - Sistema de Opinión Estudiante

## Introducción

Esta guía proporciona instrucciones detalladas sobre cómo ejecutar las pruebas del sistema de opinión del estudiante.

---

## Requisitos Previos

### Software Necesario
- PHP 7.4 o superior
- Composer
- PHPUnit 9.x
- Base de datos MySQL
- Servidor web (Apache/XAMPP)

### Instalación de Dependencias

```bash
# Instalar Composer (si no está instalado)
# Descargar de: https://getcomposer.org/download/

# Instalar PHPUnit
composer require --dev phpunit/phpunit ^9

# Verificar instalación
vendor/bin/phpunit --version
```

---

## Estructura de Pruebas

```
tests/
├── models/                    # Pruebas de modelos
│   ├── StudentTest.php       # Pruebas del modelo Student
│   └── AuthTest.php          # Pruebas del modelo Auth
├── controllers/               # Pruebas de controladores
│   └── StudentControllerTest.php
├── integration/               # Pruebas de integración
│   └── StudentRegistrationTest.php
└── README.md
```

---

## Ejecución de Pruebas

### Ejecutar Todas las Pruebas

```bash
# Desde la raíz del proyecto
vendor/bin/phpunit tests

# Con salida detallada
vendor/bin/phpunit tests --verbose

# Con reporte de cobertura (requiere Xdebug)
vendor/bin/phpunit tests --coverage-html coverage/
```

### Ejecutar Pruebas Específicas

```bash
# Solo pruebas de modelos
vendor/bin/phpunit tests/models

# Solo pruebas de controladores
vendor/bin/phpunit tests/controllers

# Prueba específica
vendor/bin/phpunit tests/models/StudentTest.php

# Método específico
vendor/bin/phpunit --filter testDniValidation tests/models/StudentTest.php
```

---

## Pruebas Manuales

### 1. Prueba de Registro de Opinión

**Objetivo:** Verificar que el formulario funciona correctamente.

**Pasos:**
1. Abrir navegador y acceder a: `http://localhost/Proyecto-Ingenieria-REGISTRO/public`
2. Completar el formulario con los siguientes datos:
   ```
   DNI: 12345678
   Nombres: Juan
   Apellidos: Pérez
   Correo: tu-email@ejemplo.com
   Carrera: Seleccionar cualquiera
   Ciclo: 5
   Comentarios: Esta es una prueba del sistema
   ```
3. Hacer clic en "Registrar tu opinión"
4. Verificar que aparece el modal de éxito
5. Revisar la bandeja de entrada del correo registrado

**Resultado esperado:**
- ✅ Modal muestra: "opinion del estudiante registrado con exito"
- ✅ Correo recibido con asunto "Gracias por tu opinión"
- ✅ Datos guardados en la base de datos

---

### 2. Prueba de Validación de DNI

**Objetivo:** Verificar validación del campo DNI.

**Casos a probar:**

| DNI | Debe | Resultado Esperado |
|-----|------|-------------------|
| 12345678 | ✅ Aceptar | Formulario se envía |
| 1234567 | ❌ Rechazar | Error: DNI inválido |
| abcd1234 | ❌ Rechazar | Error: DNI debe ser numérico |
| (vacío) | ❌ Rechazar | Error: Campo requerido |

---

### 3. Prueba de Validación de Correo

**Objetivo:** Verificar validación del campo correo.

**Casos a probar:**

| Correo | Debe | Resultado Esperado |
|--------|------|-------------------|
| usuario@ejemplo.com | ✅ Aceptar | Formulario se envía |
| usuario@ejemplo | ❌ Rechazar | Error: Formato inválido |
| @ejemplo.com | ❌ Rechazar | Error: Formato inválido |
| (vacío) | ❌ Rechazar | Error: Campo requerido |

---

### 4. Prueba de Seguridad (XSS)

**Objetivo:** Verificar que el sistema previene XSS.

**Pasos:**
1. En el campo "Comentarios", ingresar:
   ```html
   <script>alert('XSS Test')</script>
   ```
2. Enviar el formulario
3. Verificar en la base de datos que el script fue sanitizado
4. Verificar que no se ejecuta ningún script en el navegador

**Resultado esperado:**
- ✅ Script removido o escapado
- ✅ No se ejecuta ningún alert
- ✅ Datos guardados de forma segura

---

### 5. Prueba de Envío de Correo

**Objetivo:** Verificar que los correos se envían correctamente.

**Precondiciones:**
- Configuración SMTP correcta en `config/config.php`
- Usar un correo real para las pruebas

**Pasos:**
1. Registrar una opinión con tu correo real
2. Esperar hasta 2 minutos
3. Revisar bandeja de entrada (y spam)
4. Verificar contenido del correo

**Verificaciones:**
- ✅ Asunto: "Gracias por tu opinión"
- ✅ Contenido incluye tu nombre
- ✅ Formato HTML se ve correctamente
- ✅ Sin errores de caracteres (UTF-8)

---

## Pruebas en Producción

### Checklist Antes de Probar en Producción

- ✅ Base de datos de producción configurada
- ✅ Credenciales SMTP de producción configuradas
- ✅ `display_errors` desactivado en `config/config.php`
- ✅ BASE_URL actualizado a la URL de producción
- ✅ Archivo `.htaccess` con CSP correcto

### Prueba Básica en Producción

1. **Acceder a la URL de producción:**
   ```
   http://anakondita.com/Sistema_encuesta/public
   ```

2. **Verificar que la página carga:**
   - ✅ Sin errores 404 o 500
   - ✅ CSS se carga correctamente
   - ✅ JavaScript funciona (sin errores en consola)

3. **Realizar un registro de prueba:**
   - Usar datos reales pero identificables (ej: nombre "TEST Usuario")
   - Verificar correo se recibe
   - Verificar datos en BD

4. **Limpiar datos de prueba:**
   ```sql
   DELETE FROM students WHERE nombres LIKE '%TEST%';
   ```

---

## Resolución de Problemas Comunes

### Error: "Content Security Policy blocks eval"

**Solución:**
```apache
# En public/.htaccess, agregar:
Header set Content-Security-Policy "script-src 'self' 'unsafe-inline' 'unsafe-eval';"
```

### Error: "No se puede enviar correo"

**Verificar:**
1. Credenciales SMTP en `config/config.php`
2. Puerto y encriptación correctos
3. Firewall no bloquea puerto 587
4. Usar contraseña de aplicación de Gmail

### Error: "404 Not Found" en producción

**Verificar:**
1. Archivo `.htaccess` presente en `public/`
2. `mod_rewrite` habilitado en Apache
3. `BASE_URL` correcto en `config/config.php`
4. `$basePath` correcto en `public/index.php`

---

## Registro de Pruebas

### Plantilla de Reporte de Prueba

```markdown
## Reporte de Prueba

**Fecha:** [Fecha]
**Ejecutado por:** [Nombre]
**Entorno:** [Desarrollo/Producción]

### Pruebas Realizadas

| ID | Caso de Prueba | Resultado | Observaciones |
|----|---------------|-----------|---------------|
| CP-001 | Registro Exitoso | ✅ PASS | - |
| CP-002 | Validación DNI | ✅ PASS | - |
| CP-003 | Validación Correo | ❌ FAIL | Error en formato @ejemplo |

### Bugs Encontrados

1. **Bug #1:** [Descripción]
   - **Severidad:** Alta/Media/Baja
   - **Pasos para reproducir:** [Pasos]
   - **Resultado esperado:** [Descripción]
   - **Resultado actual:** [Descripción]

### Conclusiones

[Resumen de los resultados]
```

---

## Automatización de Pruebas

### Configurar PHPUnit.xml

Crear archivo `phpunit.xml` en la raíz:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="config/config.php"
         colors="true"
         verbose="true"
         stopOnFailure="false">
    <testsuites>
        <testsuite name="Models">
            <directory>tests/models</directory>
        </testsuite>
        <testsuite name="Controllers">
            <directory>tests/controllers</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/integration</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Ejecutar Suite Completa

```bash
vendor/bin/phpunit --configuration phpunit.xml
```

---

## Métricas de Calidad

### Objetivos de Cobertura
- **Cobertura de Código:** Mínimo 70%
- **Pruebas Críticas:** 100% pasan
- **Pruebas de Alta Prioridad:** 95% pasan

### Herramientas de Análisis
```bash
# Generar reporte de cobertura
vendor/bin/phpunit --coverage-html coverage/

# Ver reporte en navegador
# Abrir: coverage/index.html
```

---

## Contacto y Soporte

Para reportar bugs o solicitar ayuda con las pruebas, contactar a:
- **Email:** soporte@ejemplo.com
- **GitHub Issues:** [Repositorio del proyecto]

---

**Última actualización:** Diciembre 2025
