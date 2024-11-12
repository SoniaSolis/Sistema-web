<?php
session_start();
require 'conexion.php';

// Verificar si el carrito está en la sesión
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit;
}

// Obtener los datos del carrito
$carrito = $_SESSION['carrito'];
$productosPorProductor = [];

// Obtener información de la base de datos para cada producto en el carrito
foreach ($carrito as $item) {
    $id_producto = $item['id_producto'];
    $query = "SELECT p.*, pr.nombre_productor, pr.ubicacion 
              FROM productos p 
              JOIN productores pr ON p.id_productor = pr.id_productor 
              WHERE p.id_producto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        $id_productor = $producto['id_productor'];

        // Agrupar productos por productor
        if (!isset($productosPorProductor[$id_productor])) {
            $productosPorProductor[$id_productor] = [
                'nombre_productor' => $producto['nombre_productor'],
                'ubicacion' => $producto['ubicacion'],
                'productos' => [],
                'total' => 0
            ];
        }

        $producto['cantidad'] = $item['cantidad'];
        $productosPorProductor[$id_productor]['productos'][] = $producto;
        $productosPorProductor[$id_productor]['total'] += $producto['precio'] * $item['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <title>Confirmar Compra</title>
</head>
<body>
    <header>
        <nav class="registro">
            <a href="./registro.php">Registrarse / Acceder</a>
            <a href="./cerrar.php">Cerrar sesión</a>
            <a href="./carrito.php"><i class="bi bi-cart4" style="font-size: 2rem"></i></a>
        </nav>
    </header>

    <nav class="navbar navbar-expand-lg">
        <div id="barra" class="container-fluid">
            <a class="navbar-brand">
                <img src="./imagenes/logo.jpg" alt="" width="150px">
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

    <div class="container mt-5">
        <h2>Confirmar Compra</h2>
        <?php foreach ($productosPorProductor as $id_productor => $infoProductor) : ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($infoProductor['nombre_productor']); ?></h3>
                    <p>Ubicación: <?php echo htmlspecialchars($infoProductor['ubicacion']); ?></p>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($infoProductor['productos'] as $producto) : ?>
                            <li class="list-group-item">
                                <img src="imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" width="50">
                                <?php echo htmlspecialchars($producto['nombre_producto']); ?> - Cantidad: <?php echo $producto['cantidad']; ?> - Precio: $<?php echo number_format($producto['precio'], 2); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="card-footer">
                        <h4>Total: $<?php echo number_format($infoProductor['total'], 2); ?></h4>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="generar_pdf.php" method="post">
            <button type="submit" class="btn btn-success">Obtener lista de compras en pdf</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>

