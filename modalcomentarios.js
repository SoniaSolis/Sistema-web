// Obtener elementos del DOM (Document Object Model )
var modal = document.getElementById("modalComentario");
var btnAbrir = document.getElementById("abrirModal");
var btnCerrar = document.getElementsByClassName("cerrar")[0];

// Cuando el usuario hace clic en el icono, se abre el modal
btnAbrir.onclick = function() {
  modal.style.display = "block";
}

// Cuando el usuario hace clic en la 'x', se cierra el modal
btnCerrar.onclick = function() {
  modal.style.display = "none";
}

// Cuando el usuario hace clic fuera del modal, se cierra
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
