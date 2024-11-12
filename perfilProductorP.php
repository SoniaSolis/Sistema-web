<?php
session_start();

// Verificar si la sesión contiene datos del productor
if (isset($_SESSION['productor'])) {
    $productor = $_SESSION['productor'];

    // acceder a los campos:
    $nombre_negocio = $productor['nombre_negocio'];
    $imagen_productor = $productor ['imagen_productor'];
    $descripcion = $productor['descripcion'];
    $telefono = $productor['telefono'];
    $horario_atencion = $productor['horario_atencion'];
    $horario_tianguis = $productor['horario_tianguis'];
    $promociones = $productor['promociones'];

    //se conecta a la bd
    require 'conexion.php';

    // Obtiene el id_productor
    $id_productor = $productor['id_productor'];

    // Obtener datos de los productos usando id_productor
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_productor = ?");
    $stmt->bind_param("i", $id_productor);
    $stmt->execute();
    $resultado_productos = $stmt->get_result();

    // Verificar si se encontraron resultados
    $productos_html = '';
    if ($resultado_productos->num_rows > 0) {
        // Recorrer los resultados y generar HTML para cada producto
        while ($producto = $resultado_productos->fetch_assoc()) {
            $productos_html .= '<article class="producto-card">';
            $productos_html .= '<h2>' . htmlspecialchars($producto['nombre_producto']) . '</h2>';
            $productos_html .= '<img class="producto-image" src="imagenes/'.htmlspecialchars($producto['imagen']) . '" style= height: 50px">';
            $productos_html .= '<span class="ranking"></span>';
            $productos_html .= '<span class="descripcion">Descripción <i class="bi bi-caret-down-fill toggle-icon"></i></span>';
            $productos_html .= '<p class="descripcion-producto">' . htmlspecialchars($producto['descripcion_producto']) . '</p>';
            $productos_html .= '<span class="precio">$' . htmlspecialchars($producto['precio']) . '</span>';
            $productos_html .= '<span class="unidad">' . htmlspecialchars($producto['unidad']) . '</span>';
            $productos_html .= '<a href="#" class="producto-button"><i class="bi bi-cart4"></i> Agregar Producto</a>';
            $productos_html .= '</article>';
        }
    } else {
        $productos_html = '<p>No se encontraron productos para este productor.</p>';
    }
} else {
    // Si no hay datos en la sesión, redirigir
    header("Location: registro.php");
    exit();
}
?>



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
    </nav>
  </header>

        
  <nav class="navbar navbar-expand-lg ">
    <div id="barra" class="container-fluid">
      <a class="navbar-brand" >
        <img src="./imagenes/logo.jpg" alt="" width="150px" >
      </a>
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
  

  <div class="abcproductos">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">editar perfil</button>


    <!--modal formulario perfil-->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--aqui se coloca el archivo con consulta sql insert-->
                <form id="editarPerfilForm" method="POST" action="editarperfil.php">
 
                  <!-- Campo oculto para el ID del productor -->
                  <input type="hidden" id="id_productor" name="id_productor" value="<?php echo htmlspecialchars($id_productor); ?>">
 
                  <!-- Campos del formulario -->
                    <div class="mb-3">
                      <label for="nombre_negocio" class="form-label">Nombre del negocio</label>
                      <input type="text" class="form-control" id="nombre_negocio" name="nombre_negocio">
                    </div>
                    <div class="mb-3">
                      <label for="imagen_productor" class="form-label">Logo</label>
                      <input type="file" class="form-control" id="imagen_productor" name="imagen_productor">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="horario_atencion" class="form-label">Horario de atención</label>
                        <input type="text" class="form-control" id="horario_atencion" name="horario_atencion">
                    </div>
                    <div class="mb-3">
                        <label for="horario_tianguis" class="form-label">Horario del tianguis</label>
                        <input type="text" class="form-control" id="horario_tianguis" name="horario_tianguis">
                    </div>
                    <div class="mb-3">
                        <label for="promociones" class="form-label">Promociones</label>
                        <textarea class="form-control" id="promociones" name="promociones" rows="3"></textarea>
                    </div>
                      <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
      </div>
    </div>


    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarProductoModal">editar productos</button>

    <!--modal formulario productos-->
    <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarProductoModalLabel">Editar Productos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editarProductoForm" method="POST" action="editarproducto.php" enctype="multipart/form-data">
              <!-- Campo oculto para el ID del productor -->
              <input type="hidden" id="id_productor" name="id_productor" value="<?php echo htmlspecialchars($id_productor); ?>">
              <!-- Campo oculto para la acción -->
              <input type="hidden" id="accion" name="accion" value="">

              <!-- Campo select para seleccionar un producto -->
              <div class="mb-3">
                <label for="select_producto" class="form-label">Seleccionar Producto</label>
                <select class="form-control" id="select_producto" name="id_producto" onchange="cargarDatosProducto(this.value)">
                  <option value=""></option>
                  <?php
                    // Obtener los productos del productor desde la base de datos
                    $resultado_productos = $conn->query("SELECT id_producto, nombre_producto FROM productos WHERE id_productor = " . intval($id_productor));
                    while ($producto = $resultado_productos->fetch_assoc()) {
                      echo '<option value="' . $producto['id_producto'] . '">' . htmlspecialchars($producto['nombre_producto']) . '</option>';
                    }
                  ?>
                </select>
              </div>

              <!-- Campos del formulario -->
              <div class="mb-3">
                <label for="nombre_producto" class="form-label">Nombre del producto</label>
                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto">
              </div>
              <div class="mb-3">
                <label for="descripcion_producto" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion_producto" name="descripcion_producto" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <textarea class="form-control" id="precio" name="precio"></textarea>
              </div>
              <div class="mb-3">
                <label for="unidad" class="form-label">Unidad</label>
                <textarea class="form-control" id="unidad" name="unidad"></textarea>
              </div>
              <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
              </div>
              <button type="button" class="btn btn-primary" onclick="setAccion('agregar')">Agregar</button>
              <button type="button" class="btn btn-danger" onclick="setAccion('eliminar')">Eliminar</button>
              <button type="button" class="btn btn-warning" onclick="setAccion('editar')">Editar</button>
              <button type="submit" class="btn btn-success">Guardar cambios</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
function setAccion(accion) {
  document.getElementById('accion').value = accion;
  document.getElementById('editarProductoForm').submit();
  }
</script>


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
      <a class="wattsapp" href="<?php echo $enlace_whatsapp; ?>" target="_blank">Enviar mensaje por WhatsApp</a>


      <p>Promociones <br><br> <?php echo nl2br(htmlspecialchars($promociones)); ?></p> 

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