# Plan de Pruebas - Sistema de Registro Universitario

**Proyecto:** Sistema de Opinión Estudiante  
**Versión:** 1.0  
**Fecha:** 16 de diciembre de 2025  
**Responsable:** Equipo de Desarrollo

---

## 1. Introducción

### 1.1 Propósito
Este documento describe el plan de pruebas para el Sistema de Registro Universitario, incluyendo pruebas unitarias, de controladores y de integración.

### 1.2 Alcance
El plan de pruebas cubre:
- Modelos de datos (Student, User, Career, Auth)
- Controladores (AuthController, StudentController, UserController)
- Flujos de integración completos
- Validación de reglas de negocio

### 1.3 Objetivos
- Garantizar el correcto funcionamiento de todos los componentes
- Validar la integridad de los datos
- Verificar los flujos de autenticación y autorización
- Asegurar la calidad del código

---

## 2. Estrategia de Pruebas

### 2.1 Tipos de Pruebas

#### 2.1.1 Pruebas Unitarias
- **Modelos:** StudentTest, UserTest, CareerTest, AuthTest
- **Objetivo:** Verificar que cada método funciona correctamente de forma aislada
- **Cobertura esperada:** > 80%

#### 2.1.2 Pruebas de Controladores
- **Controladores:** AuthControllerTest, StudentControllerTest, UserControllerTest
- **Objetivo:** Verificar la estructura y métodos de los controladores
- **Cobertura esperada:** > 70%

#### 2.1.3 Pruebas de Integración
- **Flujos:** Authentication, StudentCRUD, System
- **Objetivo:** Verificar que los componentes funcionan correctamente en conjunto
- **Cobertura esperada:** Flujos críticos completos

### 2.2 Herramientas
- **Framework:** PHPUnit 9.x
- **Servidor:** XAMPP (PHP 7.4+)
- **Base de datos:** MySQL
- **Reportes:** HTML, XML, TXT

---

## 3. Casos de Prueba

### 3.1 Pruebas de Modelos

#### 3.1.1 Student Model
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| ST-01 | Instanciación del modelo | Alta | ✓ |
| ST-02 | getAll() retorna array | Alta | ✓ |
| ST-03 | getById() con ID válido | Alta | ✓ |
| ST-04 | getById() con ID inválido | Media | ✓ |
| ST-05 | Estructura de datos correcta | Alta | ✓ |
| ST-06 | getByDNI() funciona correctamente | Alta | ✓ |
| ST-07 | getByEmail() funciona correctamente | Alta | ✓ |
| ST-08 | Validación de email | Media | ✓ |
| ST-09 | Búsqueda de estudiantes | Media | ✓ |
| ST-10 | Obtener estadísticas | Baja | ✓ |

#### 3.1.2 User Model
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| US-01 | Instanciación del modelo | Alta | ✓ |
| US-02 | getAll() retorna array | Alta | ✓ |
| US-03 | getById() funciona correctamente | Alta | ✓ |
| US-04 | getByUsername() funciona | Alta | ✓ |
| US-05 | Password no se expone en getAll() | Crítica | ✓ |
| US-06 | Validación de roles | Alta | ✓ |
| US-07 | Username no vacío | Media | ✓ |
| US-08 | Formato de fecha válido | Baja | ✓ |

#### 3.1.3 Career Model
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| CA-01 | Instanciación del modelo | Alta | ✓ |
| CA-02 | getAll() retorna array | Alta | ✓ |
| CA-03 | getAllGroupedByFacultad() | Alta | ✓ |
| CA-04 | getByFacultad() funciona | Media | ✓ |
| CA-05 | IDs únicos | Alta | ✓ |
| CA-06 | Nombres no vacíos | Media | ✓ |

#### 3.1.4 Auth Model
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| AU-01 | initSession() inicia sesión | Alta | ✓ |
| AU-02 | login() establece variables | Crítica | ✓ |
| AU-03 | check() con sesión activa | Crítica | ✓ |
| AU-04 | check() sin sesión | Crítica | ✓ |
| AU-05 | logout() limpia sesión | Alta | ✓ |
| AU-06 | user() retorna datos | Alta | ✓ |
| AU-07 | hasRole() verifica rol | Alta | ✓ |
| AU-08 | isAdmin() funciona | Alta | ✓ |
| AU-09 | isUser() funciona | Alta | ✓ |

### 3.2 Pruebas de Controladores

#### 3.2.1 AuthController
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| AC-01 | Instanciación del controlador | Alta | ✓ |
| AC-02 | Extiende Controller base | Media | ✓ |
| AC-03 | Método showLogin() existe | Alta | ✓ |
| AC-04 | Método processLogin() existe | Crítica | ✓ |
| AC-05 | Método logout() existe | Alta | ✓ |

