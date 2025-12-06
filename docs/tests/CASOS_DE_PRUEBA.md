# Casos de Prueba - Sistema de Opinión Estudiante

## Documento de Casos de Prueba
**Proyecto:** Sistema de Opinión del Estudiante  
**Versión:** 2.0.0  
**Fecha:** Diciembre 2025  
**Autor:** Equipo de Desarrollo

---

## Índice
1. [Pruebas Funcionales](#pruebas-funcionales)
2. [Pruebas de Validación](#pruebas-de-validación)
3. [Pruebas de Integración](#pruebas-de-integración)
4. [Pruebas de Seguridad](#pruebas-de-seguridad)
5. [Pruebas de Email](#pruebas-de-email)

---

## Pruebas Funcionales

### CP-001: Registro de Opinión Exitoso
**Objetivo:** Verificar que un estudiante puede registrar su opinión correctamente.

**Precondiciones:**
- Sistema accesible en `http://dominio.com/Sistema_encuesta/public`
- Base de datos conectada y funcional

**Datos de prueba:**
```
DNI: 12345678
Nombres: Juan Carlos
Apellidos: Pérez García
Correo: juan.perez@ejemplo.com
Carrera: Ingeniería de Sistemas
Ciclo: 5
Comentarios: La plataforma es muy útil
```

**Pasos:**
1. Acceder al formulario de opinión
2. Completar todos los campos con los datos de prueba
3. Hacer clic en "Registrar tu opinión"
4. Verificar mensaje de éxito

**Resultado esperado:**
- Modal muestra: "opinion del estudiante registrado con exito"
- Se envía correo de confirmación al estudiante
- Datos se guardan en la base de datos

**Estado:** ✅ Pendiente  
**Prioridad:** Alta

---

### CP-002: Validación de DNI
**Objetivo:** Verificar que el sistema valida correctamente el formato del DNI.

**Casos de prueba:**

| DNI | Válido | Razón |
|-----|--------|-------|
| 12345678 | ✅ Sí | 8 dígitos numéricos |
| 1234567 | ❌ No | Solo 7 dígitos |
| 123456789 | ❌ No | 9 dígitos (muy largo) |
| 1234abcd | ❌ No | Contiene letras |
| | ❌ No | Campo vacío |

**Resultado esperado:**
- DNI válido: Se acepta y continúa
- DNI inválido: Muestra error "DNI debe tener 8 dígitos"

**Estado:** ✅ Pendiente  
**Prioridad:** Alta

---

### CP-003: Validación de Correo Electrónico
**Objetivo:** Verificar que el sistema valida correctamente el formato del correo.

**Casos de prueba:**

| Correo | Válido | Razón |
|--------|--------|-------|
| usuario@ejemplo.com | ✅ Sí | Formato correcto |
| usuario@ejemplo.edu.pe | ✅ Sí | Formato correcto |
| usuario@ejemplo | ❌ No | Falta dominio completo |
| usuario.ejemplo.com | ❌ No | Falta @ |
| @ejemplo.com | ❌ No | Falta usuario |
| | ❌ No | Campo vacío |

**Resultado esperado:**
- Correo válido: Se acepta
- Correo inválido: Muestra error "Formato de correo inválido"

**Estado:** ✅ Pendiente  
**Prioridad:** Alta

---

### CP-004: Validación de Ciclo
**Objetivo:** Verificar que el sistema valida el rango del ciclo académico.

**Casos de prueba:**

| Ciclo | Válido | Razón |
|-------|--------|-------|
| 1 | ✅ Sí | Dentro del rango (1-10) |
| 5 | ✅ Sí | Dentro del rango |
| 10 | ✅ Sí | Límite superior válido |
| 0 | ❌ No | Menor al rango |
| 11 | ❌ No | Mayor al rango |
| -1 | ❌ No | Número negativo |

**Resultado esperado:**
- Ciclo válido (1-10): Se acepta
- Ciclo inválido: Muestra error "Ciclo debe estar entre 1 y 10"

**Estado:** ✅ Pendiente  
**Prioridad:** Media

---

## Pruebas de Validación

### CP-005: Campos Requeridos
**Objetivo:** Verificar que todos los campos obligatorios son validados.

**Campos requeridos:**
- ✅ DNI
- ✅ Nombres
- ✅ Apellidos
- ✅ Correo Electrónico
- ✅ Carrera
- ✅ Ciclo
- ✅ Comentarios

**Pasos:**
1. Intentar enviar el formulario con cada campo vacío
2. Verificar mensaje de error específico

**Resultado esperado:**
- Sistema muestra mensaje de error por cada campo vacío
- No se permite enviar el formulario incompleto

**Estado:** ✅ Pendiente  
**Prioridad:** Alta

---

### CP-006: Sanitización de Datos
**Objetivo:** Verificar que el sistema sanitiza correctamente los datos de entrada.

**Casos de prueba:**

| Entrada | Salida Esperada |
|---------|-----------------|
| `<script>alert('xss')</script>Juan` | `Juan` |
| `María    ` (espacios al final) | `María` |
| `  Pedro` (espacios al inicio) | `Pedro` |
| `<b>Carlos</b>` | `Carlos` |

**Resultado esperado:**
- Todos los scripts HTML son removidos
- Espacios en blanco son eliminados
- Solo texto limpio es guardado

**Estado:** ✅ Pendiente  
**Prioridad:** Alta (Seguridad)

---

## Pruebas de Integración

### CP-007: Flujo Completo de Registro
**Objetivo:** Verificar el flujo completo desde el formulario hasta el correo.

**Pasos:**
1. Completar formulario con datos válidos
2. Enviar formulario
3. Verificar guardado en base de datos
4. Verificar envío de correo
5. Verificar mensaje de éxito en pantalla

**Resultado esperado:**
- ✅ Datos guardados correctamente en BD
- ✅ Correo enviado al estudiante
- ✅ Modal de éxito mostrado
- ✅ Formulario limpiado después del envío

**Estado:** ✅ Pendiente  
**Prioridad:** Crítica

---

### CP-008: Integración con PHPMailer
**Objetivo:** Verificar que los correos se envían correctamente.

**Configuración requerida:**
```php
SMTP_HOST: smtp.gmail.com
SMTP_PORT: 587
SMTP_ENCRYPTION: tls
```

**Pasos:**
1. Registrar una opinión
2. Verificar en bandeja de entrada del correo registrado
3. Revisar contenido del correo

**Resultado esperado:**
- ✅ Correo recibido en menos de 2 minutos
- ✅ Asunto: "Gracias por tu opinión"
- ✅ Contenido incluye nombre del estudiante
- ✅ Formato HTML correcto

**Estado:** ✅ Pendiente  
**Prioridad:** Alta

---

## Pruebas de Seguridad

### CP-009: Prevención de XSS
**Objetivo:** Verificar que el sistema previene ataques XSS.

**Casos de prueba:**
```html
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
<iframe src="javascript:alert('XSS')"></iframe>
```

**Resultado esperado:**
- Ningún script se ejecuta
- Datos son sanitizados antes de guardar
- Datos son escapados al mostrar

**Estado:** ✅ Pendiente  
**Prioridad:** Crítica

---

### CP-010: Prevención de SQL Injection
**Objetivo:** Verificar que el sistema previene inyección SQL.

**Casos de prueba:**
```sql
' OR '1'='1
'; DROP TABLE students; --
' UNION SELECT * FROM users --
```

**Resultado esperado:**
- Consultas son preparadas con PDO
- Ninguna inyección SQL exitosa
- Datos tratados como texto, no como código

**Estado:** ✅ Pendiente  
**Prioridad:** Crítica

---

### CP-011: Content Security Policy
**Objetivo:** Verificar que la política de seguridad de contenido funciona.

**Verificaciones:**
- ✅ CSP permite scripts necesarios
- ✅ CSP bloquea scripts no autorizados
- ✅ Sistema funciona correctamente con CSP activo

**Resultado esperado:**
- Sistema funcional con CSP
- No hay errores en consola del navegador

**Estado:** ✅ Pendiente  
**Prioridad:** Media

---

## Pruebas de Email

### CP-012: Contenido del Correo de Confirmación
**Objetivo:** Verificar que el correo tiene el contenido correcto.

**Verificaciones:**
- ✅ Asunto: "Gracias por tu opinión"
- ✅ Saludo personalizado con nombre del estudiante
- ✅ Mensaje de agradecimiento
- ✅ Firma institucional
- ✅ Formato HTML correcto

**Resultado esperado:**
```
Asunto: Gracias por tu opinión

Estimado(a) [Nombre del estudiante],

Hemos recibido tus comentarios exitosamente. 
Agradecemos sinceramente que te hayas tomado el tiempo 
para compartir tu opinión con nosotros.

Tu perspectiva es muy valiosa y será considerada para 
mejorar la experiencia de nuestros estudiantes.

Atentamente,
Universidad Autónoma del Perú
```

**Estado:** ✅ Pendiente  
**Prioridad:** Media

---

## Matriz de Resultados

| ID | Caso de Prueba | Prioridad | Estado | Fecha | Resultado |
|----|---------------|-----------|--------|-------|-----------|
| CP-001 | Registro Exitoso | Alta | Pendiente | - | - |
| CP-002 | Validación DNI | Alta | Pendiente | - | - |
| CP-003 | Validación Correo | Alta | Pendiente | - | - |
| CP-004 | Validación Ciclo | Media | Pendiente | - | - |
| CP-005 | Campos Requeridos | Alta | Pendiente | - | - |
| CP-006 | Sanitización | Alta | Pendiente | - | - |
| CP-007 | Flujo Completo | Crítica | Pendiente | - | - |
| CP-008 | Integración Email | Alta | Pendiente | - | - |
| CP-009 | Prevención XSS | Crítica | Pendiente | - | - |
| CP-010 | Prevención SQL Injection | Crítica | Pendiente | - | - |
| CP-011 | CSP | Media | Pendiente | - | - |
| CP-012 | Contenido Email | Media | Pendiente | - | - |

---

## Notas Adicionales

### Entorno de Pruebas
- **URL de Desarrollo:** `http://localhost/Proyecto-Ingenieria-REGISTRO/public`
- **URL de Producción:** `http://anakondita.com/Sistema_encuesta/public`
- **Base de Datos:** MySQL 5.7+
- **PHP:** 7.4+

### Responsables
- **Ejecución de Pruebas:** Equipo de QA
- **Corrección de Errores:** Equipo de Desarrollo
- **Aprobación Final:** Product Owner

### Criterios de Aceptación
- ✅ Todas las pruebas críticas pasan
- ✅ Al menos 95% de pruebas de alta prioridad pasan
- ✅ Cero vulnerabilidades de seguridad críticas
- ✅ Sistema funcional en producción
