# Informe de Cobertura de Código
## Sistema de Registro de Estudiantes - Proyecto Ingeniería

**Fecha:** 16 de diciembre de 2025  
**Versión:** 1.0  
**Herramienta:** PHPUnit 9.6.31 con Xdebug 3.5.0  
**Entorno:** PHP 8.2.12

---

## 📊 Resumen Ejecutivo

Este documento presenta los resultados del análisis de cobertura de código realizado sobre el Sistema de Registro de Estudiantes. La cobertura de código mide qué porcentaje del código fuente es ejecutado durante las pruebas automatizadas.

### Métricas Generales

| Métrica | Resultado | Objetivo Recomendado |
|---------|-----------|---------------------|
| **Clases** | 0.00% (0/10) | ≥ 80% |
| **Métodos** | 15.00% (12/80) | ≥ 80% |
| **Líneas** | 13.50% (84/622) | ≥ 80% |

### Estado General
🔴 **INSUFICIENTE** - Requiere mejoras significativas en la cobertura de pruebas

---

## 📈 Análisis Detallado por Componente

### 1. Modelos (Models)

#### 1.1 Career (Carrera)
```
Cobertura de Métodos: 42.86% (3/7)
Cobertura de Líneas:  66.67% (26/39)
Estado: 🟡 MODERADO
```

**Métodos Cubiertos:**
- ✅ `__construct()` - Constructor de clase
- ✅ `getAll()` - Obtener todas las carreras
- ✅ `getById()` - Obtener carrera por ID

**Métodos NO Cubiertos:**
- ❌ `create()` - Crear nueva carrera
- ❌ `update()` - Actualizar carrera
- ❌ `delete()` - Eliminar carrera
- ❌ `getByFacultad()` - Filtrar por facultad

**Recomendaciones:**
- Agregar pruebas para operaciones CRUD completas
- Implementar pruebas de validación de datos
- Validar integridad referencial con estudiantes

---

#### 1.2 User (Usuario)
```
Cobertura de Métodos: 30.00% (3/10)
Cobertura de Líneas:  29.09% (16/55)
Estado: 🔴 BAJO
```

**Métodos Cubiertos:**
- ✅ `__construct()` - Constructor de clase
- ✅ `getAll()` - Obtener todos los usuarios
- ✅ `getById()` - Obtener usuario por ID

**Métodos NO Cubiertos:**
- ❌ `create()` - Crear usuario
- ❌ `update()` - Actualizar usuario
- ❌ `delete()` - Eliminar usuario
- ❌ `getByUsername()` - Buscar por nombre de usuario
- ❌ `validateCredentials()` - Validar credenciales
- ❌ `changePassword()` - Cambiar contraseña
- ❌ `updateLastLogin()` - Actualizar último acceso

**Recomendaciones:**
- Priorizar pruebas de autenticación y autorización
- Implementar pruebas de seguridad (hash de contraseñas)
- Validar roles y permisos

---

#### 1.3 Student (Estudiante)
```
Cobertura de Métodos: 16.67% (2/12)
Cobertura de Líneas:  30.91% (34/110)
Estado: 🔴 BAJO
```

**Métodos Cubiertos:**
- ✅ `__construct()` - Constructor de clase
- ✅ `getAll()` - Obtener todos los estudiantes

**Métodos NO Cubiertos:**
- ❌ `create()` - Crear estudiante
- ❌ `update()` - Actualizar estudiante
- ❌ `delete()` - Eliminar estudiante
- ❌ `getById()` - Obtener por ID
- ❌ `getByDNI()` - Obtener por DNI (método faltante)
- ❌ `search()` - Buscar estudiantes
- ❌ `getStats()` - Obtener estadísticas (método faltante)
- ❌ `getByCareer()` - Filtrar por carrera
- ❌ `updateStatus()` - Actualizar estado
- ❌ `validateData()` - Validar datos

