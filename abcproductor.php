<?php
session_start();
//verifica que haya sesion de lo contrario solicita el registro
if (!isset($_SESSION['correo'])) {
    header('Location: registro.php');
    exit;
}

// Conectar a la base de datos
require 'conexion.php';

// Obtener los datos de los productores y los usuarios
$sql = "SELECT productores.id_productor, productores.nombre_productor, productores.nombre_negocio, productores.horario_tianguis, productores.fecha_ingreso, productores.giro, productores.ubicacion, usuarios.correo, usuarios.contrasena
        FROM productores
        JOIN usuarios ON productores.id_usuario = usuarios.id_usuario";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!--envia la alerta correspondiente de acuerdo al evento-->
<?php if (isset($_SESSION['status'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($_SESSION['status'] == 'agregado'): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Evento agregado correctamente.'
                });
            <?php elseif ($_SESSION['status'] == 'eliminado'): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Evento eliminado correctamente.'
                });
            <?php elseif ($_SESSION['status'] == 'pagregado'): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Productor agregado correctamente.'
                });
            <?php elseif ($_SESSION['status'] == 'peliminado'): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Productor eliminado correctamente.'
                });
            <?php elseif ($_SESSION['status'] == 'peditado'): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Productor editado correctamente.'
                });
            <?php elseif ($_SESSION['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al realizar la acción.'
                });
            <?php endif; ?>
        });
    </script>
    <?php unset($_SESSION['status']); // Limpiar el mensaje de sesión después de mostrarlo ?>
