# Guía de Ejecución de Pruebas

## Requisitos Previos

### 1. Instalar PHPUnit
```bash
composer require --dev phpunit/phpunit ^9
```

### 2. Verificar Instalación
```bash
vendor/bin/phpunit --version
```

---

## Ejecución de Pruebas

### Ejecutar TODAS las pruebas
```bash
vendor/bin/phpunit
```

### Ejecutar con detalles verbosos
```bash
vendor/bin/phpunit --verbose
```

### Ejecutar con colores
```bash
vendor/bin/phpunit --colors=always
```

---

## Ejecutar Pruebas por Tipo

### Solo Pruebas Unitarias (Modelos)
```bash
vendor/bin/phpunit tests/models
```

### Solo Pruebas de Controladores
```bash
vendor/bin/phpunit tests/controllers
```

### Solo Pruebas de Integración
```bash
vendor/bin/phpunit tests/integration
```

---

## Ejecutar Pruebas Específicas

### Un archivo específico
```bash
vendor/bin/phpunit tests/models/StudentTest.php
```

### Una clase específica
```bash
vendor/bin/phpunit --filter StudentTest
```

### Un método específico
```bash
vendor/bin/phpunit --filter testGetAllReturnsArray
```

---

## Reportes y Cobertura

### Generar reporte de cobertura en HTML
```bash
vendor/bin/phpunit --coverage-html tests/coverage
```

Luego abrir: `tests/coverage/index.html`

### Generar reporte testdox en HTML
```bash
vendor/bin/phpunit --testdox-html tests/reports/testdox.html
```

### Generar reporte testdox en texto
```bash
vendor/bin/phpunit --testdox-text tests/reports/testdox.txt
```

### Ver testdox en consola
```bash
vendor/bin/phpunit --testdox
```

---

## Opciones Útiles

### Detener en el primer error
```bash
vendor/bin/phpunit --stop-on-failure
```

### Detener en el primer error o fallo
```bash
vendor/bin/phpunit --stop-on-error --stop-on-failure
```

### Ver solo resumen
```bash
vendor/bin/phpunit --no-coverage
```

### Ejecutar en orden aleatorio
```bash
vendor/bin/phpunit --order-by=random
```

---

## Configuración Avanzada

### Usar archivo de configuración personalizado
```bash
vendor/bin/phpunit -c phpunit.xml
```

### Ver información de debug
```bash
vendor/bin/phpunit --debug
```

### Listar todas las pruebas sin ejecutarlas
```bash
vendor/bin/phpunit --list-tests
```

---

## Ejemplos de Uso Común

### Desarrollo: Ejecutar rápido sin cobertura
```bash
vendor/bin/phpunit --no-coverage --testdox
```

### QA: Reporte completo
```bash
vendor/bin/phpunit --coverage-html tests/coverage --testdox-html tests/reports/testdox.html
```

### CI/CD: Formato XML
```bash
vendor/bin/phpunit --log-junit tests/reports/junit.xml
```

### Debugging: Detalles completos
```bash
vendor/bin/phpunit --verbose --debug --testdox
```

---

## Interpretación de Resultados

### Símbolos en la salida
- `.` (punto) = Prueba exitosa
- `F` = Fallo (assertion falló)
- `E` = Error (excepción no esperada)
- `I` = Incompleta
- `S` = Saltada (skipped)
- `R` = Riesgosa (risky)

### Colores
- 🟢 Verde = Todas las pruebas pasaron
- 🔴 Rojo = Al menos una prueba falló
- 🟡 Amarillo = Advertencias o pruebas incompletas

---

## Solución de Problemas Comunes

### Error: "Class not found"
**Solución:** Verificar que bootstrap.php se está cargando correctamente

### Error: "Cannot modify header information"
**Solución:** Sesiones ya iniciadas, verificar setUp() en tests

### Error: "Database connection failed"
**Solución:** Verificar configuración de base de datos en config/database.php

### Error: "Permission denied"
**Solución:** 
```bash
chmod -R 777 tests/reports
chmod -R 777 tests/coverage
```

---

## Mejores Prácticas

1. **Ejecutar antes de cada commit**
   ```bash
   vendor/bin/phpunit --stop-on-failure
   ```

2. **Revisar cobertura semanalmente**
   ```bash
   vendor/bin/phpunit --coverage-html tests/coverage
   ```

3. **Mantener reportes actualizados**
   - Generar reportes después de cambios importantes
   - Compartir con el equipo

4. **Usar testdox para documentación**
   - El reporte testdox sirve como documentación viva

---

## Integración con VS Code

### Extensión recomendada
- **PHP Unit Test Explorer**

### Configuración en settings.json
```json
{
    "phpunit.execPath": "vendor/bin/phpunit",
    "phpunit.args": [
        "--colors=always"
    ]
}
```

---

## Scripts Útiles

### Agregar a composer.json
```json
{
    "scripts": {
        "test": "phpunit",
        "test:unit": "phpunit tests/models",
        "test:controllers": "phpunit tests/controllers",
        "test:integration": "phpunit tests/integration",
        "test:coverage": "phpunit --coverage-html tests/coverage",
        "test:report": "phpunit --testdox-html tests/reports/testdox.html"
    }
}
```

### Uso
```bash
composer test
composer test:unit
composer test:coverage
```

---

## Notas Importantes

⚠️ **Las pruebas se ejecutan contra la base de datos real**
- No se crean ni destruyen datos
- Solo se leen datos existentes
- Algunas pruebas se saltan si no hay datos

✅ **Recomendaciones**
- Ejecutar en entorno de desarrollo
- Tener datos de prueba en la base de datos
- Revisar logs si hay errores

🔧 **Mantenimiento**
- Actualizar pruebas al modificar código
- Agregar pruebas para nuevas funcionalidades
- Mantener cobertura > 80%

---

**Última actualización:** 16 de diciembre de 2025
