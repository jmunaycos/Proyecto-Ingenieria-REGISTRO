# Instrucciones de Instalación y Prueba

## ✅ Estado Actual
- ✅ Apache está corriendo (httpd)
- ✅ MySQL está corriendo (mysqld)
- ✅ API REST creada en `apiweb/`
- ✅ Frontend actualizado para usar la API

## 📋 Pasos para Probar el Proyecto

### 1. Verificar la Base de Datos
Abre tu navegador y ve a:
```
http://localhost/phpmyadmin
```

Ejecuta el script SQL que se encuentra en `database.sql` para crear la base de datos y tabla:
1. Selecciona la pestaña "SQL"
2. Copia y pega el contenido de `database.sql`
3. Haz clic en "Continuar" o "Go"

### 2. Probar la API Directamente
Abre tu navegador y prueba estos endpoints:

**Ver todos los usuarios:**
```
http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario
```

Deberías ver un array JSON (vacío si no hay usuarios, o con datos si hay registros).

### 3. Probar el Listado de Usuarios
Abre en tu navegador:
```
http://localhost/Proyecto-Ingenieria-REGISTRO/index.php
```

Deberías ver:
- Una tabla con los usuarios registrados (o vacía si no hay usuarios)
- Un botón "Registrar Nuevo Usuario"
- Una barra de búsqueda por ID

### 4. Probar el Registro de Usuarios
Haz clic en "Registrar Nuevo Usuario" o ve directamente a:
```
http://localhost/Proyecto-Ingenieria-REGISTRO/registro.php
```

Completa el formulario con:
- **DNI:** 8 dígitos (ejemplo: 12345678)
- **Nombres:** Tu nombre
- **Apellidos:** Tus apellidos
- **Correo Electrónico:** Tu correo
- **Correo Destino:** El correo donde quieres recibir la notificación

Haz clic en "Registrar" y deberías:
1. Ver un mensaje de éxito
2. Ser redirigido al listado
3. Ver el nuevo usuario en la tabla

### 5. Probar Edición de Usuarios
En el listado de usuarios (`index.php`):
1. Haz clic en el ícono de lápiz (✏️) de un usuario
2. Se abrirá un modal con los datos del usuario
3. Modifica lo que desees
4. Haz clic en "Guardar Cambios"
5. El usuario debería actualizarse en la tabla

### 6. Probar Eliminación de Usuarios
En el listado de usuarios:
1. Haz clic en el ícono de basura (🗑️) de un usuario
2. Confirma la eliminación
3. El usuario debería desaparecer de la tabla

### 7. Probar Búsqueda por ID
En el listado de usuarios:
1. Ingresa un ID en el campo de búsqueda
2. Haz clic en "Buscar"
3. Deberías ver solo el usuario con ese ID
4. Haz clic en "Mostrar Todos" para ver todos los usuarios nuevamente

## 🔍 Solución de Problemas

### Error "No se pudo conectar con el servidor"
- Verifica que Apache y MySQL estén corriendo en XAMPP
- Revisa que la URL de la API sea correcta en `index.php` línea 84

### Error "Conexión fallida a la base de datos"
- Abre phpMyAdmin: `http://localhost/phpmyadmin`
- Verifica que exista la base de datos `anakonda`
- Verifica que exista la tabla `usuarios`
- Si no existe, ejecuta el script `database.sql`

### La API retorna error 404
- Verifica que la carpeta `apiweb` exista en tu proyecto
- Verifica que la URL sea exactamente:
  ```
  http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario
  ```

### No se envía el correo electrónico
- Revisa que las credenciales de Gmail en `contact.php` sean correctas:
  - Username: línea 97
  - Password: línea 98 (contraseña de aplicación de Gmail)
- Verifica que la autenticación de dos pasos esté habilitada en Gmail
- Verifica que tengas una contraseña de aplicación generada

## 📁 Estructura del Proyecto
```
Proyecto-Ingenieria-REGISTRO/
├── apiweb/                     # API REST
│   ├── config.php              # Configuración de BD
│   ├── public/
│   │   ├── index.php           # Endpoints de la API
│   │   └── .htaccess           # Configuración Apache
│   └── README.md               # Documentación de la API
├── PHPMailer/                  # Librería de correos
├── index.php                   # Listado de usuarios
├── registro.php                # Formulario de registro
├── contact.php                 # Envío de correos
├── principal.css               # Estilos del listado
├── style.css                   # Estilos del formulario
├── database.sql                # Script de creación de BD
└── INSTRUCCIONES.md            # Este archivo
```

## 🎯 URLs Importantes
- **Listado de usuarios:** http://localhost/Proyecto-Ingenieria-REGISTRO/index.php
- **Registro de usuarios:** http://localhost/Proyecto-Ingenieria-REGISTRO/registro.php
- **API REST:** http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario
- **phpMyAdmin:** http://localhost/phpmyadmin

## 📞 Soporte
Si encuentras algún error, revisa:
1. La consola del navegador (F12 > Console)
2. Los logs de Apache en `C:\xampp\apache\logs\error.log`
3. Los logs de MySQL en `C:\xampp\mysql\data\*.err`