<?php endif; ?>



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
      <a href="./registro.php">Cerrar sesión</a>
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

  <section class="abcproductores">
  
    <p style="font-size: 24px; font-weight: bold; align-content: center " >Bienvenido, eliga la acción a realizar</p>

    <div class="accionesadmin">
      
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarProductorModal">editar productores</button><br><br>

      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarEvento">Editar Eventos</button>
    </div>


    <!-- Modal formulario productores -->
    <div class="modal fade" id="editarProductorModal" tabindex="-1" aria-labelledby="editarProductorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarProductorModalLabel">Editar Productores</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editarProductorForm" method="POST" action="editarproductores.php" enctype="multipart/form-data" >
              <!-- Campo oculto para el ID del productor -->
              <input type="hidden" id="id_productor" name="id_productor" value="<?php echo htmlspecialchars($id_productor); ?>">
              <!-- Campo oculto para la acción -->
              <input type="hidden" id="accionproductor" name="accion" value="">

              <!-- Campo select para seleccionar un productor -->
              <div class="mb-3">
                <label for="select_productor" class="form-label">Seleccionar Productor</label>
                <select class="form-control" id="select_productor" name="id_productor" onchange="cargarDatosProductor(this.value)">
                  <option value=""></option>
                  <?php
                    // Obtener los productores desde la base de datos
                    $resultado_productores = $conn->query("SELECT id_productor, nombre_productor FROM productores");
                    while ($productor = $resultado_productores->fetch_assoc()) {
                      echo '<option value="' . $productor['id_productor'] . '">' . htmlspecialchars($productor['nombre_productor']) . '</option>';
                    }
                  ?>
                </select>
              </div>

              <!-- Campos del formulario -->
              <div class="mb-3">
                <label for="nombre_productor" class="form-label">Nombre del productor</label>
                <input type="text" class="form-control" id="nombre_productor" name="nombre_productor">
              </div>
              <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
                <input type="text" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
              </div>
              <div class="mb-3">
                <label for="giro" class="form-label">Giro</label>
                <input type="text" class="form-control" id="giro" name="giro">
              </div>
              <div class="mb-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion">
              </div>
              <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="text" class="form-control" id="correo" name="correo">
              </div>
              <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="text" class="form-control" id="contrasena" name="contrasena">
              </div>
              <button type="button" class="btn btn-primary" onclick="setAccionproductor('agregar')">Agregar</button>
              <button type="button" class="btn btn-danger" onclick="setAccionproductor('eliminar')">Eliminar</button>
              <button type="button" class="btn btn-warning" onclick="setAccionproductor('editar')">Editar</button>
              
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
    function setAccionproductor(accion) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: `Vas a ${accion} un productor. ¿Deseas continuar?`,
        icon: 'warning',
        showCancelButton: true,  
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Si el usuario confirma la acción
          document.getElementById('accionproductor').value = accion;
          document.getElementById('editarProductorForm').submit();
        } else {
          // Si el usuario cancela, mostrar un mensaje de cancelación
          Swal.fire({
            title: 'Cancelado',
            text: `La acción de ${accion} ha sido cancelada.`,
            icon: 'error'
          });
        }
      });
    }
    </script>

    <!-- Modal formulario eventos -->
    <div class="modal fade" id="modalAgregarEvento" tabindex="-1" aria-labelledby="modalAgregarEventoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarEventoLabel">Agregar Evento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="agregarEventoForm" method="POST" action="procesar_evento.php" enctype="multipart/form-data">
              <!-- Campo oculto para el ID del productor -->
              <input type="hidden" id="id_evento" name="id_evento" value="">
              <!-- Campo oculto para la acción -->
              <input type="hidden" id="accionevento" name="accion" value="">

              <!-- Campo select para seleccionar la sección -->
              <div class="mb-3">
                <label for="seccion_evento" class="form-label">Seleccionar Sección</label>
                <select class="form-control" id="seccion_evento" name="seccion">
                  <option value="proximos">Próximos Eventos</option>
                  <!--<option value="pasados">Eventos Pasados</option>-->
                  <option value="difusion">Difusión</option>
                </select>
              </div>

              <!-- Campo select para seleccionar un evento -->
              <div class="mb-3">
                  <label for="select_evento" class="form-label">Seleccionar Evento</label>
                  <select class="form-control" id="select_evento" name="id_evento" onchange="cargarDatosevento(this.value)">
                    <option value=""></option>
                    <?php
                      // Obtener los eventos desde la base de datos
                      $resultado_eventos = $conn->query("SELECT id_evento, titulo FROM eventos");
                      while ($evento = $resultado_eventos->fetch_assoc()) {
                        echo '<option value="' . $evento['id_evento'] . '">' . htmlspecialchars($evento['titulo']) . '</option>';
                      }
                    ?>
                  </select>
                </div>

              <!-- Campo para titulo -->
              <div class="mb-3">
                <label for="titulo_evento" class="form-label">Titulo</label>
                <textarea class="form-control" id="titulo_evento" name="titulo" rows="3"></textarea>
              </div>

              <!-- Campo para subir la imagen o video -->
              <div class="mb-3">
                <label for="media_evento" class="form-label">Subir Imagen/Video</label>
                <input type="file" class="form-control" id="media_evento" name="media">
              </div>

              <!-- Campo para la descripción del evento -->
              <div class="mb-3">
                <label for="descripcion_evento" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion_evento" name="descripcion" rows="3"></textarea>
              </div>

              <!-- Campo para la fecha del evento (opcional según sea necesario) -->
              <div class="mb-3">
                <label for="fecha_evento" class="form-label">Fecha del Evento</label>
                <input type="date" class="form-control" id="fecha_evento" name="fecha_evento">
              </div>

              <!-- Botones de acción -->
              <button type="button" class="btn btn-primary" onclick="setAccionevento('agregar')">Agregar</button>
              <button type="button" class="btn btn-danger" onclick="setAccionevento('eliminar')">Eliminar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
    function setAccionevento(accion) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: `Vas a ${accion} un evento. ¿Deseas continuar?`,
        icon: 'warning',
        showCancelButton: true,  
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Si el usuario confirma la acción
          document.getElementById('accionevento').value = accion;
          document.getElementById('agregarEventoForm').submit();
        } else {
          // Si el usuario cancela, mostrar un mensaje de cancelación
          Swal.fire({
            title: 'Cancelado',
            text: `La acción de ${accion} ha sido cancelada.`,
            icon: 'error'
          });
        }
      });
    }
    </script>
                    
  




    

    <!-- Tabla de productores -->
    <table class="table">
      <thead>
        <tr>
          <th>Nombre Productor</th>
          <th>Nombre Negocio</th>
          <th>Horario Tianguis</th>
          <th>Fecha Ingreso</th>
          <th>Giro</th>
          <th>Ubicación</th>
          <th>Correo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre_productor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_negocio']) . "</td>";
                echo "<td>" . htmlspecialchars($row['horario_tianguis']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_ingreso']) . "</td>";
                echo "<td>" . htmlspecialchars($row['giro']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ubicacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay productores registrados</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
      </table>
  </section>

  
  
  

  
    

  <br><br> <!--espacios-->

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de imagenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    
  <!-- script para que funcione el modal formulario -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

   <!-- SweetAlert CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


 

</body>
</html>


 

    