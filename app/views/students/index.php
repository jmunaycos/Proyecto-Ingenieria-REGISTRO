<?php 
$title = "Gestión de Estudiantes";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container" style="max-width: 1600px; margin: 40px auto; padding: 0 20px;">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: #2c3e50; font-size: 2rem;">Estudiantes Registrados</h2>
        <div style="display: flex; gap: 10px;">
            <button class="btn" onclick="exportarCSV()" style="background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-file-csv"></i> Exportar CSV
            </button>
            <button class="btn" onclick="window.location.href='index.php?route=registro'" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-plus"></i> Nuevo Registro
            </button>
        </div>
    </header>

    <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: 1fr auto auto auto auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Buscar</label>
                <input type="text" id="buscarTexto" placeholder="DNI, Nombre, Apellido, Correo..." 
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Carrera</label>
                <select id="filtroCarrera" style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; min-width: 200px;">
                    <option value="">Todas las carreras</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Ciclo</label>
                <select id="filtroCiclo" style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; min-width: 150px;">
                    <option value="">Todos los ciclos</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>">Ciclo <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button onclick="buscarUsuarios()" style="padding: 12px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-search"></i> Buscar
            </button>
            <button onclick="limpiarFiltros()" style="padding: 12px 20px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table id="tablaUsuarios" style="width: 100%; border-collapse: collapse;">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <tr>
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">DNI</th>
                    <th style="padding: 15px; text-align: left;">Nombres</th>
                    <th style="padding: 15px; text-align: left;">Apellidos</th>
                    <th style="padding: 15px; text-align: left;">Correo</th>
                    <th style="padding: 15px; text-align: left;">Carrera</th>
                    <th style="padding: 15px; text-align: left;">Ciclo</th>
                    <th style="padding: 15px; text-align: left;">Comentarios</th>
                    <th style="padding: 15px; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 12px;"><?php echo $student['id']; ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($student['dni']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($student['nombres']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($student['apellidos']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($student['correo']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($student['nombre_carrera'] ?? 'N/A'); ?></td>
                    <td style="padding: 12px;"><?php 
                        $ciclo = preg_replace('/[^0-9]/', '', $student['ciclo']);
                        echo 'Ciclo ' . $ciclo;
                    ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars(substr($student['comentarios'] ?? '', 0, 30)); ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <button onclick="editarUsuario(<?php echo $student['id']; ?>)" style="background: #3498db; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;" title="Editar">
                            ✏️
                        </button>
                        <button onclick="eliminarUsuario(<?php echo $student['id']; ?>)" style="background: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;" title="Eliminar">
                            🗑️
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Edición -->
<div id="modalEditar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="margin-bottom: 20px; color: #2c3e50;">Editar Estudiante</h3>
        <form id="formEditar" style="display: grid; gap: 15px;">
            <input type="hidden" id="editId">
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">DNI</label>
                <input type="text" id="editDni" maxlength="8" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Nombres</label>
                <input type="text" id="editNombres" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Apellidos</label>
                <input type="text" id="editApellidos" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Correo</label>
                <input type="email" id="editCorreo" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Carrera</label>
                <select id="editCarrera" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <option value="">Seleccione...</option>
                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?php echo htmlspecialchars($carrera['nombre_carrera'] ?? $carrera['nombre']); ?>"><?php echo htmlspecialchars($carrera['nombre_carrera'] ?? $carrera['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Ciclo</label>
                <select id="editCiclo" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>">Ciclo <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Comentarios</label>
                <textarea id="editComentarios" rows="3" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;"></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                <button type="button" onclick="cerrarModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
let todosLosEstudiantes = <?php echo json_encode($students); ?>;

// Cargar carreras para el filtro
async function cargarCarrerasFiltro() {
    try {
        const select = document.getElementById('filtroCarrera');
        // Obtener carreras únicas directamente de los estudiantes
        const uniqueCarreras = [...new Set(todosLosEstudiantes.map(s => s.nombre_carrera || s.carrera).filter(c => c))];
        uniqueCarreras.sort().forEach(carrera => {
            const option = document.createElement('option');
            option.value = carrera;
            option.textContent = carrera;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

function buscarUsuarios() {
    const texto = document.getElementById('buscarTexto').value.toLowerCase();
    const carrera = document.getElementById('filtroCarrera').value;
    const ciclo = document.getElementById('filtroCiclo').value;
    
    const filtrados = todosLosEstudiantes.filter(student => {
        const carreraEstudiante = student.nombre_carrera || student.carrera || '';
        // Normalizar ciclo: extraer solo números
        const cicloEstudiante = String(student.ciclo).replace(/[^0-9]/g, '');
        
        const cumpleTexto = !texto || 
            student.dni.toLowerCase().includes(texto) ||
            student.nombres.toLowerCase().includes(texto) ||
            student.apellidos.toLowerCase().includes(texto) ||
            student.correo.toLowerCase().includes(texto) ||
            carreraEstudiante.toLowerCase().includes(texto);
        
        const cumpleCarrera = !carrera || carreraEstudiante === carrera;
        const cumpleCiclo = !ciclo || cicloEstudiante === ciclo;
        
        return cumpleTexto && cumpleCarrera && cumpleCiclo;
    });
    
    actualizarTabla(filtrados);
}

function limpiarFiltros() {
    document.getElementById('buscarTexto').value = '';
    document.getElementById('filtroCarrera').value = '';
    document.getElementById('filtroCiclo').value = '';
    actualizarTabla(todosLosEstudiantes);
}

function actualizarTabla(estudiantes) {
    const tbody = document.querySelector('#tablaUsuarios tbody');
    tbody.innerHTML = '';
    
    estudiantes.forEach(student => {
        tbody.innerHTML += `
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px;">${student.id}</td>
                <td style="padding: 12px;">${student.dni}</td>
                <td style="padding: 12px;">${student.nombres}</td>
                <td style="padding: 12px;">${student.apellidos}</td>
                <td style="padding: 12px;">${student.correo}</td>
                <td style="padding: 12px;">${student.nombre_carrera || student.carrera || 'N/A'}</td>
                <td style="padding: 12px;">Ciclo ${String(student.ciclo).replace(/[^0-9]/g, '')}</td>
                <td style="padding: 12px;">${(student.comentarios || '').substring(0, 30)}</td>
                <td style="padding: 12px; text-align: center;">
                    <button onclick="editarUsuario(${student.id})" style="background: #3498db; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;" title="Editar">
                        ✏️
                    </button>
                    <button onclick="eliminarUsuario(${student.id})" style="background: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;" title="Eliminar">
                        🗑️
                    </button>
                </td>
            </tr>
        `;
    });
}

async function editarUsuario(id) {
    try {
        const response = await fetch(`index.php?route=students/show/${id}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('editId').value = data.data.id;
            document.getElementById('editDni').value = data.data.dni;
            document.getElementById('editNombres').value = data.data.nombres;
            document.getElementById('editApellidos').value = data.data.apellidos;
            document.getElementById('editCorreo').value = data.data.correo;
            document.getElementById('editCarrera').value = data.data.carrera;
            document.getElementById('editCiclo').value = data.data.ciclo;
            document.getElementById('editComentarios').value = data.data.comentarios || '';
            
            document.getElementById('modalEditar').style.display = 'flex';
        }
    } catch (error) {
        Swal.fire('Error', 'No se pudo cargar el estudiante', 'error');
    }
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

document.getElementById('formEditar').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('editId').value;
    const formData = new FormData();
    formData.append('dni', document.getElementById('editDni').value);
    formData.append('nombres', document.getElementById('editNombres').value);
    formData.append('apellidos', document.getElementById('editApellidos').value);
    formData.append('correo', document.getElementById('editCorreo').value);
    formData.append('carrera', document.getElementById('editCarrera').value);
    formData.append('ciclo', document.getElementById('editCiclo').value);
    formData.append('comentarios', document.getElementById('editComentarios').value);
    
    try {
        const response = await fetch(`index.php?route=students/update/${id}`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Error de conexión', 'error');
    }
});

async function eliminarUsuario(id) {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`index.php?route=students/delete/${id}`, {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire('Eliminado', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    }
}

function exportarCSV() {
    window.location.href = 'index.php?route=students/export-csv';
}

// Inicializar
cargarCarrerasFiltro();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
