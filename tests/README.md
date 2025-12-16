# Tests - Sistema de Opinión Estudiante

## 📋 Descripción

Este directorio contiene un sistema completo de pruebas unitarias, de controladores y de integración para el Sistema de Registro Universitario.

## 📊 Estadísticas

- **Total de Pruebas:** 77 casos de prueba
- **Pruebas Unitarias:** 28 pruebas (Modelos)
- **Pruebas de Controladores:** 22 pruebas
- **Pruebas de Integración:** 27 pruebas
- **Framework:** PHPUnit 9.6.31
- **PHP Version:** 7.4+ / 8.x

## 📁 Estructura Completa

```
tests/
├── bootstrap.php                          # Bootstrap de PHPUnit
├── PLAN_DE_PRUEBAS.md                    # Plan formal de pruebas
├── GUIA_EJECUCION.md                     # Guía de comandos y ejecución
├── REPORTE_RESULTADOS.md                 # Reporte de resultados
├── README.md                              # Este archivo
├── reports/                               # Reportes generados
│   ├── testdox.html
│   ├── testdox.txt
│   └── junit.xml
├── coverage/                              # Reporte de cobertura
├── models/                                # Pruebas unitarias de modelos
│   ├── AuthTest.php                      # 15 pruebas
│   ├── StudentTest.php                   # 11 pruebas
│   ├── UserTest.php                      # 13 pruebas
│   └── CareerTest.php                    # 11 pruebas
├── controllers/                           # Pruebas de controladores
│   ├── AuthControllerTest.php            # 7 pruebas
│   ├── StudentControllerTest.php         # 5 pruebas
│   └── UserControllerTest.php            # 10 pruebas
└── integration/                           # Pruebas de integración
    ├── AuthenticationIntegrationTest.php # 5 pruebas
    ├── StudentCRUDIntegrationTest.php    # 6 pruebas
    └── SystemIntegrationTest.php         # 6 pruebas
```

## 🚀 Inicio Rápido

### 1. Requisitos Previos
- PHPUnit 9.x instalado
- PHP 7.4 o superior
- **MySQL activo** (importante)
- Base de datos configurada

### 2. Verificar Instalación
```bash
vendor/bin/phpunit --version
```

### 3. Iniciar MySQL (si no está activo)
```bash
# Windows (XAMPP)
c:\xampp\mysql_start.bat

# O desde el panel de control de XAMPP
```

### 4. Ejecutar Todas las Pruebas
```bash
vendor/bin/phpunit
```

## 📖 Documentación

### Documentos Disponibles

1. **[PLAN_DE_PRUEBAS.md](PLAN_DE_PRUEBAS.md)**
   - Plan completo de pruebas
   - Casos de prueba detallados
   - Criterios de aceptación
   - Estrategia de testing

2. **[GUIA_EJECUCION.md](GUIA_EJECUCION.md)**
   - Comandos de ejecución
   - Generación de reportes
   - Solución de problemas
   - Mejores prácticas

3. **[REPORTE_RESULTADOS.md](REPORTE_RESULTADOS.md)**
   - Resultados de ejecución
   - Métricas de calidad
   - Problemas identificados
   - Recomendaciones

## 🎯 Comandos Principales

### Ejecutar Pruebas

```bash
# Todas las pruebas
vendor/bin/phpunit

# Con formato testdox (legible)
vendor/bin/phpunit --testdox

# Solo modelos
vendor/bin/phpunit tests/models

# Solo controladores
vendor/bin/phpunit tests/controllers

# Solo integración
vendor/bin/phpunit tests/integration

# Un archivo específico
vendor/bin/phpunit tests/models/StudentTest.php
```

### Generar Reportes

```bash
# Cobertura de código (HTML)
vendor/bin/phpunit --coverage-html tests/coverage

# Reporte testdox (HTML)
vendor/bin/phpunit --testdox-html tests/reports/testdox.html

# Reporte completo
vendor/bin/phpunit --coverage-html tests/coverage --testdox-html tests/reports/testdox.html
```

## 🧪 Tipos de Pruebas

### Pruebas Unitarias (Modelos)
- **AuthTest:** Autenticación y sesiones
- **StudentTest:** Gestión de estudiantes
- **UserTest:** Gestión de usuarios
- **CareerTest:** Gestión de carreras

### Pruebas de Controladores
- **AuthControllerTest:** Controlador de autenticación
- **StudentControllerTest:** Controlador de estudiantes
- **UserControllerTest:** Controlador de usuarios

### Pruebas de Integración
- **AuthenticationIntegrationTest:** Flujos de login/logout
- **StudentCRUDIntegrationTest:** CRUD completo de estudiantes
- **SystemIntegrationTest:** Integración del sistema completo

## ✅ Convenciones

- Cada archivo de prueba termina con `Test.php`
- Cada clase extiende `PHPUnit\Framework\TestCase`
- Métodos de prueba usan anotación `@test`
- Nombres descriptivos en formato camelCase
- setUp() y tearDown() para inicialización

## 📊 Métricas Objetivo

- **Cobertura de código:** > 80%
- **Tasa de éxito:** 100%
- **Tiempo de ejecución:** < 30 segundos
- **Assertions por prueba:** 2-5

## ⚠️ Importante

**Antes de ejecutar las pruebas:**
1. ✅ Verificar que MySQL esté activo
2. ✅ Verificar conexión a base de datos
3. ✅ Asegurar que hay datos de prueba
4. ✅ PHPUnit instalado correctamente

## 🔧 Solución de Problemas

### Error: "Cannot connect to MySQL"
**Solución:** Iniciar MySQL desde XAMPP

### Error: "Class not found"
**Solución:** Verificar bootstrap.php y autoload

### Error: "No tests executed"
**Solución:** Verificar rutas en phpunit.xml

Ver [GUIA_EJECUCION.md](GUIA_EJECUCION.md) para más detalles.

## 📈 Próximos Pasos

1. Ejecutar todas las pruebas con MySQL activo
2. Revisar reporte de cobertura
3. Mejorar pruebas con baja cobertura
4. Agregar más casos edge
5. Implementar CI/CD

## 📞 Soporte

Para más información, consultar:
- [PLAN_DE_PRUEBAS.md](PLAN_DE_PRUEBAS.md) - Plan detallado
- [GUIA_EJECUCION.md](GUIA_EJECUCION.md) - Guía de uso
- [REPORTE_RESULTADOS.md](REPORTE_RESULTADOS.md) - Resultados

---

**Última actualización:** 16 de diciembre de 2025  
**Versión:** 1.0  
**Estado:** ✅ Sistema de pruebas completo y documentado
- Los métodos de prueba deben comenzar con `test`
