<?php 
$title = "Iniciar Sesión";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container" style="max-width: 450px; margin: 80px auto; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #6a1b9a; margin-bottom: 10px;">Sistema de Registro</h1>
        <p style="color: #666;">Universidad Autónoma del Perú</p>
    </div>
    
    <form method="POST" action="index.php?route=login" style="display: flex; flex-direction: column; gap: 20px;">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
        <div>
            <label for="username" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Usuario</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                required 
                autocomplete="username"
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#6a1b9a'"
                onblur="this.style.borderColor='#e0e0e0'"
            >
        </div>
        
        <div>
            <label for="password" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Contraseña</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                autocomplete="current-password"
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#6a1b9a'"
                onblur="this.style.borderColor='#e0e0e0'"
            >
        </div>
        
        <button 
            type="submit" 
            style="padding: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
        >
            Iniciar Sesión
        </button>
    </form>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; color: #666; font-size: 13px;">
        <p><strong>Usuarios de prueba:</strong></p>
        <p>Admin: <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">admin / admin123</code></p>
        <p>Usuario: <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">usuario / user123</code></p>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo $_SESSION['error']; ?>',
        confirmButtonColor: '#6a1b9a'
    });
</script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
