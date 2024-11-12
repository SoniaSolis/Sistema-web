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

<header>
    <nav class="registro">
      <a href="./registro.php">Registrarse / Acceder</a>
      <a href="./cerrar.php">cerrar sesion</a>
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


  <section class="mostrarproductosagregados">
  <?php
  session_start();

  // Verifica si el carrito existe en la sesión
  if (!isset($_SESSION['carrito'])) {
      $_SESSION['carrito'] = [];
  }

  // Acción para agregar productos al carrito
  if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id_producto']) && isset($_GET['nombre']) && isset($_GET['precio']) && isset($_GET['imagen'])) {
      $id_producto = $_GET['id_producto'];
      $nombre = $_GET['nombre'];
      $precio = $_GET['precio'];
      $imagen = $_GET['imagen'];
      $cantidad = isset($_GET['cantidad']) ? intval($_GET['cantidad']) : 1; // Obtener la cantidad seleccionada

      // Agrega el producto al carrito
      $_SESSION['carrito'][] = [
          'id_producto' => $id_producto,
          'nombre' => $nombre,
          'precio' => $precio,
          'imagen' => $imagen,
          'cantidad' => $cantidad
      ];

      // Redirige para evitar el reenvío del formulario
      header('Location: carrito.php');
      exit;
  }

  // Acción para eliminar productos del carrito
  if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id_producto'])) {
      $id_producto = $_GET['id_producto'];
      foreach ($_SESSION['carrito'] as $key => $producto) {
          if ($producto['id_producto'] == $id_producto) {
              unset($_SESSION['carrito'][$key]);
              break;
          }
      }
      // Re-indexa el array para mantener los índices consecutivos
      $_SESSION['carrito'] = array_values($_SESSION['carrito']);
      header('Location: carrito.php');
      exit;
  }

  // Acción para actualizar la cantidad de un producto
  if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id_producto']) && isset($_GET['cantidad'])) {
      $id_producto = $_GET['id_producto'];
      $cantidad = intval($_GET['cantidad']);

      foreach ($_SESSION['carrito'] as $key => $producto) {
          if ($producto['id_producto'] == $id_producto) {
              $_SESSION['carrito'][$key]['cantidad'] = $cantidad;
              break;
          }
      }

      // Redirige para evitar el reenvío del formulario
      header('Location: carrito.php');
      exit;
  }

  // Calcular el total de la compra
  $totalCompra = 0;
  if (!empty($_SESSION['carrito'])) {
      foreach ($_SESSION['carrito'] as $producto) {
          $totalCompra += $producto['precio'] * $producto['cantidad'];
      }
  }

  // Muestra los productos en el carrito
  echo '<h2>Carrito de Compras</h2>';
  if (!empty($_SESSION['carrito'])) {
      echo '<ul>';
      foreach ($_SESSION['carrito'] as $producto) {
          echo '<li>';
          echo '<img src="imagenes/' . htmlspecialchars($producto['imagen']) . '" alt="' . htmlspecialchars($producto['nombre']) . '">';
          echo '<h3>' . htmlspecialchars($producto['nombre']) . '</h3>';
          echo '<p>Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
          echo '<form action="carrito.php" method="get">';
          echo '<input type="hidden" name="action" value="update">';
          echo '<input type="hidden" name="id_producto" value="' . htmlspecialchars($producto['id_producto']) . '">';
          echo '<label for="cantidad">Cantidad:</label>';
          echo '<select name="cantidad" id="cantidad">';
          for ($i = 1; $i <= 10; $i++) {
              echo "<option value=\"$i\"" . ($producto['cantidad'] == $i ? " selected" : "") . ">$i</option>";
          }
          echo '</select>';
          echo '<input type="submit" value="Actualizar cantidad">';
          echo '</form>';
          echo '<a href="carrito.php?action=remove&id_producto=' . htmlspecialchars($producto['id_producto']) . '" class="producto-button">Eliminar Producto</a>';
          echo '</li>';
      }
      echo '</ul>';
      // Muestra el total de la compra
    echo '<h3>Total de la Compra: $' . number_format($totalCompra, 2) . '</h3>';
  } else {
      echo '<p>El carrito está vacío.</p>';
  }
  ?>

  <!--Botón para enviar lista de compras--> 
  <form action="procesar_compra.php" method="post">
  <input class="procesar" type="submit" value="Enviar lista de compras">
  </form>
  
</section>


  

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de imagenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>



 
</body>
</html>