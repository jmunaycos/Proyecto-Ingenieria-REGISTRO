<?php 
$title = "Opinion del estudiante";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container" style="max-width: 800px; margin: 40px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <h1 style="text-align: center; color: #6a1b9a; margin-bottom: 10px;">Opinion del estudiante</h1>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Universidad Autónoma del Perú</p>
    
    <form id="registroForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
        <div style="grid-column: 1 / -1;">
            <label for="dni" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                DNI <span style="color: red;">*</span>
            </label>
            <input 
                type="text" 
                id="dni" 
                name="dni" 
                required 
                maxlength="8" 
                pattern="\d{8}"
                placeholder="8 dígitos"
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;"
            >
        </div>
        
        <div>
            <label for="nombres" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Nombres <span style="color: red;">*</span>
            </label>
            <input 
                type="text" 
                id="nombres" 
                name="nombres" 
                required
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;"
            >
        </div>
        
        <div>
            <label for="apellidos" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Apellidos <span style="color: red;">*</span>
            </label>
            <input 
                type="text" 
                id="apellidos" 
                name="apellidos" 
                required
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;"
            >
        </div>
        
        <div style="grid-column: 1 / -1;">
            <label for="correo" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Correo Electrónico <span style="color: red;">*</span>
            </label>
            <input 
                type="email" 
                id="correo" 
                name="correo" 
                required
                placeholder="ejemplo@autonoma.edu.pe"
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;"
            >
        </div>
        
        <div>
            <label for="carrera" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Carrera <span style="color: red;">*</span>
            </label>
            <select 
                id="carrera" 
                name="carrera" 
                required
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; background: white;"
            >
                <option value="">Seleccione una carrera</option>
                <?php foreach ($carrerasGrouped as $facultad => $carreras): ?>
                    <optgroup label="<?php echo htmlspecialchars($facultad); ?>">
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo htmlspecialchars($carrera['nombre_carrera'] ?? $carrera['nombre']); ?>">
                                <?php echo htmlspecialchars($carrera['nombre_carrera'] ?? $carrera['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label for="ciclo" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Ciclo <span style="color: red;">*</span>
            </label>
            <select 
                id="ciclo" 
                name="ciclo" 
                required
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; background: white;"
            >
                <option value="">Seleccione ciclo</option>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div style="grid-column: 1 / -1;">
            <label for="comentarios" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Comentarios
            </label>
            <textarea 
                id="comentarios" 
                name="comentarios" 
                rows="4"
                placeholder="Información adicional (opcional)"
                style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; resize: vertical;"
            ></textarea>
        </div>
        
        <div style="grid-column: 1 / -1;">
            <button 
                type="submit" 
                style="width: 100%; padding: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer;"
            >
                Registrar tu opinion
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('registroForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Mostrar loading
    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        const response = await fetch('<?php echo BASE_URL; ?>/index.php?route=students/store', {
            method: 'POST',
            body: formData
        });
        
        // Verificar si la respuesta es OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error('Respuesta no JSON:', text);
            throw new Error("La respuesta no es JSON");
        }
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                confirmButtonColor: '#6a1b9a'
            }).then(() => {
                this.reset();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#6a1b9a'
            });
        }
    } catch (error) {
        console.error('Error completo:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión: ' + error.message,
            confirmButtonColor: '#6a1b9a'
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