**Recomendaciones:**
- **CRÍTICO:** Implementar métodos `getByDNI()` y `getStats()` que están siendo llamados en las pruebas
- Agregar pruebas para validaciones (DNI, email, ciclo)
- Probar relación con modelo Career

---

#### 1.4 Auth (Autenticación)
```
Cobertura de Métodos: 6.67% (1/15)
Cobertura de Líneas:  9.30% (4/43)
Estado: 🔴 MUY BAJO
```

**Métodos Cubiertos:**
- ✅ `__construct()` - Constructor de clase

**Métodos NO Cubiertos:**
- ❌ `login()` - Inicio de sesión
- ❌ `logout()` - Cierre de sesión
- ❌ `isAuthenticated()` - Verificar autenticación
- ❌ `checkRole()` - Verificar rol
- ❌ `register()` - Registro de usuarios
- ❌ `validateSession()` - Validar sesión
- ❌ `refreshSession()` - Refrescar sesión
- Y 8 métodos más sin cobertura

**Problema Detectado:**
⚠️ **Error de sesión:** `session_start()` se ejecuta después de enviar headers, causando 11 fallos en las pruebas

**Recomendaciones:**
- **URGENTE:** Corregir manejo de sesiones en Auth.php línea 14
- Implementar mock de sesiones para pruebas unitarias
- Agregar pruebas de seguridad (intentos fallidos, tokens)

---

### 2. Controladores (Controllers)

#### 2.1 AuthController
```
Cobertura de Métodos: 25.00% (1/4)
Cobertura de Líneas:  2.08% (1/48)
Estado: 🔴 MUY BAJO
```

**Métodos Cubiertos:**
- ✅ `__construct()` - Constructor parcialmente

**Métodos NO Cubiertos:**
- ❌ `showLogin()` - Mostrar formulario de login
- ❌ `processLogin()` - Procesar login
- ❌ `logout()` - Cerrar sesión

---

#### 2.2 UserController
```
Cobertura de Métodos: 14.29% (1/7)
Cobertura de Líneas:  1.02% (1/98)
Estado: 🔴 MUY BAJO
```

**Problema Detectado:**
⚠️ **Método faltante:** `edit()` - Requerido para CRUD completo

**Métodos NO Cubiertos:**
- ❌ `index()` - Listar usuarios
- ❌ `create()` - Mostrar formulario crear
- ❌ `store()` - Guardar nuevo usuario
- ❌ `edit()` - Mostrar formulario editar (NO EXISTE)
- ❌ `update()` - Actualizar usuario
- ❌ `delete()` - Eliminar usuario

---

#### 2.3 StudentController
```
Cobertura de Métodos: 9.09% (1/11)
Cobertura de Líneas:  1.34% (2/149)
Estado: 🔴 MUY BAJO
```

**Métodos NO Cubiertos:** 10 de 11 métodos

---

#### 2.4 Otros Controladores
```
CareerController:     0.00% (0/5 métodos)
Controller:           0.00% (0/6 métodos)
DashboardController:  0.00% (0/3 métodos)
```

---

## 🎯 Resultados de las Pruebas

### Resumen de Ejecución
```
Total de Pruebas:      77
Pruebas Exitosas:      63 (81.82%)
Pruebas Fallidas:      3 (3.90%)
Errores:               11 (14.29%)
Aserciones Totales:    282
Tiempo de Ejecución:   12 segundos
Memoria Utilizada:     12.00 MB
```

### Distribución de Pruebas

#### Pruebas Unitarias (56 pruebas)
- **Modelos:** 37 pruebas
  - Auth: 6 pruebas (100% exitosas)
  - Career: 11 pruebas (100% exitosas)
  - Student: 5 pruebas (100% exitosas)
  - User: 15 pruebas (1 fallo - validación de roles)

- **Controladores:** 19 pruebas
  - AuthController: 7 pruebas (1 error - sesión)
  - StudentController: 5 pruebas (100% exitosas)
  - UserController: 10 pruebas (2 fallos - método edit faltante)

