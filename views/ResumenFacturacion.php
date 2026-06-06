<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ResumenController.php';

if (!AuthController::estaLogueado()) {
    header('Location: index.php?accion=Nosotros');
    exit;
}

$stats = ResumenController::obtenerStats();
$ventasRecientes = ResumenController::obtenerVentasRecientes(10);
?>
<link rel="stylesheet" href="css/style.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: 4px solid #781E24;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(120, 30, 36, 0.15);
        border-top-color: #D39C24;
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background-color: rgba(120, 30, 36, 0.08);
        color: #781E24;
    }
    .stat-card:hover .stat-icon {
        background-color: rgba(211, 156, 36, 0.1);
        color: #D39C24;
        transition: all 0.3s;
    }
    .table-uta-header {
        background-color: #781E24 !important;
        color: #ffffff !important;
    }
    .flash-highlight {
        animation: pulseHighlight 0.8s ease-in-out;
    }
    @keyframes pulseHighlight {
        0% { background-color: rgba(211, 156, 36, 0.25); transform: scale(1.02); }
        100% { background-color: transparent; transform: scale(1); }
    }
</style>

<div class="uta-container py-4">
    <h1 class="uta-title">Resumen de Facturación</h1>
    

    <!-- Fila de Tarjetas Informativas -->
    <div class="row mb-5 justify-content-center">
        <!-- Tarjeta 1: Total Facturas -->
        <div class="col-md-6 col-lg-5">
            <div class="stat-card p-4 d-flex align-items-center justify-content-between" id="cardFacturas">
                <div>
                    <span class="text-uppercase tracking-wider text-muted small fw-bold d-block mb-1">Total Facturas Generadas</span>
                    <h3 class="fs-1 fw-bold mb-0" id="txtTotalFacturas" style="color: #781E24;">
                        <?= (int) $stats['total_facturas'] ?>
                    </h3>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas Recientes -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h2 class="h5 mb-0 fw-bold" style="color: #781E24;">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>Últimas Facturas Registradas
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaFacturas">
                    <thead>
                        <tr>
                            <th class="table-uta-header px-4 py-3" style="width: 15%;">Nro. Factura</th>
                            <th class="table-uta-header px-4 py-3" style="width: 35%;">Fecha y Hora</th>
                            <th class="table-uta-header px-4 py-3 text-end" style="width: 20%;">Monto Total</th>
                            <th class="table-uta-header px-4 py-3 text-center" style="width: 30%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaFacturas">
                        <?php if (empty($ventasRecientes)): ?>
                            <tr id="filaVacia">
                                <td colspan="4" class="text-center text-muted py-4">No se han registrado facturas todavía.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventasRecientes as $v): ?>
                                <tr id="fila-<?= (int) $v['id'] ?>">
                                    <td class="px-4 fw-bold">#<?= (int) $v['id'] ?></td>
                                    <td class="px-4 text-muted"><?= htmlspecialchars($v['fecha']) ?></td>
                                    <td class="px-4 text-end fw-bold text-danger">$<?= number_format((float) $v['total'], 2) ?></td>
                                    <td class="px-4 text-center">
                                        <a href="index.php?accion=Factura&venta_id=<?= (int) $v['id'] ?>" class="btn btn-sm btn-outline-danger me-2">
                                            <i class="fa-solid fa-eye"></i> Ver
                                        </a>
                                        <a href="reportes/factura_venta.php?venta_id=<?= (int) $v['id'] ?>" target="_blank" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-print"></i> Imprimir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let anteriorTotalFacturas = <?= (int) $stats['total_facturas'] ?>;

    function formatCurrency(value) {
        return '$' + parseFloat(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function actualizarDashboard() {
        fetch('index.php?accion=ResumenFacturacion&api=1')
            .then(response => {
                if (!response.ok) throw new Error('Error de red');
                return response.json();
            })
            .then(data => {
                if (!data || !data.stats) return;

                const stats = data.stats;
                const actualesTotal = parseInt(stats.total_facturas, 10);

                // Elementos del DOM
                const elTotalFacturas = document.getElementById('txtTotalFacturas');

                // Si ha cambiado el número total de facturas, refrescamos y aplicamos micro-animaciones
                if (actualesTotal !== anteriorTotalFacturas) {
                    anteriorTotalFacturas = actualesTotal;

                    // Actualizar textos
                    if (elTotalFacturas) {
                        elTotalFacturas.textContent = stats.total_facturas;
                    }

                    // Agregar efectos visuales de destello/pulso
                    const card = document.getElementById('cardFacturas');
                    if (card) {
                        card.classList.remove('flash-highlight');
                        // Forzar reflujo de la animación
                        void card.offsetWidth;
                        card.classList.add('flash-highlight');
                    }

                    // Actualizar tabla de ventas recientes
                    const cuerpoTabla = document.getElementById('cuerpoTablaFacturas');
                    if (cuerpoTabla && data.recientes) {
                        if (data.recientes.length === 0) {
                            cuerpoTabla.innerHTML = `<tr id="filaVacia"><td colspan="4" class="text-center text-muted py-4">No se han registrado facturas todavía.</td></tr>`;
                        } else {
                            let html = '';
                            data.recientes.forEach(v => {
                                const id = parseInt(v.id, 10);
                                html += `
                                    <tr id="fila-${id}">
                                        <td class="px-4 fw-bold">#${id}</td>
                                        <td class="px-4 text-muted">${v.fecha}</td>
                                        <td class="px-4 text-end fw-bold text-danger">${formatCurrency(v.total)}</td>
                                        <td class="px-4 text-center">
                                            <a href="index.php?accion=Factura&venta_id=${id}" class="btn btn-sm btn-outline-danger me-2">
                                                <i class="fa-solid fa-eye"></i> Ver
                                            </a>
                                            <a href="reportes/factura_venta.php?venta_id=${id}" target="_blank" class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-print"></i> Imprimir
                                            </a>
                                        </td>
                                    </tr>
                                `;
                            });
                            cuerpoTabla.innerHTML = html;
                        }
                    }
                }
            })
            .catch(err => console.error('Error actualizando resumen de facturación:', err));
    }

    // Polling cada 5 segundos para actualización automática en tiempo real
    setInterval(actualizarDashboard, 5000);
})();
</script>
