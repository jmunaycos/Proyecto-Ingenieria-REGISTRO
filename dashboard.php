<?php
require_once 'auth.php';
requireAuth();
$currentUser = getCurrentUser();

// Solo admins pueden acceder al dashboard
if (!isAdmin()) {
    header('Location: registro.php');
    exit;
}

// Conectar a la base de datos para obtener estadísticas
$conn = getDBConnection();

// Total de usuarios
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios_universitarios");
$totalUsuarios = $result->fetch_assoc()['total'];

// Usuarios registrados hoy
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios_universitarios WHERE DATE(created_at) = CURDATE()");
$usuariosHoy = $result->fetch_assoc()['total'];

// Usuarios registrados este mes
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios_universitarios WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
$usuariosMes = $result->fetch_assoc()['total'];

// Último usuario registrado
$result = $conn->query("SELECT nombres, apellidos, created_at FROM usuarios_universitarios ORDER BY created_at DESC LIMIT 1");
$ultimoUsuario = $result->fetch_assoc();

// Usuarios por mes (últimos 6 meses)
$result = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as mes,
        COUNT(*) as total
    FROM usuarios_universitarios 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY mes ASC
");
$usuariosPorMes = [];
while ($row = $result->fetch_assoc()) {
    $usuariosPorMes[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Registro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="principal.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .stat-card.primary {
            --card-color-start: #3498db;
            --card-color-end: #2980b9;
        }
        .stat-card.success {
            --card-color-start: #27ae60;
            --card-color-end: #229954;
        }
        .stat-card.warning {
            --card-color-start: #f39c12;
            --card-color-end: #e67e22;
        }
        .stat-card.info {
            --card-color-start: #9b59b6;
            --card-color-end: #8e44ad;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon.primary { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-icon.success { background: linear-gradient(135deg, #27ae60, #229954); }
        .stat-icon.warning { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-icon.info { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .stat-content h3 {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 600;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            line-height: 1;
        }

        .stat-footer {
            margin-top: 1.5rem;
            padding-top: 1.2rem;
            border-top: 1px solid #f0f0f0;
            font-size: 0.85rem;
            color: #95a5a6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .chart-header h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
            margin-top: 2rem;
        }

        .action-btn {
            padding: 1.8rem;
            border-radius: 14px;
            border: 2px solid #e9ecef;
            background: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .action-btn i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .action-btn span {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            border-radius: 16px;
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-content h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .welcome-content p {
            opacity: 0.95;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .welcome-icon {
            font-size: 120px;
            opacity: 0.15;
            position: relative;
            z-index: 0;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
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
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <?php endif; ?>
                <a href="registro.php" class="nav-link">
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
        <!-- Sección de bienvenida -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>¡Bienvenido, <?php echo htmlspecialchars(explode(' ', $currentUser['nombre_completo'])[0]); ?>!</h1>
                <p>Panel de control del sistema de gestión de usuarios</p>
            </div>
            <div class="welcome-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="dashboard-grid">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-content">
                        <h3>Total Usuarios</h3>
                        <div class="stat-number"><?php echo $totalUsuarios; ?></div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-database"></i> Usuarios registrados en el sistema
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-content">
                        <h3>Registros Hoy</h3>
                        <div class="stat-number"><?php echo $usuariosHoy; ?></div>
                    </div>
                    <div class="stat-icon success">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-clock"></i> Nuevos registros de hoy
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-content">
                        <h3>Registros Este Mes</h3>
                        <div class="stat-number"><?php echo $usuariosMes; ?></div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-chart-line"></i> Total del mes actual
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-content">
                        <h3>Último Registro</h3>
                        <div class="stat-number" style="font-size: 16px;">
                            <?php 
                            if ($ultimoUsuario) {
                                echo htmlspecialchars($ultimoUsuario['nombres'] . ' ' . $ultimoUsuario['apellidos']);
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="stat-icon info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-clock"></i> 
                    <?php 
                    if ($ultimoUsuario) {
                        $fecha = new DateTime($ultimoUsuario['created_at']);
                        echo $fecha->format('d/m/Y H:i');
                    } else {
                        echo "Sin registros";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Gráfico de usuarios por mes -->
        <div class="chart-container">
            <div class="chart-header">
                <h3><i class="fas fa-chart-bar"></i> Usuarios Registrados - Últimos 6 Meses</h3>
            </div>
            <canvas id="chartUsuariosMes" height="80"></canvas>
        </div>

        <!-- Acciones rápidas -->
        <div class="chart-container">
            <div class="chart-header">
                <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
            </div>
            <div class="quick-actions">
                <a href="index.php" class="action-btn">
                    <i class="fas fa-users"></i>
                    <span>Ver Usuarios</span>
                </a>
                <a href="registro.php" class="action-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Nuevo Usuario</span>
                </a>
                <a href="index.php" class="action-btn" onclick="event.preventDefault(); exportarDesdeAqui();">
                    <i class="fas fa-file-csv"></i>
                    <span>Exportar CSV</span>
                </a>
                <?php if (isAdmin()): ?>
                <a href="#" class="action-btn" onclick="event.preventDefault(); alert('Función en desarrollo');">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Preparar datos para el gráfico
        const mesesData = <?php echo json_encode($usuariosPorMes); ?>;
        
        const labels = mesesData.map(item => {
            const [year, month] = item.mes.split('-');
            const fecha = new Date(year, month - 1);
            return fecha.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        });
        
        const data = mesesData.map(item => parseInt(item.total));

        // Crear gráfico
        const ctx = document.getElementById('chartUsuariosMes').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length > 0 ? labels : ['Sin datos'],
                datasets: [{
                    label: 'Usuarios Registrados',
                    data: data.length > 0 ? data : [0],
                    backgroundColor: 'rgba(52, 152, 219, 0.6)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(44, 62, 80, 0.9)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: '#3498db',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        function exportarDesdeAqui() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