#### Pruebas de Integración (21 pruebas)
- Authentication: 5 pruebas (5 errores - sesiones)
- StudentCRUD: 6 pruebas (2 errores - métodos faltantes)
- StudentRegistration: 3 pruebas (100% exitosas)
- System: 6 pruebas (3 errores - sesiones)

---

## 🚨 Problemas Críticos Identificados

### 1. Errores de Sesión (Prioridad ALTA)
**Cantidad:** 11 errores  
**Archivo:** `app/models/Auth.php` línea 14

**Descripción:**
```
session_start(): Session cannot be started after headers have already been sent
```

**Impacto:**
- Imposibilita probar flujos de autenticación completos
- Afecta 11 pruebas de integración
- Bloquea validación de roles y permisos

**Solución Recomendada:**
```php
// En bootstrap.php o Auth.php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
```

---

### 2. Métodos Faltantes (Prioridad ALTA)

#### Student Model
- `getByDNI()` - Llamado en `StudentCRUDIntegrationTest.php:47`
- `getStats()` - Llamado en `StudentCRUDIntegrationTest.php:108`

#### UserController
- `edit()` - Método CRUD requerido

**Impacto:**
- 3 pruebas fallidas
- CRUD incompleto
- Funcionalidad limitada

---

### 3. Validación de Roles (Prioridad MEDIA)

**Error en:** `tests/models/UserTest.php:129`
```
El rol debe ser uno de los roles válidos: admin, user
Failed asserting that an array contains 'usuario'
```

**Problema:**
- Inconsistencia entre roles esperados ['admin', 'user'] y rol real 'usuario'
- Puede indicar problema en base de datos o validación

**Solución:**
Estandarizar roles en la aplicación (decidir entre 'user' o 'usuario')

---

## 📁 Reportes Generados

### Reportes HTML Interactivos
- 📊 **Coverage Report:** `tests/coverage/html/index.html`
  - Navegación por clases y métodos
  - Líneas cubiertas vs no cubiertas (código resaltado)
  - Gráficos de cobertura

### Reportes XML/Texto
- 📄 **Clover XML:** `tests/coverage/clover.xml` (para CI/CD)
- 📄 **JUnit XML:** `tests/reports/junit.xml`
- 📄 **TestDox HTML:** `tests/reports/testdox.html`
- 📄 **TestDox Text:** `tests/reports/testdox.txt`
- 📄 **Coverage Text:** Salida de consola

---

## 📋 Plan de Mejora Recomendado

### Fase 1: Correcciones Críticas (Urgente)
⏱️ Estimado: 2-3 días

1. ✅ Corregir manejo de sesiones en Auth.php
2. ✅ Implementar métodos faltantes:
   - `Student::getByDNI()`
   - `Student::getStats()`
   - `UserController::edit()`
3. ✅ Estandarizar validación de roles
4. ✅ Re-ejecutar suite de pruebas

**Objetivo:** Lograr 0 errores y 0 fallos

---

### Fase 2: Incrementar Cobertura de Modelos (Corto Plazo)
⏱️ Estimado: 1 semana

**Objetivo:** Alcanzar 60% de cobertura en modelos

**Acciones:**
1. **Auth Model (Prioridad 1)**
   - Pruebas de login exitoso/fallido
   - Pruebas de logout
   - Pruebas de validación de sesión
   - Pruebas de roles y permisos
   - **Meta:** 70% cobertura

2. **User Model (Prioridad 2)**
   - Pruebas CRUD completas
   - Pruebas de validación (username único, email válido)
   - Pruebas de cambio de contraseña
   - Pruebas de seguridad (hash de passwords)
   - **Meta:** 80% cobertura

3. **Student Model (Prioridad 3)**
   - Pruebas CRUD completas
   - Pruebas de búsqueda y filtros
   - Pruebas de validación (DNI, email, ciclo)
   - Pruebas de relaciones (Career)
   - **Meta:** 75% cobertura

4. **Career Model**
   - Mantener y mejorar cobertura actual
   - Agregar pruebas CRUD faltantes
   - **Meta:** 85% cobertura

