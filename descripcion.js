document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los íconos de despliegue y las descripciones de los productos
    const toggleIcons = document.querySelectorAll('.toggle-icon');
    const productDescriptions = document.querySelectorAll('.descripcion-producto');

    toggleIcons.forEach(function(toggleIcon, index) {
        // Al hacer clic en el ícono de cada producto, despliega su descripción correspondiente
        toggleIcon.addEventListener('click', function() {
            const productDescription = productDescriptions[index];
            
            if (productDescription.style.display === 'none' || productDescription.style.display === '') {
                productDescription.style.display = 'block';
                toggleIcon.classList.remove('bi-caret-down-fill');
                toggleIcon.classList.add('bi-caret-up-fill'); // Cambiar el icono a un triángulo hacia arriba
            } else {
                productDescription.style.display = 'none';
                toggleIcon.classList.remove('bi-caret-up-fill');
                toggleIcon.classList.add('bi-caret-down-fill'); // Cambiar el icono a un triángulo hacia abajo
            }
        });
    });
});
