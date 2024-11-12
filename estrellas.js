document.querySelectorAll('.rating span').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('calificacion').value = value;

        // Elimina la clase 'selected' de todas las estrellas
        document.querySelectorAll('.rating span').forEach(s => {
            s.classList.remove('selected');
        });

        // Agrega la clase 'selected' a todas las estrellas hasta la seleccionada
        for (let i = 0; i < value; i++) {
            document.querySelectorAll('.rating span')[i].classList.add('selected');
        }
    });
});