---

### Fase 3: Cobertura de Controladores (Mediano Plazo)
⏱️ Estimado: 1-2 semanas

**Objetivo:** Alcanzar 70% de cobertura en controladores

**Acciones:**
1. **AuthController**
   - Pruebas de vista login
   - Pruebas de procesamiento de formularios
   - Pruebas de redirecciones
   - Pruebas de mensajes de error
   - **Meta:** 80% cobertura

2. **UserController**
   - Pruebas de todas las vistas (index, create, edit)
   - Pruebas de validaciones
   - Pruebas de permisos
   - **Meta:** 75% cobertura

3. **StudentController**
   - Pruebas de registro de estudiantes
   - Pruebas de listado y búsqueda
   - Pruebas de exportación (si existe)
   - **Meta:** 75% cobertura

4. **CareerController**
   - Implementar suite completa
   - **Meta:** 80% cobertura

---

### Fase 4: Pruebas de Integración Avanzadas (Largo Plazo)
⏱️ Estimado: 2 semanas

**Objetivo:** Validar flujos completos del sistema

**Acciones:**
1. Flujos de usuario completos (registro → login → operaciones → logout)
2. Pruebas de permisos entre roles
3. Pruebas de rendimiento
4. Pruebas de carga de datos
5. Pruebas de seguridad

---

## 🎯 Objetivos de Cobertura

### Corto Plazo (1 mes)
```
Clases:   30%
Métodos:  50%
Líneas:   50%
```

### Mediano Plazo (3 meses)
```
Clases:   70%
Métodos:  75%
Líneas:   75%
```

### Largo Plazo (6 meses)
```
Clases:   85%
Métodos:  85%
Líneas:   85%
```

---

## 📌 Métricas de Calidad

### Estándares de la Industria
- **Mínimo Aceptable:** 60-70%
- **Bueno:** 75-85%
- **Excelente:** 85-95%
- **Sobreprueba:** >95% (puede indicar pruebas innecesarias)

### Estado Actual vs. Estándar
```
                  Actual    Mínimo    Diferencia
Métodos:          15%       60%       -45% ❌
Líneas:           13.5%     60%       -46.5% ❌
```

**Conclusión:** El proyecto requiere un esfuerzo significativo para alcanzar niveles mínimos de calidad en cobertura de pruebas.

---

## 🔧 Herramientas y Configuración

### Configuración Actual (phpunit.xml)
- ✅ Cobertura habilitada para `app/models`, `app/controllers`, `helpers`
- ✅ Exclusión de `app/views` y `vendor`
- ✅ Reportes HTML, Clover y Texto configurados
- ✅ Logging de resultados (JUnit, TestDox)
- ✅ Bootstrap personalizado (`tests/bootstrap.php`)

### Comandos Útiles

#### Ejecutar todas las pruebas con cobertura
```bash
vendor/bin/phpunit --coverage-html tests/coverage/html --coverage-text
```

#### Ejecutar solo pruebas unitarias
```bash
vendor/bin/phpunit --testsuite "Unit Tests"
```

#### Ejecutar solo pruebas de integración
```bash
vendor/bin/phpunit --testsuite "Integration Tests"
```

#### Ver reporte en formato testdox
```bash
vendor/bin/phpunit --testdox --colors=always
```

#### Generar todos los reportes
```bash
vendor/bin/phpunit --coverage-html tests/coverage/html --coverage-clover tests/coverage/clover.xml --testdox
```

---

## 📊 Visualización de Resultados

### Gráfico de Cobertura Actual
```
Cobertura de Métodos
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Career            ████████▓▓▓▓▓▓▓▓▓▓▓▓  42.86%
User              ██████▓▓▓▓▓▓▓▓▓▓▓▓▓▓  30.00%
Student           ███▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  16.67%
Auth              █▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓   6.67%
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
0%                50%               100%

Cobertura de Líneas
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Career            █████████████▓▓▓▓▓▓▓  66.67%
Student           ██████▓▓▓▓▓▓▓▓▓▓▓▓▓▓  30.91%
User              █████▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  29.09%
Auth              █▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓   9.30%
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
0%                50%               100%
```

