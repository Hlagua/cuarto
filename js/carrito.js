/**
 * Calcula subtotales por línea y el total general del carrito (solo presentación).
 */
(function () {
    function formatear(num) {
        return '$' + num.toFixed(2);
    }

    function recalcular() {
        var total = 0;
        var filas = document.querySelectorAll('#tablaCarrito tbody tr');
        filas.forEach(function (fila) {
            var precio = parseFloat(fila.getAttribute('data-precio')) || 0;
            var input = fila.querySelector('.cantidad-input');
            var cantidad = input ? parseInt(input.value, 10) || 1 : 1;
            var subtotal = precio * cantidad;
            var celda = fila.querySelector('.subtotal-linea');
            if (celda) {
                celda.textContent = formatear(subtotal);
            }
            total += subtotal;
        });
        var totalEl = document.getElementById('totalGeneral');
        if (totalEl) {
            totalEl.textContent = formatear(total);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        recalcular();
        document.querySelectorAll('.cantidad-input').forEach(function (input) {
            input.addEventListener('input', recalcular);
            input.addEventListener('change', function () {
                var formId = input.getAttribute('form');
                if (formId) {
                    var form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        });
    });
})();
