<?php

// Se conecta a la base de datos
require 'conexion.php';

// Obtener el ID del productor desde la URL
$id_productor = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_productor > 0) {
    // Obtener datos del productor seleccionado
    $stmt = $conn->prepare("SELECT * FROM productores WHERE id_productor = ?");
    $stmt->bind_param("i", $id_productor);
    $stmt->execute();
    $resultado_productor = $stmt->get_result()->fetch_assoc();
    
    $nombre_negocio = $resultado_productor['nombre_negocio'];
    $imagen_productor = $resultado_productor ['imagen_productor'];
    $descripcion = $resultado_productor['descripcion'];
    $telefono = $resultado_productor['telefono'];
    $horario_atencion = $resultado_productor['horario_atencion'];
    $horario_tianguis = $resultado_productor['horario_tianguis'];
    $promociones = $resultado_productor['promociones'];
    
    // Obtener productos del productor seleccionado
    $stmt_productos = $conn->prepare("SELECT * FROM productos WHERE id_productor = ?");
    $stmt_productos->bind_param("i", $id_productor);
    $stmt_productos->execute();
    $resultado_productos = $stmt_productos->get_result();

    // Generar HTML para los productos
    $productos_html = '';
    if ($resultado_productos->num_rows > 0) {
        while ($producto = $resultado_productos->fetch_assoc()) {
            $productos_html .= '<article class="producto-card">';
            $productos_html .= '<h2>' . htmlspecialchars($producto['nombre_producto']) . '</h2>';
            $productos_html .= '<img class="producto-image" src="imagenes/'.htmlspecialchars($producto['imagen']) . '">';
            $productos_html .= '<span class="ranking"></span>';
            
            
            
            $productos_html .= '<span class="descripcion">Descripción <i class="bi bi-caret-down-fill toggle-icon"></i></span>';
            $productos_html .= '<p class="descripcion-producto">' . htmlspecialchars($producto['descripcion_producto']) . '</p>';
           
            
        
            $productos_html .= '<span class="precio">$' . htmlspecialchars($producto['precio']) . '</span>';
            $productos_html .= '<span class="unidad">' . htmlspecialchars($producto['unidad']) . '</span> <br> ';
           
            $productos_html .= '<span class="comentarios"><i class="bi bi-chat-left-heart"></i></span><br> ';
            $productos_html .= '<span class="calificaciones"> <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span> <br> ';


            $productos_html .= '<button class="producto-button" onclick="agregarProducto(\'' . htmlspecialchars($producto['id_producto']) . '\', \'' . htmlspecialchars($producto['nombre_producto']) . '\', \'' . htmlspecialchars($producto['precio']) . '\', \'' . htmlspecialchars($producto['imagen']) . '\')"><i class="bi bi-cart4"></i> Agregar Producto</button>';
            $productos_html .= '</article>';  
          } 
        }else {
        $productos_html = '<p>No se encontraron productos para este productor.</p>';
       }
} else {
    echo '<p>ID de productor no válido.</p>';
    exit;
}
//cierra la declaracion 
$stmt->close();
//cierra la conexión
$conn->close();

?>

<script>
function agregarProducto(id_producto, nombre, precio, imagen) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "carrito.php?action=add&id_producto=" + encodeURIComponent(id_producto) + "&nombre=" + encodeURIComponent(nombre) + "&precio=" + encodeURIComponent(precio) + "&imagen=" + encodeURIComponent(imagen), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert('Producto agregado');
        }
    };
    xhr.send();
}
</script>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
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
      <a href="./cerrar.php">cerrar sesion</a>
      <a href="./carrito.php"><i class="bi bi-cart4" style="font-size: 2rem"></i></a>
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

  <br><br> <!--espacios-->

  <div class="contenido-all">
    
    <div class="Perfil">
      <!-- modificque la linea para la imagen del perfil -->
      <img src="imagenes/<?php echo htmlspecialchars($imagen_productor); ?>" alt="Imagen productor" class="foto-perfil"> 

      <p><strong>Nombre del negocio:</strong> <?php echo nl2br( htmlspecialchars($nombre_negocio)); ?>
    
        <br><br>
        
        DESCRIPCIÓN O HISTORIA DEL NEGOCIO
        <?php echo nl2br(htmlspecialchars($descripcion)); ?>
        <br><br>
        CONTACTANOS <br>
        
        Teléfono <br>
        <?php echo nl2br(htmlspecialchars($telefono)); ?>
        <br><br>
        Horarios de atención <br>
        <?php echo nl2br(htmlspecialchars($horario_atencion)); ?>
        <br><br>
        Horario del tianguis <br>
        <?php echo nl2br(htmlspecialchars($horario_tianguis)); ?>
      </p>
      <?php
      $mensaje = "Hola, me gustaría saber más sobre sus productos.";

      // Generar el enlace de WhatsApp
      $enlace_whatsapp = "https://wa.me/" . str_replace(['+', ' '], '', $telefono) . "?text=" . urlencode($mensaje);
      ?>

      <!-- Mostrar el enlace en la página -->
      <a class="wattsapp" href="<?php echo $enlace_whatsapp; ?>" target="_blank" >Enviar mensaje por <br>  <i class="bi bi-whatsapp"></i> WhatsApp   </a>

      <div class="promociones">
        <p > ¡PROMOCIONES! <br><br> <?php echo nl2br(htmlspecialchars($promociones)); ?></p> 
      </div>
      

    </div>

    <!--muestra  todos los productos disponibles en los articles-->
    <section class="productos">
    <?php echo $productos_html; ?>  
    </section>
  </div>

  <br><br> <!--espacios-->

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de imagenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <!-- script para que se despliegue la descripción del producto-->
  <script src="./descripcion.js"></script> 

  <!-- script para que funcione el modal formulario editar-perfil-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>