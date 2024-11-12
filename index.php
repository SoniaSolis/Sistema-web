<?php
// Conexión a la base de datos
require_once 'conexion.php';

// Obtener todos los comentarios
$stmt = $conn->prepare("SELECT nombre, comentario, calificacion, fecha FROM comentarios");
$stmt->execute();
$resultado_comentarios = $stmt->get_result();

// Array para almacenar los comentarios
$comentarios = [];

while ($comentario = $resultado_comentarios->fetch_assoc()) {
    $comentarios[] = $comentario;
}

$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS local -->
    <link rel="stylesheet" href="./style.css">
   
    <title>Tianguis Alternativo Cultural y Artesanal "El Coperativo"</title>
</head>

<body>

<header>
    <nav class="registro">
      <a href="./registro.php">Registrarse / Acceder</a>
    </nav>
  </header>
        
  <nav class="navbar navbar-expand-lg ">
    <div id="barra" class="container-fluid">
      <a class="navbar-brand" >
        <img src="./imagenes/logo.jpg" alt="" width="150px" >
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="./principal.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./productores.php">Productores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./eventos.php">Eventos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./contacto.html">Contacto</a>
          </li>
          
        </ul>
      </div>
    </div>
  </nav>

  
  
  <div id="carouselExample" class="carousel slide">

  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./imagenes/larg.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="./imagenes/lar2ok.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="./imagenes/la3ok.jpg" class="d-block w-100" alt="...">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>

  </div>

  <p class="espacio"></p>

  <section class="contenido">

    <div class="izq">
      <img src="./imagenes/pr.jpg" >
      <p> </p>
      <p style="font-size: 20px;">El Tianguis Alternativo Cultural y Artesanal "El Cooperativo" es un espacio donde se encuentran productos sustentables, libres de residuos y elaborados con conciencia ambiental. Aquí, la gastronomía tradicional mexicana y las creaciones locales destacan, ofreciendo a los visitantes una experiencia auténtica y comprometida con la comunidad y el medio ambiente.</p>
    </div>

    <div class="der">
      <iframe  src="https://www.youtube.com/embed/p-z5asXkOY8?si=qwu0RAWlcHnERMDT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
  
  </section>

  <p class="tituopi">OPINIONES DE NUESTROS CLIENTES <br> <br> Dejanos un comentario! <i class="bi bi-caret-down-square-fill" id="abrirModal" ></i> </p>

  <style>
  .star {
    color: green; /* Cambia el color de las estrellas */  
  }
  .card{
    background-color: whitesmoke;
  }
  </style>


  <section class="opiniones">
    <section id="carouselComentarios" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      // Variable para manejar el índice de los comentarios
      $active_class = 'active'; // Para la primera iteración

      // Mostrar los comentarios en grupos de 4
      for ($i = 0; $i < count($comentarios); $i += 4) {
          echo '<div class="carousel-item ' . $active_class . '">';
          $active_class = ''; // Solo la primera slide es "active"

          // Dentro de cada "carousel-item" mostramos 4 comentarios
          echo '<div class="row">';
          for ($j = $i; $j < $i + 4 && $j < count($comentarios); $j++) {
              echo '<div class="col-md-3">';
              echo '<div class="card">';
              echo '<div class="card-body">';
              echo '<p>' . htmlspecialchars($comentarios[$j]['nombre']) . '</p>'; 
              echo '<p>' . nl2br(htmlspecialchars($comentarios[$j]['comentario'])) . '</p>';
              echo '<p>' . htmlspecialchars($comentarios[$j]['fecha']) . '</p>';
              echo '<p>';
              for ($k = 1; $k <= 5; $k++) {
                  if ($k <= $comentarios[$j]['calificacion']) {
                      echo "<span class='star'>&#9733;</span>"; // Estrella llena
                  } else {
                      echo "<span class='star'>&#9734;</span>"; // Estrella vacía
                  }
              }
              echo '</p>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
          }
          echo '</div>'; // Fin del row
          echo '</div>'; // Fin del carousel-item
      }
      ?>
    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselComentarios" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselComentarios" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
    </section>
  </section>

  

  <!-- Modal -->
  <div id="modalComentario" class="modal">
    <div class="modal-contenido">
      <span class="cerrar">&times;</span>
      <h2>¡Compartenos tu opinión!<i class="bi bi-emoji-sunglasses-fill"></i> </h2>
      <form id="formComentario" action="procesarcomentarios.php" method="POST"> <br>
        <p>Ingresa tu nombre o escribe Anónimo:</p>
        <input id="nombre" name="nombre" maxlength="20" placeholder="Nombre o Anónimo"> <br> <br>
        <p>Ingresa tu comentario:</p>
        <textarea id="comentario" name="comentario" rows="4" cols="30" maxlength="150" placeholder="Cuéntanos tu experiencia..."></textarea>

        <br><br><p style="font-size: 15px; font-weight: bold">Calificanos!</p>
        <div class="rating">
        <span data-value="1">&#9734;  </span>
        <span data-value="2">&#9734;  </span>
        <span data-value="3">&#9734;  </span>
        <span data-value="4">&#9734;  </span>
        <span data-value="5">&#9734;  </span>
        </div>
        <input type="hidden" id="calificacion" name="calificacion">
        <br>
        <input class="coment" type="submit" value="Enviar comentario">
      </form>
    </div>
  </div>

  <br><br>

  <section class="ranking">
      <p class="nu" >4.5 
      <br> <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"><i class="bi bi-star-half"></i></i></p> <br> <br>
      <p class="nu"> 3.5 mil <br>ME GUSTA</p> 
  </section>
  

  <p></p>
  <p class="tituopi">CONOCE NUESTRA UBICACIÓN <br> </p>

  <section class="mapa">
    <div class="locali">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3761.2898010824815!2d-98.88558762478425!3d19.486163781804702!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1e77699348f8f%3A0x83d231d74ea75d17!2sTianguis%20alternativo%2C%20cultural%20y%20artesanal%20%22El%20cooperativo%22!5e0!3m2!1ses-419!2smx!4v1718779408260!5m2!1ses-419!2smx" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      
  </section>

  <p></p>
  <p class="titfinal">Dirección <br> C. Igualdad 12, El Cooperativo, 56225 El Cooperativo, Méx.</p>
  
  

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de imagenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
 
  <!-- SweetAlert CDN -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!--scrip para el modal de comentarios-->
  <script src="./modalcomentarios.js"></script>
  <script src="./mencomen.js"></script>
  <script src="./estrellas.js"></script>
  <script src="./contador.js"></script>
  




 
</body>
</html>

