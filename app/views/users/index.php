<?php 
$title = "Gestión de Usuarios";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<style>
.users-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.users-container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.users-container h2 {
    color: #2c3e50;
    font-weight: 700;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background: white;
}

.user-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.user-table thead th {
    padding: 16px 12px;
    text-align: center;
    color: #fff !important;
    background: transparent;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.user-table thead th:first-child {
    border-radius: 12px 0 0 0;
}

.user-table thead th:last-child {
    border-radius: 0 12px 0 0;
}

.user-table tbody tr {
    transition: background 0.2s;
    border-bottom: 1px solid #e8eaf0;
    background: white;
}

.user-table tbody tr:hover {
    background: #f8f9ff;
}

.user-table td {
    padding: 14px 12px;
    color: #2c3e50 !important;
    font-size: 0.95rem;
    text-align: center;
}

.user-table td strong {
    color: #2c3e50 !important;
    font-weight: 700;
}

.role-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.role-badge.admin {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.role-badge.usuario {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-edit {
    background: #3498db;
    color: white;
    padding: 6px 12px;
    margin-right: 5px;
}

.btn-delete {
    background: #e74c3c;
    color: white;
    padding: 6px 12px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}
</style>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <div class="users-header">
        <h1 style="margin: 0; font-size: 2rem;">👥 Gestión de Usuarios</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Administra los usuarios del sistema</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            ✕ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="users-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #2c3e50;">Lista de Usuarios</h2>
            <a href="index.php?route=usuarios&action=create" class="btn btn-primary">
                ➕ Nuevo Usuario
            </a>
        </div>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($usuario['id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                    <td>
                        <span class="role-badge <?php echo $usuario['role']; ?>">
                            <?php echo $usuario['role'] === 'admin' ? '👑 Admin' : '👤 Usuario'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></td>
                    <td>
                        <?php if ($usuario['id'] != Auth::id()): ?>
                            <button onclick="editarUsuario(<?php echo $usuario['id']; ?>)" class="btn btn-edit">
                                ✏️ Editar
                            </button>
                            <button onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)" class="btn btn-delete">
                                🗑️ Eliminar
                            </button>
                        <?php else: ?>
                            <span style="color: #95a5a6; font-style: italic;">Usuario actual</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Edición -->
<div id="modalEditar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 20px; color: #2c3e50;">✏️ Editar Usuario</h3>
        <form id="formEditar" style="display: grid; gap: 15px;">
            <input type="hidden" id="editId">
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: 600;">Usuario</label>
                <input type="text" id="editUsername" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: 600;">Nueva Contraseña</label>
                <input type="password" id="editPassword" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d; font-size: 12px;">Dejar en blanco para no cambiar</small>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: 600;">Rol</label>
                <select id="editRole" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    <option value="usuario">👤 Usuario</option>
                    <option value="admin">👑 Administrador</option>
                </select>
            </div>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; border-radius: 5px; margin-top: 10px;">
                <small style="color: #856404;">
                    <strong>Requisitos de contraseña:</strong><br>
                    • Mínimo 8 caracteres<br>
                    • Al menos 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial
                </small>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                <button type="button" onclick="cerrarModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Cancelar
                </button>
                <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
async function editarUsuario(id) {
    try {
        const response = await fetch(`index.php?route=usuarios/show/${id}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('editId').value = data.data.id;
            document.getElementById('editUsername').value = data.data.username;
            document.getElementById('editPassword').value = '';
            document.getElementById('editRole').value = data.data.role;
            
            document.getElementById('modalEditar').style.display = 'flex';
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'No se pudo cargar el usuario', 'error');
    }
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

document.getElementById('formEditar').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('editId').value;
    const formData = new FormData();
    formData.append('username', document.getElementById('editUsername').value);
    formData.append('password', document.getElementById('editPassword').value);
    formData.append('role', document.getElementById('editRole').value);
    
    try {
        const response = await fetch(`index.php?route=usuarios/update/${id}`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            cerrarModal();
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
            const response = await fetch(`index.php?route=usuarios/delete/${id}`, {
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