#### 3.2.2 StudentController
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| SC-01 | Instanciación del controlador | Alta | ✓ |
| SC-02 | Métodos CRUD completos | Alta | ✓ |
| SC-03 | Métodos son públicos | Media | ✓ |

#### 3.2.3 UserController
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| UC-01 | Instanciación del controlador | Alta | ✓ |
| UC-02 | Métodos CRUD completos | Alta | ✓ |
| UC-03 | Métodos son públicos | Media | ✓ |

### 3.3 Pruebas de Integración

#### 3.3.1 Authentication Flow
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| AI-01 | Flujo completo de login | Crítica | ✓ |
| AI-02 | Flujo completo de logout | Alta | ✓ |
| AI-03 | Verificación de roles post-login | Alta | ✓ |
| AI-04 | Integración Auth-User | Alta | ✓ |
| AI-05 | Persistencia de sesión | Media | ✓ |

#### 3.3.2 Student CRUD Flow
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| SI-01 | Flujo completo de lectura | Alta | ✓ |
| SI-02 | Integración Student-Career | Alta | ✓ |
| SI-03 | Búsqueda de estudiantes | Media | ✓ |
| SI-04 | Estadísticas | Baja | ✓ |
| SI-05 | Validación de datos | Alta | ✓ |
| SI-06 | Consistencia de datos | Media | ✓ |

#### 3.3.3 System Integration
| ID | Caso de Prueba | Prioridad | Estado |
|----|----------------|-----------|--------|
| SY-01 | Workflow completo de usuario | Crítica | ✓ |
| SY-02 | Permisos de administrador | Alta | ✓ |
| SY-03 | Integridad entre modelos | Alta | ✓ |
| SY-04 | Independencia de modelos | Media | ✓ |
| SY-05 | Disponibilidad de funciones | Alta | ✓ |
| SY-06 | Rendimiento de consultas | Media | ✓ |

---

## 4. Criterios de Aceptación

### 4.1 Criterios Generales
- ✅ Todas las pruebas unitarias deben pasar
- ✅ Cobertura de código > 80% en modelos
- ✅ Cobertura de código > 70% en controladores
- ✅ Todas las pruebas de integración críticas deben pasar
- ✅ Tiempo de ejecución total < 30 segundos

### 4.2 Criterios Específicos por Componente

#### Modelos
- Sin errores en consultas a base de datos
- Validación correcta de datos
- Manejo apropiado de casos edge

#### Controladores
- Métodos CRUD completos
- Estructura correcta
- Herencia apropiada

#### Integración
- Flujos completos sin errores
- Consistencia de datos
- Rendimiento aceptable

---

## 5. Entorno de Pruebas

### 5.1 Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- PHPUnit 9.x
- Composer

### 5.2 Configuración
```bash
# Instalar dependencias
composer install

# Ejecutar todas las pruebas
vendor/bin/phpunit

# Ejecutar con cobertura
vendor/bin/phpunit --coverage-html tests/coverage
```

### 5.3 Base de Datos
- Se utiliza la base de datos de desarrollo
- No se crean datos de prueba destructivos
- Se valida integridad de datos existentes

---

## 6. Resultados Esperados

### 6.1 Métricas de Calidad
- **Pruebas totales:** ~60-80 pruebas
- **Tasa de éxito:** 100%
- **Cobertura de código:** > 75%
- **Tiempo de ejecución:** < 30s

### 6.2 Reportes Generados
1. **testdox.html** - Reporte legible de todas las pruebas
2. **testdox.txt** - Versión texto del reporte
3. **junit.xml** - Reporte para integración continua
4. **coverage/** - Reporte de cobertura de código

---

## 7. Riesgos y Mitigaciones

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Cambios en BD durante pruebas | Baja | Alto | Usar transacciones |
| Dependencia de datos existentes | Media | Medio | Validar antes de ejecutar |
| Sesiones activas interfieren | Media | Bajo | Limpiar en setUp/tearDown |
| Permisos de archivos | Baja | Medio | Verificar permisos escritura |

---

## 8. Calendario de Ejecución

| Fase | Descripción | Responsable | Fecha |
|------|-------------|-------------|-------|
| Preparación | Configurar entorno | Equipo Dev | 16/12/2025 |
| Ejecución | Ejecutar todas las pruebas | QA | 16/12/2025 |
| Análisis | Revisar resultados | Equipo Dev | 16/12/2025 |
| Reporte | Documentar hallazgos | QA | 16/12/2025 |

---

## 9. Contactos

**Equipo de Desarrollo**
- Proyecto: Sistema de Registro Universitario
- Framework: PHP MVC Custom

---

## 10. Aprobaciones

| Rol | Nombre | Firma | Fecha |
|-----|--------|-------|-------|
| Desarrollador | | | |
| QA | | | |
| Project Manager | | | |

---

**Fin del Documento**
