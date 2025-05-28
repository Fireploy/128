function formatearPeso(numero) {
    return '$ ' + numero.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}