---

## 📝 Conclusiones y Recomendaciones

### Conclusiones

1. **Cobertura Insuficiente:** Con solo 13.5% de líneas cubiertas, el proyecto está muy por debajo de los estándares mínimos de la industria (60%).

2. **Problemas Técnicos Críticos:** Los errores de manejo de sesiones impiden probar componentes críticos de autenticación.

3. **Código Incompleto:** Métodos faltantes en Student y UserController indican desarrollo incompleto.

4. **Base Sólida:** Las 63 pruebas exitosas demuestran que la infraestructura de testing está bien configurada.

5. **Modelo Career Destacado:** Con 66.67% de cobertura de líneas, demuestra que es posible alcanzar buenos niveles.

### Recomendaciones Prioritarias

#### 🔴 Urgente (Esta Semana)
1. Corregir errores de sesión en Auth.php
2. Implementar métodos faltantes
3. Resolver inconsistencias de roles
4. Re-ejecutar suite completa

#### 🟡 Importante (Este Mes)
1. Incrementar cobertura de Auth a 70%
2. Incrementar cobertura de User a 80%
3. Completar pruebas CRUD de Student
4. Alcanzar 50% de cobertura general

#### 🟢 Deseable (Próximos 3 Meses)
1. Implementar pruebas de controladores
2. Agregar pruebas de integración avanzadas
3. Configurar CI/CD con validación de cobertura mínima
4. Alcanzar 75% de cobertura general

### Beneficios Esperados

✅ **Mayor confiabilidad:** Reducción de bugs en producción  
✅ **Refactoring seguro:** Cambios de código con confianza  
✅ **Documentación viva:** Las pruebas documentan el comportamiento esperado  
✅ **Detección temprana:** Identificación de problemas antes del deployment  
✅ **Mantenibilidad:** Código más fácil de mantener y evolucionar  

---

## 📚 Referencias y Recursos

### Documentación
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Xdebug Code Coverage](https://xdebug.org/docs/code_coverage)
- [Test-Driven Development Best Practices](https://martinfowler.com/bliki/TestCoverage.html)

### Estándares de Cobertura
- IEEE Std 829-2008 (Software Test Documentation)
- ISO/IEC 25010 (Software Quality)

### Archivos de Configuración
- `phpunit.xml` - Configuración de PHPUnit
- `tests/bootstrap.php` - Inicialización de pruebas

---

## Anexos

### A. Listado Completo de Archivos de Prueba

#### Modelos
- `tests/models/AuthTest.php` (6 pruebas)
- `tests/models/CareerTest.php` (11 pruebas)
- `tests/models/StudentTest.php` (5 pruebas - falta implementar)
- `tests/models/UserTest.php` (15 pruebas)

#### Controladores
- `tests/controllers/AuthControllerTest.php` (7 pruebas)
- `tests/controllers/StudentControllerTest.php` (5 pruebas - falta implementar)
- `tests/controllers/UserControllerTest.php` (10 pruebas)

#### Integración
- `tests/integration/AuthenticationIntegrationTest.php` (5 pruebas)
- `tests/integration/StudentCRUDIntegrationTest.php` (6 pruebas)
- `tests/integration/StudentRegistrationTest.php` (3 pruebas)
- `tests/integration/SystemIntegrationTest.php` (6 pruebas)

### B. Datos de Ejecución

```
Fecha: 2025-12-16 11:26:10
PHP Version: 8.2.12
Xdebug Version: 3.5.0
PHPUnit Version: 9.6.31
Sistema Operativo: Windows (XAMPP)
Tiempo Total: 12 segundos
Memoria Máxima: 12.00 MB
```

---

**Documento generado automáticamente por PHPUnit**  
**Última actualización:** 16 de diciembre de 2025
