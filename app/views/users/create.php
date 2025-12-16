<?php 
$title = "Crear Usuario";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<style>
.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.form-container {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.help-text {
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 5px;
}

.password-requirements {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.password-requirements ul {
    margin: 10px 0 0 0;
    padding-left: 20px;
}

.password-requirements li {
    color: #856404;
    margin: 5px 0;
    font-size: 13px;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
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

.btn-secondary {
    background: #95a5a6;
    color: white;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}
</style>

<div class="container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
    <div class="form-header">
        <h1 style="margin: 0; font-size: 2rem;">➕ Crear Nuevo Usuario</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Registra un nuevo usuario en el sistema</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            ✕ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?route=usuarios&action=store" id="formCrear">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
            
            <div class="form-group">
                <label for="username">👤 Nombre de Usuario</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="off"
                    placeholder="Ingrese el nombre de usuario"
                >
                <div class="help-text">Debe ser único en el sistema</div>
            </div>
            
            <div class="form-group">
                <label for="password">🔐 Contraseña</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Ingrese una contraseña segura"
                >
                
                <div class="password-requirements">
                    <strong style="color: #856404;">📋 Requisitos de Contraseña:</strong>
                    <ul>
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula (A-Z)</li>
                        <li>Al menos una letra minúscula (a-z)</li>
                        <li>Al menos un número (0-9)</li>
                        <li>Al menos un carácter especial (!@#$%^&*(),.?":{}|<>)</li>
                    </ul>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">🔐 Confirmar Contraseña</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirm" 
                    required
                    placeholder="Confirme la contraseña"
                >
                <div class="help-text" id="password-match"></div>
            </div>
            
            <div class="form-group">
                <label for="role">👔 Rol del Usuario</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="usuario">👤 Usuario Normal</option>
                    <option value="admin">👑 Administrador</option>
                </select>
                <div class="help-text">
                    <strong>Usuario:</strong> Puede registrar estudiantes<br>
                    <strong>Admin:</strong> Acceso completo al sistema
                </div>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    💾 Crear Usuario
                </button>
                <a href="index.php?route=usuarios" class="btn btn-secondary">
                    ← Volver
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Validar que las contraseñas coincidan
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('password_confirm');
const passwordMatch = document.getElementById('password-match');
const btnSubmit = document.getElementById('btnSubmit');

function checkPasswordMatch() {
    if (passwordConfirm.value === '') {
        passwordMatch.textContent = '';
        passwordMatch.style.color = '';
        return;
    }
    
    if (password.value === passwordConfirm.value) {
        passwordMatch.textContent = '✓ Las contraseñas coinciden';
        passwordMatch.style.color = '#28a745';
    } else {
        passwordMatch.textContent = '✕ Las contraseñas no coinciden';
        passwordMatch.style.color = '#dc3545';
    }
}

password.addEventListener('input', checkPasswordMatch);
passwordConfirm.addEventListener('input', checkPasswordMatch);

// Validar formulario antes de enviar
document.getElementById('formCrear').addEventListener('submit', function(e) {
    if (password.value !== passwordConfirm.value) {
        e.preventDefault();
        Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
        return false;
    }
    
    if (password.value.length < 8) {
        e.preventDefault();
        Swal.fire('Error', 'La contraseña debe tener al menos 8 caracteres', 'error');
        return false;
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
