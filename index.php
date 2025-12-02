<?php
require_once 'auth.php';
requireAuth();
$currentUser = getCurrentUser();

// Solo admins pueden acceder a la lista de usuarios
if (!isAdmin()) {
    header('Location: registro.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestión de Usuarios - REGISTRO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <a href="index.php" class="nav-link active">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <?php endif; ?>
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

    <div class="container">
        <header class="page-header">
            <h2>Usuarios Registrados</h2>
            <div class="header-buttons">
                <button class="btn btn-success" onclick="exportarCSV()">
                    <i class="fas fa-file-csv"></i> Exportar CSV
                </button>
                <button class="btn btn-primary" onclick="window.location.href='registro.php'">
                    <i class="fas fa-plus"></i> Registrar Nuevo Usuario
                </button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search-bar">
                <input type="number" id="buscarId" placeholder="Buscar por ID...">
                <button class="btn" onclick="buscarUsuarioPorId()"><i class="fas fa-search"></i> Buscar</button>
                <button class="btn btn-secondary" onclick="cargarUsuarios()"><i class="fas fa-sync"></i> Mostrar Todos</button>
            </div>
        </div>

        <main class="tabla-container">
            <table id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DNI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Carrera</th>
                        <th>Ciclo</th>
                        <th>Comentarios</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </main>
    </div>

    <div id="modalEditar" class="modal-overlay">
        <div class="modal-content">
            <form id="formEditar">
                <h3>Editar Usuario</h3>
                <input type="hidden" id="editId">
        
                <div class="form-group">
                  <label for="editDni">DNI</label>
                  <input type="text" id="editDni" maxlength="8" required>
                </div>
                
                <div class="form-group">
                  <label for="editNombres">Nombres</label>
                  <input type="text" id="editNombres" required>
                </div>

                <div class="form-group">
                  <label for="editApellidos">Apellidos</label>
                  <input type="text" id="editApellidos" required>
                </div>

                <div class="form-group">
                  <label for="editCorreo">Correo Electrónico</label>
                  <input type="email" id="editCorreo" required>
                </div>

                <div class="form-group">
                  <label for="editCarrera">Carrera</label>
                  <select id="editCarrera" required>
                    <option value="">Seleccione una carrera...</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="editCiclo">Ciclo</label>
                  <select id="editCiclo" required>
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
                  <label for="editComentarios">Comentarios</label>
                  <textarea id="editComentarios" rows="3"></textarea>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
  <script>
    const API_URL = 'http://localhost/Proyecto-Ingenieria-REGISTRO/apiweb/public/index.php?resource=usuario';
    const tablaBody = document.querySelector('#tablaUsuarios tbody');

    async function cargarUsuarios() {
      tablaBody.innerHTML = "<tr><td colspan='9'>Cargando...</td></tr>";
      try {
        const res = await fetch(API_URL);
        const usuarios = await res.json();

        tablaBody.innerHTML = '';
        usuarios.forEach(user => {
          tablaBody.innerHTML += `
            <tr>
              <td>${user.id}</td>
              <td>${user.dni}</td>
              <td>${user.nombres}</td>
              <td>${user.apellidos}</td>
              <td>${user.correo}</td>
              <td>${user.carrera || 'Sin especificar'}</td>
              <td>${user.ciclo || 'Sin especificar'}</td>
              <td>${user.comentarios || 'Sin comentarios'}</td>
              <td>
                <button onclick="editarUsuario(${user.id})" class="btn-action btn-edit" title="Editar">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="eliminarUsuario(${user.id})" class="btn-action btn-delete" title="Eliminar">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
          `;
        });
      } catch (e) {
        tablaBody.innerHTML = "<tr><td colspan='9'>Error al cargar usuarios.</td></tr>";
        console.error(e);
      }
    }

    // Cargar carreras para el modal de edición
    async function cargarCarrerasEditar() {
      try {
        const response = await fetch('get_carreras.php');
        const data = await response.json();
        
        if (data.success && data.carreras) {
          const selectCarrera = document.getElementById('editCarrera');
          
          // Limpiar opciones anteriores excepto la primera
          selectCarrera.innerHTML = '<option value="">Seleccione una carrera...</option>';
          
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

    async function buscarUsuarioPorId() {
      const id = document.getElementById('buscarId').value.trim();
      if (!id) {
        Swal.fire('Campo vacío', 'Ingresa un ID válido', 'warning');
        return;
      }

      try {
        const res = await fetch(`${API_URL}&id=${id}`);
        const user = await res.json();

        if (!user.id) {
          Swal.fire('No encontrado', `Usuario con ID ${id} no existe.`, 'info');
          return;
        }

        tablaBody.innerHTML = `
          <tr>
           <td>${user.id}</td>
            <td>${user.dni}</td>
            <td>${user.nombres}</td>
            <td>${user.apellidos}</td>
            <td>${user.correo}</td>
            <td>${user.carrera || 'Sin especificar'}</td>
            <td>${user.ciclo || 'Sin especificar'}</td>
            <td>${user.comentarios || 'Sin comentarios'}</td>
            <td>
              <button onclick="editarUsuario(${user.id})" class="btn-action btn-edit" title="Editar">
                <i class="fas fa-edit"></i>
              </button>
              <button onclick="eliminarUsuario(${user.id})" class="btn-action btn-delete" title="Eliminar">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        `;
      } catch (e) {
        Swal.fire('Error', 'No se pudo buscar el usuario.', 'error');
        console.error(e);
      }
    }

    async function eliminarUsuario(id) {
      const confirm = await Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      });

      if (!confirm.isConfirmed) return;

      try {
        const res = await fetch(`${API_URL}&id=${id}`, {
          method: 'DELETE'
        });
        const result = await res.json();
        Swal.fire('Eliminado', result.message || 'Usuario eliminado.', 'success');
        cargarUsuarios();
      } catch (e) {
        Swal.fire('Error', 'No se pudo eliminar.', 'error');
      }
    }

    async function editarUsuario(id) {
      try {
        const res = await fetch(`${API_URL}&id=${id}`);
        const user = await res.json();

        document.getElementById('editId').value = user.id;
        document.getElementById('editDni').value = user.dni;
        document.getElementById('editNombres').value = user.nombres;
        document.getElementById('editApellidos').value = user.apellidos;
        document.getElementById('editCorreo').value = user.correo;
        
        // Cargar carreras antes de seleccionar
        await cargarCarrerasEditar();
        document.getElementById('editCarrera').value = user.carrera || '';
        document.getElementById('editCiclo').value = user.ciclo || '';
        document.getElementById('editComentarios').value = user.comentarios || '';

        document.getElementById('modalEditar').classList.add('activo');
        //cargarUsuarios();
      } catch (e) {
        Swal.fire('Error', 'No se pudo obtener el usuario.', 'error');
      }
    }

    document.getElementById('formEditar').addEventListener('submit', async (e) => {
      e.preventDefault();

      const id = document.getElementById('editId').value;
      const data = {        
        dni: document.getElementById('editDni').value,
        nombres: document.getElementById('editNombres').value,
        apellidos: document.getElementById('editApellidos').value,
        correo: document.getElementById('editCorreo').value,
        carrera: document.getElementById('editCarrera').value,
        ciclo: document.getElementById('editCiclo').value,
        comentarios: document.getElementById('editComentarios').value
      };

      try {
        const res = await fetch(`${API_URL}&id=${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams(data)
        });

        const result = await res.json();
        Swal.fire('Actualizado', result.message || 'Usuario actualizado.', 'success');
        cerrarModal();
        cargarUsuarios();
      } catch (e) {
        Swal.fire('Error', 'No se pudo actualizar.', 'error');
      }
    });

    function cerrarModal() {
      document.getElementById('modalEditar').classList.remove('activo');
    }

    async function exportarCSV() {
      try {
        const res = await fetch(API_URL);
        const usuarios = await res.json();

        if (!usuarios || usuarios.length === 0) {
          Swal.fire('Sin datos', 'No hay usuarios para exportar.', 'info');
          return;
        }

        // Crear el contenido CSV con BOM UTF-8 para Excel
        const BOM = '\uFEFF';
        let csv = BOM + 'ID;DNI;Nombres;Apellidos;Correo;Carrera;Ciclo;Comentarios\n';
        
        usuarios.forEach(user => {
          // Usar punto y coma como separador y escapar comillas
          const carrera = (user.carrera || 'Sin especificar').replace(/"/g, '""');
          const ciclo = (user.ciclo || 'Sin especificar').replace(/"/g, '""');
          const comentarios = (user.comentarios || 'Sin comentarios').replace(/"/g, '""');
          csv += `${user.id};${user.dni};${user.nombres.replace(/"/g, '""')};${user.apellidos.replace(/"/g, '""')};${user.correo};${carrera};${ciclo};${comentarios}\n`;
        });

        // Crear un blob con el contenido CSV
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        
        // Crear un enlace de descarga
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        // Generar nombre del archivo con fecha y hora actual
        const fecha = new Date();
        const nombreArchivo = `usuarios_${fecha.getFullYear()}${String(fecha.getMonth()+1).padStart(2,'0')}${String(fecha.getDate()).padStart(2,'0')}_${String(fecha.getHours()).padStart(2,'0')}${String(fecha.getMinutes()).padStart(2,'0')}.csv`;
        
        link.setAttribute('href', url);
        link.setAttribute('download', nombreArchivo);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        Swal.fire('Exportado', `${usuarios.length} usuarios exportados a CSV`, 'success');
      } catch (e) {
        console.error(e);
        Swal.fire('Error', 'No se pudo exportar el archivo CSV.', 'error');
      }
    }

    cargarUsuarios();
  </script>

</body>
</html>
