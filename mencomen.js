// Seleccionar todas las estrellas y agregar un event listener
document.querySelectorAll('.rating span').forEach(function(star) {
    star.addEventListener('click', function() {
        const calificacion = this.getAttribute('data-value');
        
        // Asignar el valor de la calificación al input hidden
        document.getElementById('calificacion').value = calificacion;
        
        // Cambiar el aspecto de las estrellas seleccionadas
        document.querySelectorAll('.rating span').forEach(function(s) {
            s.innerHTML = s.getAttribute('data-value') <= calificacion ? '&#9733;' : '&#9734;';
        });
    });
});

document.getElementById('formComentario').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario recargue la página

    const nombre = document.getElementById('nombre').value.trim() || 'Anónimo';
    const comentario = document.getElementById('comentario').value;
    const calificacion = document.getElementById('calificacion').value;

    if (!comentario) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El comentario no puede estar vacío.',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    fetch('procesarcomentarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `nombre=${encodeURIComponent(nombre)}&comentario=${encodeURIComponent(comentario)}&calificacion=${encodeURIComponent(calificacion)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
                confirmButtonText: 'Entendido'
            });
        } else if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito!',
                text: data.message,
                confirmButtonText: 'Genial'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
