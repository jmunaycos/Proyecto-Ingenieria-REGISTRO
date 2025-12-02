<?php
require_once 'auth.php';
requireAuth();
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario - REGISTRO</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="principal.css">
</head>
<body>

  <!-- Barra de navegación -->
  <nav class="navbar">
      <div class="nav-content">
          <div class="nav-left">
              <span class="logo"><i class="fas fa-users-cog"></i> REGISTRO</span>
          </div>
          <div class="nav-right">
              <?php if (isAdmin()): ?>
              <a href="dashboard.php" class="nav-link">
                  <i class="fas fa-chart-line"></i>
                  <span>Dashboard</span>
              </a>
              <a href="index.php" class="nav-link">
                  <i class="fas fa-users"></i>
                  <span>Usuarios</span>
              </a>
              <?php endif; ?>
              <a href="registro.php" class="nav-link active">
                  <i class="fas fa-user-plus"></i>
                  <span>Registrar</span>
              </a>
              <span class="user-info">
                  <i class="fas fa-user-circle"></i>
                  <?php echo htmlspecialchars($currentUser['nombre_completo']); ?>
                  <?php if (isAdmin()): ?>
                      <span class="badge-admin">Admin</span>
                  <?php endif; ?>
              </span>
              <a href="logout.php" class="nav-link logout">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Salir</span>
              </a>
          </div>
      </div>
  </nav>

  <div class="form-container">
    <div class="form-header">
      <h2>Registrar Usuario</h2>
      <p>Complete el formulario para agregar un nuevo usuario</p>
    </div>

    <form id="formRegistro">
      <div class="form-group">
        <label for="dni"><i class="fas fa-id-card"></i> DNI</label>
        <input type="text" id="dni" name="dni" maxlength="8" inputmode="numeric" pattern="\d{8}" placeholder="Ej: 12345678" title="Debe ingresar 8 números" required>
      </div>

      <div class="form-group">
        <label for="nombres"><i class="fas fa-user"></i> Nombres</label>
        <input type="text" id="nombres" name="nombres" placeholder="Ingrese sus nombres" required>
      </div>
      
      <div class="form-group">
        <label for="apellidos"><i class="fas fa-user-tag"></i> Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese sus apellidos" required>
      </div>

      <div class="form-group">
        <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico</label>
        <input type="email" id="correo" name="correo" placeholder="ejemplo@universidad.edu" required>
      </div>

      <div class="form-group">
        <label for="carrera"><i class="fas fa-graduation-cap"></i> Carrera</label>
        <select id="carrera" name="carrera" required>
          <option value="">Seleccione una carrera...</option>
        </select>
      </div>

      <div class="form-group">
        <label for="ciclo"><i class="fas fa-layer-group"></i> Ciclo</label>
        <select id="ciclo" name="ciclo" required>
          <option value="">Seleccione el ciclo...</option>
          <option value="Ciclo 1">Ciclo 1</option>
          <option value="Ciclo 2">Ciclo 2</option>
          <option value="Ciclo 3">Ciclo 3</option>
          <option value="Ciclo 4">Ciclo 4</option>
          <option value="Ciclo 5">Ciclo 5</option>
          <option value="Ciclo 6">Ciclo 6</option>
          <option value="Ciclo 7">Ciclo 7</option>
          <option value="Ciclo 8">Ciclo 8</option>
          <option value="Ciclo 9">Ciclo 9</option>
          <option value="Ciclo 10">Ciclo 10</option>
        </select>
      </div>

      <div class="form-group">
        <label for="comentarios"><i class="fas fa-comment-dots"></i> Comentarios (Opcional)</label>
        <textarea id="comentarios" name="comentarios" rows="4" placeholder="Ingrese sus comentarios o sugerencias..."></textarea>
      </div>

      <button type="submit"><i class="fas fa-save"></i> Registrar Usuario</button>
    </form>
  </div>

  <script>
    const API_URL = 'http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario';

    // Cargar carreras al iniciar la página
    async function cargarCarreras() {
      try {
        const response = await fetch('get_carreras.php');
        const data = await response.json();
        
        if (data.success && data.carreras) {
          const selectCarrera = document.getElementById('carrera');
          
          // Agrupar carreras por facultad
          const carrerasPorFacultad = {};
          data.carreras.forEach(carrera => {
            if (!carrerasPorFacultad[carrera.facultad]) {
              carrerasPorFacultad[carrera.facultad] = [];
            }
            carrerasPorFacultad[carrera.facultad].push(carrera);
          });
          
          // Crear optgroups por facultad
          for (const facultad in carrerasPorFacultad) {
            const optgroup = document.createElement('optgroup');
            optgroup.label = facultad;
            
            carrerasPorFacultad[facultad].forEach(carrera => {
              const option = document.createElement('option');
              option.value = carrera.nombre;
              option.textContent = carrera.nombre;
              optgroup.appendChild(option);
            });
            
            selectCarrera.appendChild(optgroup);
          }
        }
      } catch (error) {
        console.error('Error al cargar carreras:', error);
      }
    }

    // Cargar carreras cuando se carga la página
    document.addEventListener('DOMContentLoaded', cargarCarreras);

    document.getElementById('formRegistro').addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = {
        dni: document.getElementById('dni').value,
        nombres: document.getElementById('nombres').value,
        apellidos: document.getElementById('apellidos').value,
        correo: document.getElementById('correo').value,
        carrera: document.getElementById('carrera').value,
        ciclo: document.getElementById('ciclo').value,
        comentarios: document.getElementById('comentarios').value
      };

      const correoDestino = formData.correo; // El mismo correo del usuario

      try {
        // Primero registrar en la API
        const response = await fetch(API_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
          // Ahora enviar el correo electrónico
          try {
            const emailResponse = await fetch('contact.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: new URLSearchParams({
                dni: formData.dni,
                nombres: formData.nombres,
                apellidos: formData.apellidos,
                email: formData.correo,
                carrera: formData.carrera,
                ciclo: formData.ciclo,
                comentarios: formData.comentarios,
                correo_destino: correoDestino,
                skip_db: 'true' // Indicar que no debe insertar en BD
              })
            });

            // Mostrar mensaje de éxito
            await Swal.fire({
              icon: 'success',
              title: '¡Éxito!',
              text: 'Usuario registrado y correo enviado correctamente',
              showConfirmButton: true,
              confirmButtonText: 'OK'
            });
            
            // Limpiar el formulario después del registro exitoso
            document.getElementById('formRegistro').reset();
          } catch (emailError) {
            // Si falla el correo pero se registró el usuario
            await Swal.fire({
              icon: 'warning',
              title: 'Registro exitoso',
              text: 'Usuario registrado, pero hubo un error al enviar el correo',
              showConfirmButton: true
            });
            
            // Limpiar el formulario después del registro exitoso
            document.getElementById('formRegistro').reset();
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: result.message || 'Error al registrar el usuario'
          });
        }
      } catch (error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo conectar con el servidor'
        });
      }
    });
  </script>

</body>
</html>