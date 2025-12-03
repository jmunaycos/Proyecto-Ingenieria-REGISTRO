<?php 
$title = "Dashboard";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
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
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.stat-card.primary {
    --card-color-start: #667eea;
    --card-color-end: #764ba2;
}

.stat-card.success {
    --card-color-start: #11998e;
    --card-color-end: #38ef7d;
}

.stat-card.warning {
    --card-color-start: #f093fb;
    --card-color-end: #f5576c;
}

.stat-card.info {
    --card-color-start: #4facfe;
    --card-color-end: #00f2fe;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-icon.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-icon.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-content h3 {
    font-size: 0.9rem;
    color: #8e94a1;
    margin-bottom: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.chart-container {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: 2rem 0;
}

.chart-container h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-container h3::before {
    content: '';
    width: 4px;
    height: 28px;
    background: linear-gradient(180deg, #667eea, #764ba2);
    border-radius: 4px;
}

.recent-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.recent-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.recent-table thead th {
    padding: 16px 12px;
    text-align: left;
    color: white !important;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.recent-table thead th:first-child {
    border-radius: 12px 0 0 0;
}

.recent-table thead th:last-child {
    border-radius: 0 12px 0 0;
}

.recent-table tbody tr {
    transition: background 0.2s;
}

.recent-table tbody tr:hover {
    background: #f8f9ff;
}

.recent-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #e8eaf0;
    color: #2c3e50;
    font-size: 0.95rem;
}

.recent-table tbody tr:last-child td {
    border-bottom: none;
}

.header-dashboard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3.5rem 2.5rem;
    border-radius: 20px;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.header-dashboard h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 800;
}

.header-dashboard p {
    opacity: 0.95;
    font-size: 1.2rem;
    font-weight: 300;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .header-dashboard {
        padding: 2rem 1.5rem;
    }
    
    .header-dashboard h1 {
        font-size: 2rem;
    }
}
</style>

<div class="container" style="max-width: 1400px; margin: 40px auto; padding: 0 20px;">
    <div class="header-dashboard">
        <h1>📊 Dashboard</h1>
        <p>Bienvenido de nuevo, <strong><?php echo Auth::username(); ?></strong></p>
    </div>
    
    <div class="dashboard-grid">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                👥
            </div>
            <div class="stat-content">
                <h3>Total Estudiantes</h3>
                <div class="stat-number"><?php echo $totalStudents; ?></div>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon success">
                🎓
            </div>
            <div class="stat-content">
                <h3>Total Carreras</h3>
                <div class="stat-number"><?php echo $totalCarreras; ?></div>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon warning">
                🔐
            </div>
            <div class="stat-content">
                <h3>Total Usuarios</h3>
                <div class="stat-number"><?php echo $totalUsers; ?></div>
            </div>
        </div>
    </div>
    
    <div class="chart-container">
        <h3>📊 Estudiantes por Carrera</h3>
        <canvas id="carrerasChart" style="max-height: 400px;"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>📈 Estudiantes por Ciclo</h3>
        <canvas id="ciclosChart" style="max-height: 300px;"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>🕒 Últimos Registros</h3>
        <table class="recent-table">
            <thead>
                <tr>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem; border-radius: 12px 0 0 0;">DNI</th>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem;">NOMBRES</th>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem;">APELLIDOS</th>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem;">CARRERA</th>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem;">CICLO</th>
                    <th style="background: #34495e; color: #ffffff; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.95rem; border-radius: 0 12px 0 0;">FECHA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentStudents as $student): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($student['dni']); ?></strong></td>
                    <td><?php echo htmlspecialchars($student['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($student['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($student['nombre_carrera'] ?? 'N/A'); ?></td>
                    <td><span style="background: #667eea; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?php echo $student['ciclo']; ?></span></td>
                    <td><?php echo isset($student['created_at']) ? date('d/m/Y', strtotime($student['created_at'])) : 'N/A'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Configuración de colores modernos
const colors = {
    primary: ['#667eea', '#764ba2'],
    success: ['#11998e', '#38ef7d'],
    warning: ['#f093fb', '#f5576c'],
    info: ['#4facfe', '#00f2fe']
};

// Gráfico de carreras mejorado
const carrerasData = <?php echo json_encode($studentsByCarrera); ?>;
const ctxCarreras = document.getElementById('carrerasChart').getContext('2d');

// Crear gradiente para barras
const gradientCarreras = ctxCarreras.createLinearGradient(0, 0, 0, 400);
gradientCarreras.addColorStop(0, 'rgba(102, 126, 234, 0.9)');
gradientCarreras.addColorStop(1, 'rgba(118, 75, 162, 0.7)');

new Chart(ctxCarreras, {
    type: 'bar',
    data: {
        labels: carrerasData.map(item => item.nombre_carrera),
        datasets: [{
            label: 'Número de Estudiantes',
            data: carrerasData.map(item => item.total),
            backgroundColor: gradientCarreras,
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { 
                display: true,
                labels: {
                    color: '#2c3e50',
                    font: { size: 13, weight: '600' }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(44, 62, 80, 0.9)',
                padding: 12,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#7f8c8d',
                    font: { size: 12 }
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    color: '#7f8c8d',
                    font: { size: 11 }
                }
            }
        }
    }
});

// Gráfico de ciclos mejorado
const ciclosData = <?php echo json_encode($studentsByCiclo); ?>;
const ctxCiclos = document.getElementById('ciclosChart').getContext('2d');

// Crear gradiente para área
const gradientCiclos = ctxCiclos.createLinearGradient(0, 0, 0, 300);
gradientCiclos.addColorStop(0, 'rgba(118, 75, 162, 0.4)');
gradientCiclos.addColorStop(1, 'rgba(118, 75, 162, 0.05)');

new Chart(ctxCiclos, {
    type: 'line',
    data: {
        labels: ciclosData.map(item => 'Ciclo ' + item.ciclo),
        datasets: [{
            label: 'Número de Estudiantes',
            data: ciclosData.map(item => item.total),
            backgroundColor: gradientCiclos,
            borderColor: 'rgba(118, 75, 162, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#764ba2',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { 
                display: true,
                labels: {
                    color: '#2c3e50',
                    font: { size: 13, weight: '600' }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(44, 62, 80, 0.9)',
                padding: 12,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#7f8c8d',
                    font: { size: 12 }
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    color: '#7f8c8d',
                    font: { size: 12 }
                }
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
