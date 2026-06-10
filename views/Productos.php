<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

if (!AuthController::esAdmin()) {
    echo '<div class="alert alert-danger m-4">Acceso restringido a administradores.</div>';
    return;
}

$productos = ProductoModel::listar();
$editarId = isset($_GET['editar']) ? (int) $_GET['editar'] : 0;
$editar = $editarId ? ProductoModel::obtenerPorId($editarId) : null;
?>
<!-- Carga de Bootstrap CSS y JS para soporte de Modales -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0" style="color:#781E24; font-weight: bold;">Administración de productos</h1>
        <!-- Botón para abrir modal de agregar producto -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="prepararNuevo()">
            <i class="fa-solid fa-plus me-1"></i> Agregar nuevo producto
        </button>
    </div>

    <?php if (!empty($_SESSION['producto_ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['producto_ok']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['producto_ok']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['producto_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['producto_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['producto_error']); ?>
    <?php endif; ?>

    <!-- Buscador por nombre (Búsqueda por inicio) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="buscadorNombre" class="form-control border-start-0 ps-1" 
                       placeholder="Buscar producto por nombre (empieza con...)" oninput="filtrarProductos()">
            </div>
        </div>
    </div>

    <!-- Tabla de Productos -->
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaProductos">
                    <thead style="background-color: #781E24; color: #ffffff;">
                        <tr>
                            <th class="px-4 py-3" style="background-color: #781E24; color: #ffffff; width: 8%;">ID</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 12%;">Imagen</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 25%;">Nombre</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 25%;">Descripción</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 10%;">Precio</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 10%;">Stock</th>
                            <th style="background-color: #781E24; color: #ffffff; width: 10%;">Estado</th>
                            <th class="text-center" style="background-color: #781E24; color: #ffffff; width: 15%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No se han registrado productos.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr id="prod-row-<?= (int) $p['id'] ?>" data-nombre="<?= htmlspecialchars($p['nombre']) ?>">
                                    <td class="px-4 fw-bold">#<?= (int) $p['id'] ?></td>
                                    <td>
                                        <img src="<?= htmlspecialchars(ProductoController::rutaImagen($p['imagen'])) ?>"
                                             alt="" width="60" height="60" class="rounded border shadow-sm" style="object-fit:cover">
                                    </td>
                                    <td class="fw-semibold"><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td class="text-muted small"><?= htmlspecialchars($p['descripcion']) ?></td>
                                    <td class="fw-bold text-danger">$<?= number_format((float) $p['precio'], 2) ?></td>
                                    <td>
                                        <?php if ((int)$p['stock'] > 0): ?>
                                            <span class="badge bg-success"><?= (int) $p['stock'] ?> uds</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Sin Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ((int)$p['activo'] === 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <a href="index.php?accion=Productos&editar=<?= (int) $p['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form method="post" action="index.php?accion=Productos" class="m-0"
                                                  onsubmit="return confirm('¿Seguro que desea eliminar/desactivar este producto?');">
                                                <input type="hidden" name="accion_producto" value="eliminar">
                                                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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

<!-- Modal para Crear/Editar Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalProductoLabel"><?= $editar ? 'Editar producto' : 'Nuevo producto' ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="cancelarEdicion()"></button>
            </div>
            <form id="formProducto" method="post" action="index.php?accion=Productos" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="accion_producto" value="<?= $editar ? 'actualizar' : 'crear' ?>">
                    <?php if ($editar): ?>
                        <input type="hidden" name="id" value="<?= (int) $editar['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= $editar ? htmlspecialchars($editar['nombre']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required><?= $editar ? htmlspecialchars($editar['descripcion']) : '' ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Precio</label>
                            <input type="text" id="precio" name="precio" class="form-control" required placeholder="0.00"
                                   value="<?= $editar ? (float) $editar['precio'] : '' ?>">
                            <div class="form-text text-muted" style="font-size:0.75rem;">Solo números y un punto decimal.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stock disponible</label>
                            <input type="text" id="stock" name="stock" class="form-control" required placeholder="0"
                                   value="<?= $editar ? (int) $editar['stock'] : '0' ?>">
                            <div class="form-text text-muted" style="font-size:0.75rem;">Solo números enteros.</div>
                        </div>
                    </div>
                    <?php if ($editar): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1" <?= (int)$editar['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= (int)$editar['activo'] === 0 ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Imagen<?= $editar ? ' (opcional)' : '' ?></label>
                        <input type="file" name="imagen" class="form-control" accept="image/*">
                        <?php if ($editar): ?>
                            <small class="text-muted d-block mt-1">Imagen actual: <strong><?= htmlspecialchars($editar['imagen']) ?></strong></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cancelarEdicion()">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><?= $editar ? 'Guardar cambios' : 'Crear producto' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para filtrar productos por nombre en tiempo real (Búsqueda por inicio)
function filtrarProductos() {
    var query = document.getElementById('buscadorNombre').value.toLowerCase().trim();
    var filas = document.querySelectorAll('#tablaProductos tbody tr:not(#filaVacia)');
    var totalFilasVisibles = 0;
    
    filas.forEach(function(fila) {
        var nombre = fila.getAttribute('data-nombre').toLowerCase().trim();
        if (nombre.startsWith(query)) {
            fila.style.display = '';
            totalFilasVisibles++;
        } else {
            fila.style.display = 'none';
        }
    });

    var filaVacia = document.getElementById('filaVacia');
    if (totalFilasVisibles === 0) {
        if (!filaVacia) {
            var tbody = document.querySelector('#tablaProductos tbody');
            filaVacia = document.createElement('tr');
            filaVacia.id = 'filaVacia';
            filaVacia.innerHTML = '<td colspan="8" class="text-center text-muted py-4">No se encontraron productos que empiecen con "' + query + '".</td>';
            tbody.appendChild(filaVacia);
        }
    } else {
        if (filaVacia) {
            filaVacia.remove();
        }
    }
}

// Restricción de caracteres del teclado en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    var precioInput = document.getElementById('precio');
    var stockInput = document.getElementById('stock');

    if (precioInput) {
        precioInput.addEventListener('keypress', function(e) {
            var charCode = e.which ? e.which : e.keyCode;
            var charStr = String.fromCharCode(charCode);
            
            // Permitir números enteros
            if (/[0-9]/.test(charStr)) {
                return true;
            }
            
            // Permitir un solo punto decimal
            if (charStr === '.') {
                if (this.value.indexOf('.') === -1) {
                    return true;
                }
            }
            
            e.preventDefault();
            return false;
        });

        // Limpieza de pegado de texto no permitido
        precioInput.addEventListener('input', function() {
            var val = this.value;
            // Eliminar caracteres no válidos
            val = val.replace(/[^0-9.]/g, '');
            // Evitar más de un punto
            var parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
            this.value = val;
        });
    }

    if (stockInput) {
        stockInput.addEventListener('keypress', function(e) {
            var charCode = e.which ? e.which : e.keyCode;
            var charStr = String.fromCharCode(charCode);
            
            // Permitir solo números
            if (!/[0-9]/.test(charStr)) {
                e.preventDefault();
                return false;
            }
            return true;
        });

        stockInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Auto-mostrar el modal si se está editando un producto
    <?php if ($editar): ?>
        var myModal = new bootstrap.Modal(document.getElementById('modalProducto'));
        myModal.show();
    <?php endif; ?>
});

// Limpia el formulario y resetea las etiquetas para una nueva creación
function prepararNuevo() {
    var label = document.getElementById('modalProductoLabel');
    if (label) label.textContent = 'Nuevo producto';
    
    var form = document.getElementById('formProducto');
    if (form) {
        form.reset();
        var inputAccion = form.querySelector('input[name="accion_producto"]');
        if (inputAccion) inputAccion.value = 'crear';
        
        var inputId = form.querySelector('input[name="id"]');
        if (inputId) inputId.remove();
        
        var imgSmall = form.querySelector('.modal-body small');
        if (imgSmall) imgSmall.remove();
    }
}

// Redirecciona al cancelar en caso de estar editando para limpiar el query param ?editar=
function cancelarEdicion() {
    <?php if ($editar): ?>
        window.location.href = 'index.php?accion=Productos';
    <?php endif; ?>
}
</script>
