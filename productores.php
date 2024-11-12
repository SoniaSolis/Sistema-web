<?php
//se conecta a la bd
require 'conexion.php';

// Obtener filtros de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$letra = isset($_GET['letra']) ? $_GET['letra'] : '';
$giro = isset($_GET['giro']) ? $_GET['giro'] : '';

// Crear la consulta dinámica
$sql = "SELECT * FROM productores WHERE 1=1";

// Filtro de búsqueda por nombre o giro
if (!empty($buscar)) {
    $sql .= " AND (nombre_negocio LIKE ? OR giro LIKE ?)";
}

// Filtro por letra
if (!empty($letra)) {
    $sql .= " AND nombre_negocio LIKE ?";
}

// Filtro por giro
if (!empty($giro)) {
    $sql .= " AND giro = ?";
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Asociar parámetros dinámicamente
$types = '';
$params = [];

if (!empty($buscar)) {
    $types .= 'ss';
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}

if (!empty($letra)) {
    $types .= 's';
    $params[] = "$letra%";
}

if (!empty($giro)) {
    $types .= 's';
    $params[] = $giro;
}

// Ejecutar la consulta
if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado_productores = $stmt->get_result();

// Verificar si se encontraron resultados
$productores_html = '';
if ($resultado_productores->num_rows > 0) {
    while ($productores = $resultado_productores->fetch_assoc()) {
        $productores_html .= '<article class="negocio-card">';
        $productores_html .= '<h2>' . htmlspecialchars($productores['nombre_negocio']) . '</h2>';
        $productores_html .= '<img class="negocio-image" src="imagenes/' . htmlspecialchars($productores['imagen_productor']) . '">';
        $productores_html .= '<a href="perfilProductor.php?id=' . urlencode($productores['id_productor']) . '" class="negocio-button">Ir a Perfil</a>';
        $productores_html .= '</article>';
    }
} else {
    $productores_html = '<p>No se encontraron productores.</p>';
}

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
  <div class="buscar">
    <form action="productores.php" method="get" class="buscador">
      <!-- Búsqueda por nombre o giro -->
      <input  class="busca_entra" type="text" name="buscar" id="buscar" placeholder="Buscar por nombre o giro">

      <!-- Filtro por letra del abecedario -->
      <label for="letra">Filtrar por letra:</label>
      <select name="letra" id="letra">
          <option value="">Todas</option>
          <?php
          foreach (range('A', 'Z') as $letra) {
              echo "<option value='$letra'>$letra</option>";
          }
          ?>
      </select>

      <!-- Filtro por giro -->
      <label for="giro">Filtrar por giro:</label>
      <select name="giro" id="giro">
          <option value="">Todos los giros</option>
          <!-- Aquí debes cargar los giros desde la base de datos -->
          <?php
          $stmt = $conn->prepare("SELECT DISTINCT giro FROM productores");
          $stmt->execute();
          $giros = $stmt->get_result();
          while ($giro = $giros->fetch_assoc()) {
              echo '<option value="' . htmlspecialchars($giro['giro']) . '">' . htmlspecialchars($giro['giro']) . '</option>';
          }
          ?>
      </select>

      <br> <br><button class="boton" type="submit">Buscar</button>
    </form>
  </div>


   

  <section class="contenidoperfil">
  <?php echo $productores_html; ?> 
  </section>

  <br><br> <!--espacios-->

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de imagenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>