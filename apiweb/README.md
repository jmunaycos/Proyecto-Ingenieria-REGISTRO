# API REST - Sistema de Gestión de Usuarios

API RESTful para la gestión de usuarios del proyecto Anakonda.

## Configuración

### Base de datos
La configuración de la base de datos se encuentra en `apiweb/config.php`:
- Host: `localhost`
- Usuario: `root`
- Contraseña: `` (vacía por defecto)
- Base de datos: `anakonda`

### Estructura de la tabla usuarios
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(8) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Endpoints

Base URL: `http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario`

### 1. Listar todos los usuarios
**GET** `/index.php?resource=usuario`

**Respuesta exitosa (200):**
```json
[
    {
        "id": 1,
        "dni": "12345678",
        "nombres": "Juan",
        "apellidos": "Pérez",
        "correo": "juan@example.com"
    }
]
```

### 2. Obtener un usuario por ID
**GET** `/index.php?resource=usuario&id=1`

**Respuesta exitosa (200):**
```json
{
    "id": 1,
    "dni": "12345678",
    "nombres": "Juan",
    "apellidos": "Pérez",
    "correo": "juan@example.com"
}
```

**Respuesta de error (404):**
```json
{
    "error": true,
    "message": "Usuario no encontrado"
}
```

### 3. Crear un nuevo usuario
**POST** `/index.php?resource=usuario`

**Body (JSON):**
```json
{
    "dni": "87654321",
    "nombres": "María",
    "apellidos": "García",
    "correo": "maria@example.com"
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "id": 2
}
```

**Validaciones:**
- El DNI debe tener exactamente 8 dígitos
- El correo debe tener formato válido
- No se pueden duplicar DNIs

### 4. Actualizar un usuario
**PUT** `/index.php?resource=usuario&id=1`

**Body (application/x-www-form-urlencoded o JSON):**
```json
{
    "dni": "12345678",
    "nombres": "Juan Carlos",
    "apellidos": "Pérez López",
    "correo": "juancarlos@example.com"
}
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Usuario actualizado exitosamente"
}
```

### 5. Eliminar un usuario
**DELETE** `/index.php?resource=usuario&id=1`

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Usuario eliminado exitosamente"
}
```

## Códigos de respuesta HTTP

- `200 OK`: Solicitud exitosa
- `201 Created`: Recurso creado exitosamente
- `400 Bad Request`: Datos inválidos o incompletos
- `404 Not Found`: Recurso no encontrado
- `409 Conflict`: Conflicto (ej: DNI duplicado)
- `500 Internal Server Error`: Error del servidor

## CORS

La API está configurada para aceptar peticiones desde cualquier origen:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`

## Uso desde el Frontend

### JavaScript Fetch
```javascript
const API_URL = 'http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario';

// GET - Listar todos
const usuarios = await fetch(API_URL).then(r => r.json());

// GET - Obtener uno
const usuario = await fetch(`${API_URL}&id=1`).then(r => r.json());

// POST - Crear
const response = await fetch(API_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        dni: '12345678',
        nombres: 'Juan',
        apellidos: 'Pérez',
        correo: 'juan@example.com'
    })
});

// PUT - Actualizar
await fetch(`${API_URL}&id=1`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
        dni: '12345678',
        nombres: 'Juan Carlos',
        apellidos: 'Pérez',
        correo: 'juan@example.com'
    })
});

// DELETE - Eliminar
await fetch(`${API_URL}&id=1`, { method: 'DELETE' });
```

## Notas importantes

1. **XAMPP debe estar ejecutándose** con Apache y MySQL activos
2. La base de datos `anakonda` debe existir con la tabla `usuarios`
3. El módulo `mod_rewrite` de Apache debe estar habilitado
4. La extensión `mysqli` de PHP debe estar habilitada
