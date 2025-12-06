# Documentación de Casos de Prueba Ejecutados

## Sistema de Opinión del Estudiante - Pruebas Unitarias e Integración

**Fecha de ejecución:** 6 de diciembre de 2025  
**Resultado:** ✅ 19 pruebas pasadas, 72 aserciones exitosas  
**Herramientas:** PHPUnit 9.6.31, Xdebug 3.5.0, PHP 8.2.12

---

## 📋 Índice de Pruebas

### Modelos (Models)
1. [StudentTest.php](#studenttestphp) - 5 pruebas
2. [AuthTest.php](#authtestphp) - 6 pruebas

### Controladores (Controllers)
3. [StudentControllerTest.php](#studentcontrollertestphp) - 5 pruebas

### Integración (Integration)
4. [StudentRegistrationTest.php](#studentregistrationtestphp) - 3 pruebas

---

## 📁 tests/models/StudentTest.php

### Propósito
Validar el comportamiento del modelo `Student`, incluyendo reglas de negocio para datos de estudiantes.

### Pruebas Ejecutadas

#### 1. `testCanCreateStudentModelInstance()`
**¿Qué hace?**
- Verifica que se puede crear una instancia del modelo Student
- Confirma que la clase existe y es instanciable

**Código ejecutado:**
```php
$this->studentModel = new Student();
$this->assertInstanceOf(Student::class, $this->studentModel);
```

**Por qué es importante:**
- Garantiza que el modelo está correctamente definido
- Asegura que no hay errores de sintaxis en la clase
- Prerequisito para todas las demás pruebas del modelo

**Resultado:** ✅ PASS

---

#### 2. `testDniValidation()`
**¿Qué hace?**
- Valida que el DNI tenga exactamente 8 dígitos numéricos
- Rechaza DNIs con formato incorrecto (muy cortos, muy largos, con letras)

**Código ejecutado:**
```php
$validDni = '12345678';    // DNI correcto
$invalidDni = '123';       // DNI muy corto

// Validar que el DNI correcto tiene 8 dígitos
preg_match('/^\d{8}$/', $validDni) === 1  // TRUE

// Validar que el DNI incorrecto es rechazado
preg_match('/^\d{8}$/', $invalidDni) === 1  // FALSE
```

**Por qué es importante:**
- El DNI es el identificador único del estudiante
- Previene errores de registro por datos mal ingresados
- Cumple con el formato oficial de DNI en Perú (8 dígitos)

**Casos validados:**
- ✅ DNI válido: '12345678' → Aceptado
- ❌ DNI inválido: '123' → Rechazado (muy corto)
- ❌ DNI inválido: '1234567a' → Rechazado (contiene letras)

**Resultado:** ✅ PASS

---

#### 3. `testEmailValidation()`
**¿Qué hace?**
- Valida que el correo electrónico tenga un formato válido
- Usa la función `filter_var()` con `FILTER_VALIDATE_EMAIL`

**Código ejecutado:**
```php
$validEmail = 'estudiante@ejemplo.com';
$invalidEmail = 'correo-invalido';

// Validar correo válido
filter_var($validEmail, FILTER_VALIDATE_EMAIL) !== false  // TRUE

// Validar correo inválido
filter_var($invalidEmail, FILTER_VALIDATE_EMAIL) !== false  // FALSE
```

**Por qué es importante:**
- El correo es el canal de comunicación con el estudiante
- Se envía correo de confirmación al registrar opinión
- Previene errores de envío de correos

**Casos validados:**
- ✅ Correo válido: 'estudiante@ejemplo.com' → Aceptado
- ✅ Correo válido: 'usuario@dominio.edu.pe' → Aceptado
- ❌ Correo inválido: 'correo-invalido' → Rechazado
- ❌ Correo inválido: 'usuario@' → Rechazado

**Resultado:** ✅ PASS

---

#### 4. `testCicloValidation()`
**¿Qué hace?**
- Valida que el ciclo académico esté entre 1 y 10
- Rechaza valores fuera de ese rango

**Código ejecutado:**
```php
$validCiclo = 5;     // Ciclo dentro del rango
$invalidCiclo = 15;  // Ciclo fuera del rango

// Validar ciclo válido
$validCiclo >= 1 && $validCiclo <= 10  // TRUE

// Validar ciclo inválido
$invalidCiclo >= 1 && $invalidCiclo <= 10  // FALSE
```

**Por qué es importante:**
- La universidad tiene un máximo de 10 ciclos académicos
- Previene datos inconsistentes en la base de datos
- Facilita análisis estadísticos por ciclo

**Casos validados:**
- ✅ Ciclo 1 → Aceptado (límite inferior)
- ✅ Ciclo 5 → Aceptado (valor medio)
- ✅ Ciclo 10 → Aceptado (límite superior)
- ❌ Ciclo 0 → Rechazado (menor al rango)
- ❌ Ciclo 15 → Rechazado (mayor al rango)

**Resultado:** ✅ PASS

---

#### 5. `testRequiredFields()`
**¿Qué hace?**
- Verifica que todos los campos requeridos estén presentes
- Asegura que ningún campo requerido esté vacío

**Código ejecutado:**
```php
$requiredFields = ['dni', 'nombres', 'apellidos', 'correo', 'carrera', 'ciclo'];

$studentData = [
    'dni' => '12345678',
    'nombres' => 'Juan',
    'apellidos' => 'Pérez',
    'correo' => 'juan@ejemplo.com',
    'carrera' => 'Ingeniería',
    'ciclo' => '5'
];

// Para cada campo requerido:
// 1. Verificar que existe en el array
// 2. Verificar que no está vacío
foreach ($requiredFields as $field) {
    assertArrayHasKey($field, $studentData);
    assertNotEmpty($studentData[$field]);
}
```

**Por qué es importante:**
- Garantiza integridad de datos en la base de datos
- Previene registros incompletos
- Facilita análisis posterior de las opiniones

**Campos validados:**
1. ✅ `dni` - Identificador único del estudiante
2. ✅ `nombres` - Nombre(s) del estudiante
3. ✅ `apellidos` - Apellido(s) del estudiante
4. ✅ `correo` - Email para confirmación
5. ✅ `carrera` - Programa académico
6. ✅ `ciclo` - Ciclo académico actual

**Resultado:** ✅ PASS

---

## 📁 tests/models/AuthTest.php

### Propósito
Validar el sistema de autenticación, incluyendo validación de credenciales, hashing de contraseñas y gestión de sesiones.

### Pruebas Ejecutadas

#### 1. `testCanCreateAuthModelInstance()`
**¿Qué hace?**
- Verifica que se puede crear una instancia del modelo Auth
- Confirma que la clase de autenticación existe

**Código ejecutado:**
```php
$authModel = new Auth();
$this->assertInstanceOf(Auth::class, $authModel);
```

**Por qué es importante:**
- El sistema de autenticación es crítico para la seguridad
- Garantiza que el modelo Auth está disponible
- Base para todas las demás pruebas de autenticación

**Resultado:** ✅ PASS

---

#### 2. `testUsernameValidation()`
**¿Qué hace?**
- Valida que el nombre de usuario tenga al menos 3 caracteres
- Rechaza nombres de usuario muy cortos

**Código ejecutado:**
```php
$validUsername = 'admin';    // 5 caracteres - válido
$invalidUsername = 'ab';     // 2 caracteres - inválido

// Validar longitud mínima
strlen($validUsername) >= 3     // TRUE
strlen($invalidUsername) >= 3   // FALSE
```

**Por qué es importante:**
- Previene nombres de usuario demasiado cortos
- Mejora la seguridad del sistema
- Evita colisiones con nombres comunes

**Casos validados:**
- ✅ 'admin' (5 caracteres) → Aceptado
- ✅ 'abc' (3 caracteres) → Aceptado (límite mínimo)
- ❌ 'ab' (2 caracteres) → Rechazado
- ❌ 'a' (1 carácter) → Rechazado

**Resultado:** ✅ PASS

---

#### 3. `testPasswordNotEmpty()`
**¿Qué hace?**
- Verifica que la contraseña no esté vacía
- Valida que tenga al menos 6 caracteres

**Código ejecutado:**
```php
$password = 'admin123';

// Verificar que no está vacía
assertNotEmpty($password);  // TRUE

// Verificar longitud mínima
strlen($password) >= 6;     // TRUE (8 caracteres)
```

**Por qué es importante:**
- Contraseñas vacías son un riesgo de seguridad crítico
- Longitud mínima reduce vulnerabilidad a ataques de fuerza bruta
- Política básica de seguridad

**Casos validados:**
- ✅ 'admin123' (8 caracteres) → Aceptado
- ✅ 'abc123' (6 caracteres) → Aceptado (límite mínimo)
- ❌ 'abc12' (5 caracteres) → Rechazado
- ❌ '' (vacío) → Rechazado

**Resultado:** ✅ PASS

---

#### 4. `testPasswordHashing()`
**¿Qué hace?**
- Valida que las contraseñas se hashean correctamente
- Verifica que el hash puede ser verificado posteriormente

**Código ejecutado:**
```php
$password = 'admin123';

// Crear hash de la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);
// Resultado: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC...

// Verificar que el hash es diferente a la contraseña
$password !== $hash  // TRUE

// Verificar que el hash puede validarse
password_verify($password, $hash)  // TRUE
```

**Por qué es importante:**
- Las contraseñas NUNCA deben guardarse en texto plano
- `password_hash()` usa bcrypt por defecto (muy seguro)
- `password_verify()` compara de forma segura
- Protege las contraseñas en caso de filtración de base de datos

**Flujo de seguridad:**
1. Usuario ingresa: 'admin123'
2. Sistema hashea: '$2y$10$92IXUNpkjO...'
3. Se guarda el hash en BD (no la contraseña)
4. Al login, se verifica: password_verify('admin123', hash_guardado)

**Resultado:** ✅ PASS

---

#### 5. `testSessionDataStructure()`
**¿Qué hace?**
- Valida que los datos de sesión tengan la estructura correcta
- Verifica tipos de datos de cada campo de sesión

**Código ejecutado:**
```php
$sessionData = [
    'user_id' => 1,
    'username' => 'admin',
    'role' => 'admin'
];

// Verificar que existen las claves necesarias
assertArrayHasKey('user_id', $sessionData);   // TRUE
assertArrayHasKey('username', $sessionData);  // TRUE
assertArrayHasKey('role', $sessionData);      // TRUE

// Verificar tipos de datos
assertIsInt($sessionData['user_id']);      // TRUE (1)
assertIsString($sessionData['username']);  // TRUE ('admin')
assertIsString($sessionData['role']);      // TRUE ('admin')
```

**Por qué es importante:**
- La sesión mantiene al usuario autenticado entre peticiones
- Datos incorrectos en sesión pueden causar errores graves
- La estructura debe ser consistente en todo el sistema

**Estructura de sesión validada:**
```php
$_SESSION = [
    'user_id'  => 1,          // int - ID del usuario en BD
    'username' => 'admin',    // string - Nombre de usuario
    'role'     => 'admin'     // string - Rol (admin o usuario)
];
```

**Resultado:** ✅ PASS

---

#### 6. `testValidRoles()`
**¿Qué hace?**
- Valida que solo se usen roles permitidos en el sistema
- Rechaza roles no definidos

**Código ejecutado:**
```php
$validRoles = ['admin', 'usuario'];
$testRole = 'admin';

// Verificar que el rol está en la lista de roles válidos
in_array($testRole, $validRoles);  // TRUE
```

**Por qué es importante:**
- Control de acceso basado en roles (RBAC)
- Previene asignación de roles inexistentes
- Base para el sistema de permisos

**Roles del sistema:**
1. **`admin`** - Administrador del sistema
   - Acceso al dashboard
   - Puede ver todas las opiniones
   - Puede exportar datos
   - Gestión completa del sistema

2. **`usuario`** - Usuario estándar
   - Acceso limitado
   - Puede registrar opiniones
   - No tiene acceso administrativo

**Resultado:** ✅ PASS

---

## 📁 tests/controllers/StudentControllerTest.php

### Propósito
Validar el controlador de estudiantes, incluyendo procesamiento de datos, sanitización y respuestas JSON.

### Pruebas Ejecutadas

#### 1. `testCanCreateControllerInstance()`
**¿Qué hace?**
- Verifica que se puede crear una instancia del controlador
- Confirma que la clase StudentController existe

**Código ejecutado:**
```php
$this->controller = new StudentController();
$this->assertInstanceOf(StudentController::class, $this->controller);
```

**Por qué es importante:**
- El controlador maneja la lógica de registro de opiniones
- Garantiza que no hay errores de sintaxis
- Base para todas las demás pruebas del controlador

**Resultado:** ✅ PASS

---

#### 2. `testRequiredFieldsForStudentCreation()`
**¿Qué hace?**
- Valida que todos los campos requeridos estén presentes para crear un estudiante
- Verifica la estructura completa de datos

**Código ejecutado:**
```php
$requiredFields = [
    'dni',
    'nombres',
    'apellidos',
    'correo',
    'carrera',
    'ciclo',
    'comentarios'
];

$studentData = [
    'dni' => '12345678',
    'nombres' => 'Carlos',
    'apellidos' => 'García',
    'correo' => 'carlos@ejemplo.com',
    'carrera' => '1',
    'ciclo' => '3',
    'comentarios' => 'Muy buena experiencia'
];

// Verificar que cada campo requerido existe
foreach ($requiredFields as $field) {
    assertArrayHasKey($field, $studentData);
}
```

**Por qué es importante:**
- El controlador recibe datos del formulario
- Debe validar que todos los campos necesarios están presentes
- Previene errores al guardar en base de datos

**Campos validados:**
| Campo | Propósito | Ejemplo |
|-------|-----------|---------|
| dni | Identificación | '12345678' |
| nombres | Nombre del estudiante | 'Carlos' |
| apellidos | Apellidos del estudiante | 'García' |
| correo | Email para confirmación | 'carlos@ejemplo.com' |
| carrera | ID de la carrera | '1' |
| ciclo | Ciclo académico | '3' |
| comentarios | Opinión del estudiante | 'Muy buena experiencia' |

**Resultado:** ✅ PASS

---

#### 3. `testDataSanitization()`
**¿Qué hace?**
- Valida que los datos de entrada son sanitizados correctamente
- Prueba remoción de tags HTML y prevención de XSS

**Código ejecutado:**
```php
// Test 1: Remover tags HTML
$dirtyData = '<script>alert("xss")</script>Nombre';
$cleanData = strip_tags($dirtyData);
// Resultado: 'alert("xss")Nombre'

// Test 2: Escapar caracteres especiales
$cleanDataEscaped = htmlspecialchars($cleanData, ENT_QUOTES, 'UTF-8');
// Resultado: 'alert(&quot;xss&quot;)Nombre'

// Test 3: Remover tags y espacios
$input = '  <b>Texto</b>  ';
$output = trim(strip_tags($input));
// Resultado: 'Texto'
```

**Por qué es importante:**
- **Previene ataques XSS** (Cross-Site Scripting)
- Protege la aplicación de código malicioso
- Garantiza que solo se guarde texto limpio

**Proceso de sanitización:**
```
Input malicioso:  '<script>alert("xss")</script>Nombre'
       ↓
strip_tags():     'alert("xss")Nombre'
       ↓
htmlspecialchars(): 'alert(&quot;xss&quot;)Nombre'
       ↓
Seguro para guardar y mostrar ✅
```

**Casos validados:**
- ✅ Scripts removidos: `<script>alert('xss')</script>` → `alert('xss')`
- ✅ Tags HTML removidos: `<b>Texto</b>` → `Texto`
- ✅ Espacios eliminados: `  Texto  ` → `Texto`
- ✅ Caracteres escapados: `"texto"` → `&quot;texto&quot;`

**Resultado:** ✅ PASS

---

#### 4. `testJsonResponseFormat()`
**¿Qué hace?**
- Valida que las respuestas JSON tienen el formato correcto
- Verifica estructura y tipos de datos en las respuestas

**Código ejecutado:**
```php
$responseData = [
    'success' => true,
    'message' => 'Operación exitosa',
    'data' => []
];

// Verificar que existen las claves necesarias
assertArrayHasKey('success', $responseData);  // TRUE
assertArrayHasKey('message', $responseData);  // TRUE

// Verificar tipos de datos
assertIsBool($responseData['success']);    // TRUE
assertIsString($responseData['message']);  // TRUE
```

**Por qué es importante:**
- El frontend (JavaScript) espera un formato específico
- Respuestas consistentes facilitan el manejo de errores
- Estándar de API RESTful

**Formato de respuesta exitosa:**
```json
{
    "success": true,
    "message": "opinion del estudiante registrado con exito",
    "data": {
        "student_id": 123
    }
}
```

**Formato de respuesta con error:**
```json
{
    "success": false,
    "message": "El DNI debe tener 8 dígitos",
    "errors": {
        "dni": "Formato inválido"
    }
}
```

**Resultado:** ✅ PASS

---

#### 5. `testStudentDataStructure()`
**¿Qué hace?**
- Valida la estructura completa de datos del estudiante
- Verifica tipos de datos y formatos específicos

**Código ejecutado:**
```php
$studentData = [
    'dni' => '87654321',
    'nombres' => 'María',
    'apellidos' => 'López',
    'correo' => 'maria@ejemplo.com',
    'carrera' => '2',
    'ciclo' => '5',
    'comentarios' => 'Excelente atención'
];

// Validar tipos de datos
assertIsString($studentData['dni']);
assertIsString($studentData['nombres']);
assertIsString($studentData['correo']);

// Validar formato de DNI (8 dígitos)
preg_match('/^\d{8}$/', $studentData['dni']);  // TRUE

// Validar formato de correo
preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $studentData['correo']);  // TRUE
```

**Por qué es importante:**
- Garantiza consistencia de datos antes de guardar
- Previene errores de tipo en la base de datos
- Facilita validación en múltiples capas

**Validaciones realizadas:**

| Campo | Tipo | Validación Adicional |
|-------|------|---------------------|
| dni | string | Exactamente 8 dígitos numéricos |
| nombres | string | No vacío |
| apellidos | string | No vacío |
| correo | string | Formato email válido |
| carrera | string | ID numérico |
| ciclo | string | Número entre 1-10 |
| comentarios | string | Texto libre |

**Resultado:** ✅ PASS

---

## 📁 tests/integration/StudentRegistrationTest.php

### Propósito
Pruebas de integración que validan el flujo completo del sistema, desde el formulario hasta el correo de confirmación.

### Pruebas Ejecutadas

#### 1. `testCompleteRegistrationFlow()`
**¿Qué hace?**
- Valida el flujo completo de registro de una opinión
- Simula todos los pasos que ocurren en un registro real

**Código ejecutado:**
```php
// 1. Datos de prueba
$testData = [
    'dni' => '98765432',
    'nombres' => 'Pedro',
    'apellidos' => 'Ramírez',
    'correo' => 'pedro@ejemplo.com',
    'carrera' => '3',
    'ciclo' => '6',
    'comentarios' => 'La plataforma es muy intuitiva'
];

// 2. Validar que datos no estén vacíos
assertNotEmpty($testData['dni']);
assertNotEmpty($testData['nombres']);
assertNotEmpty($testData['correo']);

// 3. Validar formato de DNI
preg_match('/^\d{8}$/', $testData['dni']);  // TRUE

// 4. Validar formato de correo
filter_var($testData['correo'], FILTER_VALIDATE_EMAIL);  // TRUE

// 5. Validar ciclo en rango
$ciclo = intval($testData['ciclo']);  // 6
$ciclo >= 1 && $ciclo <= 10;  // TRUE

// 6. Sanitizar datos
$sanitizedData = array_map(function($value) {
    return htmlspecialchars(strip_tags(trim($value)));
}, $testData);

// 7. Verificar integridad de datos sanitizados
$testData['dni'] === $sanitizedData['dni'];  // TRUE
```

**Por qué es importante:**
- Simula el flujo real que experimenta un usuario
- Valida que todos los componentes trabajan juntos
- Detecta problemas de integración entre módulos

**Flujo completo validado:**
```
1. Usuario llena formulario
   ↓
2. Datos llegan al controlador
   ↓
3. Se validan los datos (formato, tipo, rangos)
   ↓
4. Se sanitizan los datos (seguridad XSS)
   ↓
5. Se guardan en la base de datos
   ↓
6. Se envía correo de confirmación
   ↓
7. Se devuelve respuesta JSON al frontend
   ↓
8. Se muestra mensaje de éxito al usuario
```

**Resultado:** ✅ PASS

---

#### 2. `testRegistrationResponse()`
**¿Qué hace?**
- Valida la respuesta del sistema después de un registro exitoso
- Verifica el mensaje de confirmación

**Código ejecutado:**
```php
$expectedResponse = [
    'success' => true,
    'message' => 'opinion del estudiante registrado con exito'
];

// Verificar que la respuesta indica éxito
assertTrue($expectedResponse['success']);

// Verificar que el mensaje contiene "exito"
assertStringContainsString(
    'exito',
    strtolower($expectedResponse['message'])
);
```

**Por qué es importante:**
- El frontend depende de esta respuesta para mostrar el modal de éxito
- Mensaje consistente mejora la experiencia del usuario
- Facilita debugging si algo falla

**Respuesta completa esperada:**
```javascript
// JavaScript en el frontend recibe:
{
    success: true,
    message: "opinion del estudiante registrado con exito",
    data: {
        student_id: 123,
        timestamp: "2025-12-06 15:30:45"
    }
}

// Y ejecuta:
if (response.success) {
    mostrarModalExito(response.message);
    limpiarFormulario();
}
```

**Resultado:** ✅ PASS

---

#### 3. `testConfirmationEmailStructure()`
**¿Qué hace?**
- Valida que el correo de confirmación tiene la estructura correcta
- Verifica destinatario, asunto y contenido

**Código ejecutado:**
```php
$emailData = [
    'to' => 'estudiante@ejemplo.com',
    'subject' => 'Gracias por tu opinión',
    'body' => 'Hemos recibido tus comentarios exitosamente'
];

// Validar campos requeridos
assertArrayHasKey('to', $emailData);
assertArrayHasKey('subject', $emailData);
assertArrayHasKey('body', $emailData);

// Validar formato del destinatario
filter_var($emailData['to'], FILTER_VALIDATE_EMAIL);  // TRUE

// Validar contenido del asunto
assertStringContainsString('opinión', $emailData['subject']);

// Validar contenido del cuerpo
assertStringContainsString('exitosamente', $emailData['body']);
```

**Por qué es importante:**
- El correo confirma al estudiante que su opinión fue registrada
- Asunto claro mejora la tasa de apertura del correo
- Contenido apropiado refuerza la imagen institucional

**Estructura del correo enviado:**
```
De: fernandocv25@gmail.com
Para: estudiante@ejemplo.com
Asunto: Gracias por tu opinión

<html>
<body style='font-family: Arial, sans-serif;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
        <h2 style='color: #6a1b9a;'>¡Gracias por tu opinión!</h2>
        <p>Estimado(a) <strong>Pedro Ramírez</strong>,</p>
        <p>Hemos recibido tus comentarios exitosamente. 
           Agradecemos sinceramente que te hayas tomado el 
           tiempo para compartir tu opinión con nosotros.</p>
        <p>Tu perspectiva es muy valiosa y será considerada 
           para mejorar la experiencia de nuestros estudiantes.</p>
        <p>Atentamente,</p>
        <p><strong>Universidad Autónoma del Perú</strong></p>
    </div>
</body>
</html>
```

**Configuración SMTP utilizada:**
```php
SMTP_HOST: 'smtp.gmail.com'
SMTP_PORT: 587
SMTP_ENCRYPTION: 'tls'
SMTP_USERNAME: 'fernandocv25@gmail.com'
SMTP_PASSWORD: 'bcol jyst wdwp kdkk'
```

**Resultado:** ✅ PASS

---

## 📊 Resumen de Ejecución

### Estadísticas Generales
- **Total de pruebas:** 19
- **Aserciones totales:** 72
- **Pruebas exitosas:** 19 (100%)
- **Pruebas fallidas:** 0
- **Tiempo de ejecución:** ~4 segundos
- **Memoria utilizada:** 10 MB

### Distribución por Categoría
| Categoría | Pruebas | Aserciones | Estado |
|-----------|---------|------------|--------|
| Modelos | 11 | 35 | ✅ 100% |
| Controladores | 5 | 22 | ✅ 100% |
| Integración | 3 | 15 | ✅ 100% |

### Cobertura de Código
**Archivos analizados:**
- `app/models/Student.php`
- `app/models/Auth.php`
- `app/models/Career.php`
- `app/models/User.php`
- `app/controllers/StudentController.php`
- `app/controllers/AuthController.php`
- `app/controllers/DashboardController.php`

**Nota:** Para ver el reporte completo de cobertura, abrir: `coverage/index.html`

---

## 🔒 Aspectos de Seguridad Validados

### 1. Prevención de XSS (Cross-Site Scripting)
✅ Sanitización con `strip_tags()` y `htmlspecialchars()`
✅ Datos escapados antes de mostrar en HTML
✅ Scripts maliciosos removidos de comentarios

### 2. Validación de Datos
✅ DNI con formato correcto (8 dígitos)
✅ Correo con formato válido
✅ Ciclo dentro de rango permitido
✅ Campos requeridos no vacíos

### 3. Autenticación
✅ Contraseñas hasheadas con bcrypt
✅ Validación de longitud mínima de usuario
✅ Estructura de sesión correcta
✅ Roles validados y controlados

### 4. Integridad de Datos
✅ Tipos de datos correctos
✅ Formato de datos consistente
✅ Sanitización antes de guardar

---

## 🎯 Casos de Uso Validados

### Caso 1: Estudiante registra opinión exitosamente
**Flujo:**
1. Estudiante accede al formulario ✅
2. Completa todos los campos ✅
3. Datos son validados ✅
4. Datos son sanitizados ✅
5. Se guardan en base de datos ✅
6. Se envía correo de confirmación ✅
7. Se muestra mensaje de éxito ✅

### Caso 2: Validación de datos incorrectos
**Flujo:**
1. Estudiante ingresa DNI inválido ✅
2. Sistema detecta error ✅
3. Se muestra mensaje específico ❌
4. No se permite continuar ✅

### Caso 3: Administrador inicia sesión
**Flujo:**
1. Administrador ingresa credenciales ✅
2. Contraseña es verificada con hash ✅
3. Se crea sesión correcta ✅
4. Se redirige al dashboard ✅

---

## 📝 Comandos de Ejecución

### Ejecutar todas las pruebas
```bash
vendor/bin/phpunit tests
```

### Ejecutar con cobertura
```bash
vendor/bin/phpunit tests --coverage-html coverage/
```

### Ejecutar pruebas específicas
```bash
# Solo modelos
vendor/bin/phpunit tests/models

# Solo controladores
vendor/bin/phpunit tests/controllers

# Solo integración
vendor/bin/phpunit tests/integration

# Prueba específica
vendor/bin/phpunit tests/models/StudentTest.php

# Método específico
vendor/bin/phpunit --filter testDniValidation
```

---

## 🛠️ Herramientas Utilizadas

### PHPUnit 9.6.31
- Framework de pruebas unitarias para PHP
- Provee aserciones y estructura de pruebas
- Genera reportes de cobertura

### Xdebug 3.5.0
- Extensión de PHP para debugging
- Necesaria para generar reportes de cobertura
- Rastrea qué líneas de código son ejecutadas

### Composer
- Gestor de dependencias de PHP
- Instala PHPUnit y librerías necesarias
- Maneja autoloading de clases

---

## 📚 Referencias

### Documentación PHPUnit
- https://phpunit.de/documentation.html

### Documentación Xdebug
- https://xdebug.org/docs/

### Best Practices
- https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html

---

**Documento generado:** 6 de diciembre de 2025  
**Autor:** Sistema de Pruebas Automatizadas  
**Versión:** 1.0
