<nav class="navbar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px 20px; color: white; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="nav-brand" style="font-size: 20px; font-weight: bold;">
        <?php echo APP_NAME; ?>
    </div>
    
    <div class="nav-menu" style="display: flex; gap: 20px; align-items: center;">
        <?php if (Auth::isAdmin()): ?>
            <a href="<?php echo BASE_URL; ?>/index.php?route=dashboard" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background 0.3s;" 
               onmouseover="this.style.background='rgba(255,255,255,0.2)'" 
               onmouseout="this.style.background='transparent'">
                Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/index.php?route=students" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background 0.3s;"
               onmouseover="this.style.background='rgba(255,255,255,0.2)'" 
               onmouseout="this.style.background='transparent'">
                Estudiantes
            </a>
            <a href="<?php echo BASE_URL; ?>/index.php?route=usuarios" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background 0.3s;"
               onmouseover="this.style.background='rgba(255,255,255,0.2)'" 
               onmouseout="this.style.background='transparent'">
                Usuarios
            </a>
        <?php endif; ?>
        
        <a href="<?php echo BASE_URL; ?>/index.php?route=registro" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background 0.3s;"
           onmouseover="this.style.background='rgba(255,255,255,0.2)'" 
           onmouseout="this.style.background='transparent'">
            Registro
        </a>
        
        <div style="border-left: 1px solid rgba(255,255,255,0.3); height: 24px; margin: 0 10px;"></div>
        
        <span style="font-size: 14px;">
            <?php echo Auth::username(); ?> 
            <span style="background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">
                <?php echo Auth::role(); ?>
            </span>
        </span>
        
        <a href="<?php echo BASE_URL; ?>/index.php?route=logout" style="background: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s;"
           onmouseover="this.style.background='rgba(255,255,255,0.3)'" 
           onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            Cerrar Sesión
        </a>
    </div>
</nav>
