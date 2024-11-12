function validarFormulario() {
    var nombre = document.getElementById('nombre_productor').value;
    var correo = document.getElementById('correo').value;
    var fechaIngreso = document.getElementById('fecha_ingreso').value;
    var ubicacion = document.getElementById('ubicacion').value;
    var contrasena = document.getElementById('contrasena').value;

    var errores = [];

    if (nombre.trim() === '') {
        errores.push('El nombre del productor es requerido');
    }

    if (!/^\S+@\S+\.\S+$/.test(correo)) {
        errores.push('Por favor, introduce un correo electrónico válido');
    }

    if (fechaIngreso.trim() === '') {
        errores.push('La fecha de ingreso es requerida');
    } else if (!/^\d{4}-\d{2}-\d{2}$/.test(fechaIngreso)) {
        errores.push('El formato de fecha debe ser YYYY-MM-DD');
    }

    if (ubicacion.trim() === '') {
        errores.push('La ubicación es requerida');
    }

    if (contrasena.length < 8) {
        errores.push('La contraseña debe tener al menos 8 caracteres');
    }

    if (errores.length > 0) {
        alert(errores.join('\n'));
        return false;
    }

    return true;
}