# Guía Completa: Instalación y Ejecución de Pruebas Unitarias/Automáticas

## Universidad Autónoma del Perú - Sistema de Opinión del Estudiante

**Fecha:** Diciembre 2025  
**Versión:** 1.0

---

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Instalación de Herramientas](#instalación-de-herramientas)
3. [Configuración del Proyecto](#configuración-del-proyecto)
4. [Ejecución de Pruebas](#ejecución-de-pruebas)
5. [Interpretación de Resultados](#interpretación-de-resultados)
6. [Solución de Problemas](#solución-de-problemas)

---

## 🔧 Requisitos Previos

### Software Necesario

Antes de comenzar, asegúrate de tener instalado:

#### 1. PHP 7.4 o superior

**Verificar instalación:**
```powershell
php --version
```

**Salida esperada:**
```
PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)
```

**Si no está instalado:**
- Descargar XAMPP desde: https://www.apachefriends.org/
- Instalar y agregar PHP al PATH del sistema

#### 2. Composer (Gestor de dependencias PHP)

**Verificar instalación:**
```powershell
composer --version
```

**Salida esperada:**
```
Composer version 2.x.x
```

**Si no está instalado:**

1. Descargar desde: https://getcomposer.org/download/
2. Ejecutar el instalador `Composer-Setup.exe`
3. Seguir el asistente de instalación
4. Reiniciar PowerShell/CMD

**Instalación manual en Windows:**
```powershell
# Descargar composer.phar
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Mover a una ubicación global
move composer.phar C:\xampp\php\composer.phar

# Crear un archivo batch (composer.bat) en C:\xampp\php\
@echo off
php "%~dp0composer.phar" %*
```

#### 3. Git (Control de versiones)

**Verificar instalación:**
```powershell
git --version
```

**Si no está instalado:**
- Descargar desde: https://git-scm.com/download/win
- Instalar con opciones por defecto

---

## 📥 Instalación de Herramientas

### Paso 1: Clonar el Repositorio

Si aún no tienes el proyecto:

```powershell
# Navegar a la carpeta deseada
cd C:\xampp\htdocs\

# Clonar el repositorio
git clone https://github.com/jmunaycos/Proyecto-Ingenieria-REGISTRO.git

# Entrar al proyecto
cd Proyecto-Ingenieria-REGISTRO
```

Si ya tienes el proyecto, asegúrate de tener la última versión:

```powershell
cd C:\xampp\htdocs\Proyecto-Ingenieria-REGISTRO

# Actualizar desde GitHub
git pull origin main
```

### Paso 2: Instalar PHPUnit (Framework de Pruebas)

PHPUnit es la herramienta principal para ejecutar pruebas unitarias en PHP.

**Opción A: Instalación a través de Composer (Recomendado)**

```powershell
# Instalar PHPUnit como dependencia de desarrollo
composer install
```

Este comando:
- Lee el archivo `composer.json`
- Descarga PHPUnit 9.6
- Instala todas las dependencias
- Crea la carpeta `vendor/`

**Verificar instalación:**
```powershell
vendor\bin\phpunit --version
```

**Salida esperada:**
```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.
```

**Opción B: Si no existe composer.json**

Si el archivo `composer.json` no existe, créalo:

```powershell
# Inicializar composer
composer init

# Cuando pregunte por dependencias, presiona Enter
# Luego instalar PHPUnit:
composer require --dev phpunit/phpunit ^9.5
```

### Paso 3: Instalar Xdebug (Para Cobertura de Código)

Xdebug es una extensión de PHP que permite generar reportes de cobertura.

#### 3.1 Verificar versión de PHP

```powershell
php -v
```

Anota:
- Versión de PHP (ej: 8.2.12)
- Arquitectura (x64 o x86)
- Thread Safety (TS o NTS)

#### 3.2 Descargar Xdebug

1. Visita: https://xdebug.org/download
2. Descarga la versión compatible:
   - **PHP 8.2 x64 Thread Safe**: `php_xdebug-3.5.0-8.2-vs16-x86_64.dll`
   - **PHP 8.1 x64 Thread Safe**: `php_xdebug-3.5.0-8.1-vs16-x86_64.dll`
   - **PHP 7.4 x64 Thread Safe**: `php_xdebug-3.1.6-7.4-vc15-x86_64.dll`

#### 3.3 Instalar Xdebug

```powershell
# 1. Copiar el archivo descargado a la carpeta de extensiones de PHP
Copy-Item "C:\Users\TU_USUARIO\Downloads\php_xdebug-3.5.0-8.2-vs16-x86_64.dll" "C:\xampp\php\ext\php_xdebug.dll"
```

#### 3.4 Configurar php.ini

```powershell
# Abrir php.ini (ubicación típica en XAMPP)
notepad C:\xampp\php\php.ini
```

**Agregar al final del archivo:**

```ini
[Xdebug]
zend_extension = "C:\xampp\php\ext\php_xdebug.dll"
xdebug.mode = coverage,debug
xdebug.start_with_request = yes
xdebug.client_port = 9003
```

**Guardar y cerrar el archivo.**

#### 3.5 Reiniciar Apache (si está corriendo)

```powershell
# Desde el Panel de Control de XAMPP o:
net stop Apache2.4
net start Apache2.4
```

#### 3.6 Verificar instalación de Xdebug

```powershell
php -v
```

**Salida esperada (debe incluir):**
```
PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)
    with Xdebug v3.5.0, Copyright (c) 2002-2024, by Derick Rethans
```

O verificar módulos:
```powershell
php -m | findstr xdebug
```

**Salida esperada:**
```
xdebug
```

---

## ⚙️ Configuración del Proyecto

### Paso 4: Verificar Estructura de Archivos

Asegúrate de que existen estos archivos clave:

```powershell
# Listar archivos importantes
Get-ChildItem -Path . -Include composer.json,phpunit.xml -Recurse
```

**Archivos necesarios:**

#### 1. `composer.json` (en la raíz del proyecto)

Si no existe, créalo con este contenido:

```json
{
    "name": "uap/sistema-opinion-estudiante",
    "description": "Sistema de registro de opiniones de estudiantes",
    "type": "project",
    "require": {
        "php": ">=7.4"
    },
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

#### 2. `phpunit.xml` (en la raíz del proyecto)

Si no existe, créalo con este contenido:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.5/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         verbose="true">
    
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

    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">app/models</directory>
            <directory suffix=".php">app/controllers</directory>
        </include>
        <exclude>
            <directory>vendor</directory>
            <directory>tests</directory>
        </exclude>
        <report>
            <html outputDirectory="coverage/html"/>
            <text outputFile="coverage/coverage.txt"/>
        </report>
    </coverage>

    <php>
        <ini name="display_errors" value="1"/>
        <ini name="error_reporting" value="-1"/>
    </php>
</phpunit>
```

### Paso 5: Configurar Base de Datos de Pruebas

**Opción A: Usar base de datos existente**

Asegúrate de que la base de datos esté configurada en `config/database.php`:

```php
private $host = "localhost";
private $user = "root";
private $pass = "";
private $dbname = "anakond1_anakonda";
```

**Opción B: Crear base de datos de pruebas (Recomendado)**

```sql
-- Conectarse a MySQL
mysql -u root -p

-- Crear base de datos de pruebas
CREATE DATABASE anakond1_anakonda_test 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Importar estructura
USE anakond1_anakonda_test;
SOURCE C:/xampp/htdocs/Proyecto-Ingenieria-REGISTRO/database.sql;

-- Verificar
SHOW TABLES;
```

---

## 🧪 Ejecución de Pruebas

### Método 1: Ejecutar TODAS las Pruebas

```powershell
# Asegúrate de estar en la raíz del proyecto
cd C:\xampp\htdocs\Proyecto-Ingenieria-REGISTRO

# Ejecutar todas las pruebas
vendor\bin\phpunit
```

**Salida esperada:**
```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.12
Configuration: C:\xampp\htdocs\Proyecto-Ingenieria-REGISTRO\phpunit.xml

...............                                                   19 / 19 (100%)

Time: 00:01.234, Memory: 10.00 MB

OK (19 tests, 72 assertions)
```

### Método 2: Ejecutar Pruebas por Grupo (Test Suite)

#### Pruebas de Modelos:
```powershell
vendor\bin\phpunit --testsuite Models
```

**Ejecuta:**
- `tests/models/StudentTest.php` (5 tests)
- `tests/models/AuthTest.php` (6 tests)

#### Pruebas de Controladores:
```powershell
vendor\bin\phpunit --testsuite Controllers
```

**Ejecuta:**
- `tests/controllers/StudentControllerTest.php` (5 tests)

#### Pruebas de Integración:
```powershell
vendor\bin\phpunit --testsuite Integration
```

**Ejecuta:**
- `tests/integration/StudentRegistrationTest.php` (3 tests)

### Método 3: Ejecutar un Archivo Específico

```powershell
# Ejecutar solo las pruebas del modelo Student
vendor\bin\phpunit tests/models/StudentTest.php

# Ejecutar solo las pruebas del controlador
vendor\bin\phpunit tests/controllers/StudentControllerTest.php
```

### Método 4: Ejecutar una Prueba Específica

```powershell
# Ejecutar solo el test testCreateStudent
vendor\bin\phpunit --filter testCreateStudent
```

### Método 5: Ejecutar con Salida Detallada

```powershell
# Modo verbose (detallado)
vendor\bin\phpunit --verbose

# Modo verbose con stack traces
vendor\bin\phpunit --verbose --debug
```

---

## 📊 Generar Reporte de Cobertura de Código

### Paso 1: Ejecutar Pruebas con Cobertura

```powershell
# Generar reporte de cobertura en HTML
vendor\bin\phpunit --coverage-html coverage/html

# Generar reporte de cobertura en texto
vendor\bin\phpunit --coverage-text

# Generar ambos reportes
vendor\bin\phpunit --coverage-html coverage/html --coverage-text
```

**Salida esperada:**
```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.12 with Xdebug 3.5.0
Configuration: C:\xampp\htdocs\Proyecto-Ingenieria-REGISTRO\phpunit.xml

...............                                                   19 / 19 (100%)

Time: 00:05.678, Memory: 12.00 MB

OK (19 tests, 72 assertions)

Generating code coverage report in HTML format ... done [00:02.345]

Code Coverage Report:
  2025-12-06 14:30:45

 Summary:
  Classes: 75.00% (6/8)
  Methods: 78.26% (18/23)
  Lines:   82.14% (115/140)
```

### Paso 2: Ver Reporte HTML

```powershell
# Abrir el reporte en el navegador
start coverage\html\index.html
```

O manualmente:
1. Navegar a `coverage/html/`
2. Abrir `index.html` con un navegador

**El reporte muestra:**
- ✅ Líneas de código ejecutadas (verde)
- ❌ Líneas de código NO ejecutadas (rojo)
- ⚠️ Líneas parcialmente ejecutadas (amarillo)
- 📊 Porcentaje de cobertura por archivo/clase/método

---

## 📖 Interpretación de Resultados

### Resultado Exitoso

```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.

...............                                                   19 / 19 (100%)

Time: 00:01.234, Memory: 10.00 MB

OK (19 tests, 72 assertions)
```

**Significado:**
- ✅ **19/19 (100%)**: Las 19 pruebas pasaron exitosamente
- ✅ **72 assertions**: Se verificaron 72 condiciones
- ✅ **OK**: Todo funciona correctamente
- ✅ Cada punto (.) representa una prueba exitosa

### Resultado con Errores

```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.

..F..E.....                                                      11 / 19 ( 57%)

Time: 00:01.456, Memory: 10.00 MB

FAILURES!
Tests: 19, Assertions: 45, Failures: 1, Errors: 1.
```

**Significado:**
- ❌ **F (Failure)**: Una prueba falló (assertion no se cumplió)
- ❌ **E (Error)**: Error de PHP (excepción, error fatal)
- ⚠️ **57%**: Solo el 57% de las pruebas pasaron

**Detalles del error:**

```
1) StudentTest::testCreateStudent
Failed asserting that false is true.

C:\xampp\htdocs\...\tests\models\StudentTest.php:45

2) StudentTest::testInvalidEmail
ErrorException: Undefined variable $student

C:\xampp\htdocs\...\tests\models\StudentTest.php:67
```

### Símbolos de Estado

| Símbolo | Significado |
|---------|-------------|
| `.` | Prueba exitosa |
| `F` | Prueba falló (assertion) |
| `E` | Error de PHP |
| `S` | Prueba omitida (skipped) |
| `I` | Prueba incompleta |
| `R` | Prueba riesgosa |

---

## 🔍 Solución de Problemas Comunes

### Problema 1: "PHPUnit no se reconoce como comando"

**Error:**
```
'vendor\bin\phpunit' no se reconoce como un comando interno o externo
```

**Solución:**
```powershell
# Verificar que composer install se ejecutó correctamente
composer install

# Si persiste, usar la ruta completa
php vendor\phpunit\phpunit\phpunit

# O crear un alias
Set-Alias phpunit "$PWD\vendor\bin\phpunit.bat"
```

### Problema 2: "Class not found"

**Error:**
```
Error: Class 'Student' not found
```

**Solución:**
```powershell
# Regenerar autoload de Composer
composer dump-autoload

# Verificar que las rutas en composer.json son correctas
```

### Problema 3: Xdebug no genera cobertura

**Error:**
```
Error: No code coverage driver available
```

**Solución:**
```powershell
# Verificar que Xdebug está cargado
php -v

# Verificar modo coverage
php -i | findstr xdebug.mode

# Si no aparece, editar php.ini:
# xdebug.mode = coverage,debug
```

### Problema 4: "Cannot modify header information"

**Error:**
```
Cannot modify header information - headers already sent
```

**Solución:**
```powershell
# En los archivos de prueba, NO uses echo o var_dump
# Usa $this->expectOutputString() si necesitas verificar salida
```

### Problema 5: Errores de conexión a base de datos

**Error:**
```
Connection refused [2002]
```

**Solución:**
```powershell
# 1. Verificar que MySQL está corriendo
net start MySQL

# 2. Verificar credenciales en config/database.php
# 3. Crear base de datos si no existe
```

### Problema 6: Timeout en pruebas

**Error:**
```
Maximum execution time of 30 seconds exceeded
```

**Solución:**

Editar `phpunit.xml`:
```xml
<php>
    <ini name="max_execution_time" value="0"/>
</php>
```

### Problema 7: Memoria insuficiente

**Error:**
```
Allowed memory size of X bytes exhausted
```

**Solución:**

Editar `phpunit.xml`:
```xml
<php>
    <ini name="memory_limit" value="512M"/>
</php>
```

---

## 📝 Comandos de Referencia Rápida

### Instalación Inicial (Solo una vez)

```powershell
# 1. Clonar repositorio
git clone https://github.com/jmunaycos/Proyecto-Ingenieria-REGISTRO.git
cd Proyecto-Ingenieria-REGISTRO

# 2. Instalar dependencias
composer install

# 3. Verificar PHPUnit
vendor\bin\phpunit --version
```

### Ejecución Diaria de Pruebas

```powershell
# Todas las pruebas
vendor\bin\phpunit

# Con cobertura
vendor\bin\phpunit --coverage-html coverage/html

# Solo modelos
vendor\bin\phpunit --testsuite Models

# Prueba específica
vendor\bin\phpunit --filter testCreateStudent
```

### Actualización del Proyecto

```powershell
# Obtener últimos cambios
git pull origin main

# Actualizar dependencias
composer update

# Ejecutar pruebas
vendor\bin\phpunit
```

---

## 🎯 Mejores Prácticas

### 1. Ejecuta pruebas ANTES de hacer commit

```powershell
# Asegúrate de que todo funciona
vendor\bin\phpunit

# Si todo está OK, hacer commit
git add .
git commit -m "feat: Nueva funcionalidad"
git push
```

### 2. Mantén alta cobertura de código

- **Meta mínima**: 70% de cobertura
- **Meta ideal**: 85-90% de cobertura
- Revisa el reporte HTML regularmente

### 3. Escribe pruebas para bugs

Cuando encuentres un bug:
1. Escribe una prueba que reproduzca el bug
2. La prueba debe fallar
3. Corrige el código
4. La prueba debe pasar
5. Commit con la prueba Y la corrección

### 4. Organiza tus pruebas

```
tests/
├── models/          # Pruebas de modelos (lógica de datos)
├── controllers/     # Pruebas de controladores (lógica de negocio)
├── integration/     # Pruebas de integración (flujo completo)
└── README.md        # Documentación de pruebas
```

### 5. Nombra las pruebas descriptivamente

```php
// ✅ Bueno - Descriptivo
public function testCreateStudentWithValidDataSucceeds()

// ❌ Malo - Genérico
public function test1()
```

---

## 📚 Recursos Adicionales

### Documentación Oficial

- **PHPUnit**: https://phpunit.de/documentation.html
- **Xdebug**: https://xdebug.org/docs/
- **Composer**: https://getcomposer.org/doc/

### Documentación del Proyecto

- `docs/tests/CASOS_DE_PRUEBA.md` - Lista de todos los casos de prueba
- `docs/tests/GUIA_PRUEBAS.md` - Guía técnica de pruebas
- `docs/tests/DOCUMENTACION_CASOS_EJECUTADOS.md` - Ejecución detallada
- `docs/SISTEMA_COMPLETO.md` - Documentación completa del sistema

### Video Tutoriales Recomendados

- PHPUnit Tutorial: https://www.youtube.com/watch?v=84j61_aI0q8
- Testing PHP Code: https://laracasts.com/series/phpunit-testing-in-laravel

---

## ✅ Checklist de Verificación

Antes de considerar que las pruebas están correctamente configuradas:

- [ ] PHP 7.4+ instalado y funcionando
- [ ] Composer instalado globalmente
- [ ] Proyecto clonado desde GitHub
- [ ] `composer install` ejecutado exitosamente
- [ ] Carpeta `vendor/` existe
- [ ] `vendor\bin\phpunit --version` muestra versión
- [ ] Xdebug instalado (verificar con `php -v`)
- [ ] `phpunit.xml` existe en la raíz
- [ ] `composer.json` existe en la raíz
- [ ] Base de datos configurada
- [ ] `vendor\bin\phpunit` ejecuta sin errores
- [ ] Las 19 pruebas pasan (100%)
- [ ] Reporte de cobertura se genera correctamente
- [ ] `coverage/html/index.html` es accesible

---

## 🆘 Soporte

Si encuentras problemas no cubiertos en esta guía:

1. Revisa los logs: `storage/logs/`
2. Consulta la documentación en `docs/tests/`
3. Revisa issues en GitHub: https://github.com/jmunaycos/Proyecto-Ingenieria-REGISTRO/issues
4. Contacta al equipo de desarrollo

---

**Última actualización:** 6 de diciembre de 2025  
**Versión del documento:** 1.0  
**Autor:** Equipo de Desarrollo - Universidad Autónoma del Perú
