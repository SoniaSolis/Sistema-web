<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estiloregistro.css">
    <link rel="stylesheet" href="./style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Tianguis Alternativo Cultural y Artesanal "El Coperativo"</title>
<body class="text-center">
    
  <header>
    <nav class="registro">
      <a href="./registro.php">Registrarse / Acceder </a>
      
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

  <div class="formularios">
    <form class="inicio"  action="validainicio.php" method="post" onsubmit="return validarCampos()">
    
      <img  src="./imagenes/user.jpg" alt="" width="200" height="200">
        <h1>Iniciar sesión</h1>
      
        <br><input id="correo" name="correo" type="email" placeholder="email" >
        
        <br><br><input id="password" name="password" type="password" placeholder="contraseña" >
        
        <br> <br> <a class="olvidar" href="#">¿Olvidaste la contraseña?</a>
        
        <p></p>

        <br><br><input class="button" type="submit" value="Ingresar">
        
    </form>

    <script>
    function validarCampos() {
        const correo = document.getElementById("correo").value;
        const password = document.getElementById("password").value;

        if (correo.trim() === "" || password.trim() === "") {
            alert("Por favor, completa todos los campos.");
            return false; // Evita enviar el formulario
        }

        return true; // Envía el formulario
    }
    </script>
  

    <form class="registrar" action="almacenar_datos.php" method="post" >
      <img  src="./imagenes/regi.jpg" alt="" width="200" height="200">

      <h1>Registrarse</h1>

      <br><input id="user" name="user" placeholder="nombre usuario" >
  
      <br><br><input id="email" name="email" type="email" placeholder="email" >
        
      <br><br><input id="contrasena" name="contrasena" type="password" placeholder="contraseña" >
      
      <br><br><input id="telefono" name="telefono"  placeholder="telefono" >
      
      <br><br><input class="button" type="submit" value="Registrar"></input>
        
  
    </form>

  </div>
  
  
  <br><br> <!--espacios-->

  <!--llamada a pie de pagina -->
  <iframe src="./pie.html" frameborder="0"></iframe>

  <!--scrip para que funcione carrucel de iamgenes del header-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
 

</body>
 
</html>