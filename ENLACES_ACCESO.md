# 🔗 ENLACES DE ACCESO AL SISTEMA MVC (SIN .htaccess)

## ⚠️ IMPORTANTE: USA `index.php` EN TODAS LAS URLs

El `.htaccess` fue eliminado para evitar bucles. Ahora DEBES usar `index.php` explícitamente.

---

## ✅ ENLACES CORRECTOS

### 1️⃣ Login Principal (EMPIEZA AQUÍ)
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php
```

### 2️⃣ Limpiar Cookies (si tienes problemas)
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/limpiar.html
```

### 3️⃣ Diagnóstico del Sistema
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/diagnostico.php
```

---

## 🎯 Rutas Después de Login

Una vez iniciada sesión, usa estas URLs:

| Función | URL Correcta |
|---------|--------------|
| **Dashboard** | `index.php?route=dashboard` |
| **Estudiantes** | `index.php?route=students` |
| **Registro** | `index.php?route=registro` |
| **Logout** | `index.php?route=logout` |

### URLs Completas:

```
Dashboard:
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php?route=dashboard

Estudiantes:
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php?route=students

Registro:
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php?route=registro

Cerrar Sesión:
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php?route=logout
```

---

## 👤 Credenciales

```
Administrador:
Usuario: admin
Contraseña: admin123

Usuario Normal:
Usuario: usuario
Contraseña: user123
```

---

## ❌ URLs QUE NO FUNCIONARÁN

Estas URLs darán error 404 porque no existe `.htaccess`:

```
❌ http://localhost/Proyecto-Ingenieria-REGISTRO/public/
❌ http://localhost/Proyecto-Ingenieria-REGISTRO/public/dashboard
❌ http://localhost/Proyecto-Ingenieria-REGISTRO/public/login
❌ http://localhost/Proyecto-Ingenieria-REGISTRO/public/students
```

---

## 🚀 PASOS PARA ACCEDER

### Paso 1: Abre el navegador en modo incógnito
Presiona `Ctrl + Shift + N` (Chrome) o `Ctrl + Shift + P` (Firefox)

### Paso 2: Ve al login
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php
```

### Paso 3: Inicia sesión
```
Usuario: admin
Contraseña: admin123
```

### Paso 4: Accede al dashboard usando el menú
O ve directamente a:
```
http://localhost/Proyecto-Ingenieria-REGISTRO/public/index.php?route=dashboard
```

---

## 🔧 El Navbar Ya Está Actualizado

Los enlaces del menú de navegación ya usan las URLs correctas. Una vez que inicies sesión, puedes hacer clic en:
- **Dashboard** (te lleva a `index.php?route=dashboard`)
- **Estudiantes** (te lleva a `index.php?route=students`)
- **Registro** (te lleva a `index.php?route=registro`)

---

## 📝 ¿Por Qué Sin .htaccess?

El `.htaccess` estaba causando bucles de redirección infinitos (ERR_TOO_MANY_REDIRECTS). 

**Solución temporal**: Usar URLs con `index.php?route=` hasta configurar correctamente Apache.

**Solución futura**: Configurar Apache con VirtualHost o DocumentRoot apuntando a `/public/`.

---

*Última actualización: 3 de diciembre de 2025 - Modo sin .htaccess activo*
