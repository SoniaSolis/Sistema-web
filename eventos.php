<?php
  include 'conexion.php';

  // Fecha actual
  $fecha_actual = date('Y-m-d');

  // Consulta para eventos próximos
  $sql_proximos = "SELECT * FROM eventos WHERE fecha_evento >= ? AND seccion = 'proximos'";
  $stmt_proximos = $conn->prepare($sql_proximos);
  $stmt_proximos->bind_param("s", $fecha_actual);
  $stmt_proximos->execute();
  $resultado_proximos = $stmt_proximos->get_result();
  
  

  // Consulta para eventos pasados
  $sql_pasados = "SELECT * FROM eventos WHERE fecha_evento < ? AND seccion = 'pasados'";
  $stmt_pasados = $conn->prepare($sql_pasados);
  $stmt_pasados->bind_param("s", $fecha_actual);
  $stmt_pasados->execute();
  $resultado_pasados = $stmt_pasados->get_result();

  
  
  // Consulta para difusión
  $sql_difusion = "SELECT * FROM eventos WHERE seccion = 'difusion'";
  $stmt_difusion = $conn->prepare($sql_difusion);
  $stmt_difusion->execute();
  $resultado_difusion = $stmt_difusion->get_result();
 
  
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">

    <title>Tianguis Alternativo Cultural y Artesanal "El Coperativo"</title>
</head>
<body>

  <!-- Colocar la linea de código <?php include 'header.php'; ?> para agregar el header en general -->
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
      <img src="./imagenes/facebook.png" class="img-fluid" alt="" style="width: 30px;">
      <img src="./imagenes/instagram.png" class="img-fluid" alt="" style="width: 30px;">
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

  <div>

    <!-- Sección de próximos eventos -->
    <p class="tituopi">PROXIMOS EVENTOS <br></p>
    <section class="eventos">
        <?php while($evento = $resultado_proximos->fetch_assoc()): ?>
            <div class="even1">
                <img src="imagenes/<?php echo $evento['media']; ?>" alt="Próximo Evento">
                <p></p>
                <p><?php echo $evento['descripcion']; ?></p>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Sección de eventos pasados -->
    <p class="tituopi">EVENTOS PASADOS <br></p>
    <section class="eventos">
        <?php while($evento = $resultado_pasados->fetch_assoc()): ?>
            <div class="even1">
                <img src="imagenes/<?php echo $evento['media']; ?>" alt="Evento Pasado">
                <p></p>
                <p><?php echo $evento['descripcion']; ?></p>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Sección de difusión -->
    <p class="tituopi">DIFUSIÓN <br></p>
    <section class="difu">
        <?php while($evento = $resultado_difusion->fetch_assoc()): ?>
            <div class="dif1">
                <img src="imagenes/<?php echo $evento['media']; ?>" alt="Difusión">
                <p></p>
                <p><?php echo $evento['descripcion']; ?></p>
            </div>
        <?php endwhile; ?>
    </section>
  </div>
  
  <iframe src="./pie.html" frameborder="0"></iframe>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <!-- SweetAlert CDN -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </body>
</html>