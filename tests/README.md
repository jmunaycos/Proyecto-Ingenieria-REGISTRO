# Tests - Sistema de Opinión Estudiante

## Estructura de Pruebas

Este directorio contiene las pruebas unitarias y de integración del sistema.

### Requisitos

- PHPUnit 9.x o superior
- PHP 7.4 o superior

### Instalación de PHPUnit

```bash
composer require --dev phpunit/phpunit ^9
```

### Ejecutar todas las pruebas

```bash
vendor/bin/phpunit tests
```

### Ejecutar pruebas específicas

```bash
# Pruebas de modelos
vendor/bin/phpunit tests/models

# Pruebas de controladores
vendor/bin/phpunit tests/controllers

# Prueba específica
vendor/bin/phpunit tests/models/StudentTest.php
```

## Estructura de carpetas

```
tests/
├── models/          # Pruebas para los modelos
├── controllers/     # Pruebas para los controladores
├── integration/     # Pruebas de integración
└── README.md
```

## Convenciones

- Cada archivo de prueba debe terminar con `Test.php`
- Cada clase de prueba debe extender `PHPUnit\Framework\TestCase`
- Los métodos de prueba deben comenzar con `test`
